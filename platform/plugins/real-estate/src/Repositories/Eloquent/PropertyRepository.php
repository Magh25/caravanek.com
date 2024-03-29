<?php

namespace Botble\RealEstate\Repositories\Eloquent;

use Botble\RealEstate\Enums\ModerationStatusEnum;
use Botble\RealEstate\Enums\PropertyTypeEnum;
use Botble\RealEstate\Repositories\Interfaces\PropertyInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class PropertyRepository extends RepositoriesAbstract implements PropertyInterface
{
    /**
     * {@inheritdoc}
     */
    public function getRelatedProperties(int $propertyId, $limit = 4, array $with = [])
    {
        $currentProperty = $this->findById($propertyId);
        $this->model = $this->originalModel;
        $this->model = $this->model->where('id', '<>', $propertyId)
            ->notExpired();

        if ($currentProperty) {
            $this->model
                ->where('category_id', $currentProperty->category_id)
                ->where('type', $currentProperty->type);
        }

        $params = [
            'condition' => [
                're_properties.moderation_status' => ModerationStatusEnum::APPROVED,
            ],
            'order_by'  => [
                're_properties.created_at' => 'desc',
            ],
            'take'      => $limit,
            'paginate'  => [
                'per_page'      => 12,
                'current_paged' => 1,
            ],
            'select'    => [
                're_properties.*',
            ],
            'with'      => $with,
        ];

        return $this->advancedGet($params);
    }

    /**
     * {@inheritdoc}
     */
    public function getProperties($filters = [], $params = [])
    {

        // $data = $this->model->with('slugable')->where('status', BaseStatusEnum::PUBLISHED);
        // foreach (explode(' ', $query) as $term) {
        //     $data = $data->where('name', 'LIKE', '%' . $term . '%');
        // }



        $filters = array_merge([
            // 'name'     => null,
            'keyword'     => null,
            'type'        => null,
            'bedroom'     => null,
            'bathroom'    => null,
            'floor'       => null,
            'min_square'  => null,
            'max_square'  => null,
            'min_price'   => null,
            'max_price'   => null,
            'category_id' => null,
            'city_id'     => null,
            'location'    => null,
            'features'    => null,
            'sort_by'     => null,
        ], $filters);

        switch ($filters['sort_by']) {
            case 'date_asc':
                $orderBy = [
                    're_properties.created_at' => 'asc',
                ];
                break;
            case 'date_desc':
                $orderBy = [
                    're_properties.created_at' => 'desc',
                ];
                break;
            case 'price_asc':
                $orderBy = [
                    're_properties.price' => 'asc',
                ];
                break;
            case 'price_desc':
                $orderBy = [
                    're_properties.price' => 'desc',
                ];
                break;
            case 'name_asc':
                $orderBy = [
                    're_properties.name' => 'asc',
                ];
                break;
            case 'name_desc':
                $orderBy = [
                    're_properties.name' => 'desc',
                ];
                break;
            default:
                $orderBy = [
                    're_properties.created_at' => 'desc',
                ];
                break;
        }

        $params = array_merge([
            'condition' => [
                're_properties.moderation_status' => ModerationStatusEnum::APPROVED,
            ],
            'order_by'  => [
                're_properties.created_at' => 'desc',
            ],
            'take'      => null,
            'paginate'  => [
                'per_page'      => 10,
                'current_paged' => 1,
            ],
            'select'    => [
                're_properties.*',
            ],
            'with'      => [],
        ], $params);

        $withCount = [];
        if (is_review_enabled()) {
            $withCount = [
                'reviews',
                'reviews as reviews_avg' => function ($query) {
                    $query->select(DB::raw('avg(star)'));
                },
            ];
        }

        $params['withCount'] = $withCount;

        $params['order_by'] = $orderBy;

        $this->model = $this->originalModel->notExpired();

        if( !empty($filters['keyword']) ){
            $this->model = $this->model
                ->where(function (Builder $query) use ($filters) {
                    return $query
                        ->where('re_properties.name', 'LIKE', '%' . $filters['keyword'] . '%')
                        ->orWhere('re_properties.location', 'LIKE', '%' . $filters['keyword'] . '%');
                });
        }

        if ( !empty($filters['type']) ) {
            $this->model = $this->model->whereHas('type', function (Builder $q) use ($filters) {
                if (is_array($filters['type'])) {
                    $q->whereIn('re_property_types.slug', $filters['type']);
                } else {
                    $q->where('re_property_types.slug', $filters['type']);
                }
            });
        }

        if( !empty($filters['ids']) ){
            if( is_string($filters['ids'])) $filters['ids'] = explode(',',$filters['ids']);
            $this->model = $this->model->whereIn('re_properties.id', $filters['ids']);
        }
        
        if( !empty($filters['not_ids']) ){
            if( is_string($filters['not_ids'])) $filters['not_ids'] = explode(',',$filters['not_ids']);
            $this->model = $this->model->whereNotIn('re_properties.id', $filters['not_ids']);
        }
        
        if( !empty($filters['category_ids']) ){
            if( is_string($filters['category_ids'])) $filters['category_ids'] = explode(',',$filters['category_ids']);
            $this->model = $this->model->whereIn('re_properties.category_id', $filters['category_ids']);
        } else if( !empty($filters['category_id']) ) {
            $this->model = $this->model->where('re_properties.category_id', $filters['category_id']);
        }
        
        if( !empty($filters['guests']) ){
            if( $filters['guests'] >= 10)
                $this->model = $this->model->where('re_properties.number_bedroom', '>=', (int)$filters['guests']);
            else
                $this->model = $this->model->where('re_properties.number_bedroom', (int)$filters['guests']);
        }

        if( !empty($filters['latlng']) ){
            $latlng = is_array($filters['latlng']) ? $filters['latlng'] : explode(',',$filters['latlng']);
            $lat = floatval(@$latlng[0]);
            $lng = floatval(@$latlng[1]);

            $dis_radio = 'km'=='miles' ? 69.0 : 111.111;
            $maxDistance = 20;
            $sql_lnglat = $dis_radio." * DEGREES(ACOS(LEAST(1.0, COS(RADIANS(re_properties.latitude)) * COS(RADIANS($lat)) * COS(RADIANS(re_properties.longitude - $lng)) + SIN(RADIANS(re_properties.latitude)) * SIN(RADIANS($lat)))))";

            $pids = array_map(function($r){ return $r->id; },DB::select("SELECT id FROM re_properties WHERE($sql_lnglat) <= $maxDistance"));

            if( empty($pids)) $pids = [0];
            $this->model = $this->model->whereIn("re_properties.id",$pids);
        }

        if( !empty($filters['rating']) ){
            // $pids = array_map(function($r){ return $r->reviewable_id; },DB::select("SELECT `reviewable_id` FROM `re_reviews` WHERE status = 'published' GROUP BY reviewable_id HAVING  AVG(star) BETWEEN ".(intval($filters['rating'])-1)." AND ".intval($filters['rating'])));
            $pids = array_map(function($r){ return $r->reviewable_id; },DB::select("SELECT `reviewable_id` FROM `re_reviews` WHERE status = 'published' GROUP BY reviewable_id HAVING (AVG(star) >= ".intval($filters['rating'])." AND AVG(star) < ".intval($filters['rating']+1).")"));
            if( empty($pids)) $pids = [0];
            $this->model = $this->model->whereIn("re_properties.id",$pids);
        }
        

        /*
        if ($filters['bathroom']) {
            if ($filters['bathroom'] < 5) {
                $this->model = $this->model->where('re_properties.number_bathroom', $filters['bathroom']);
            } else {
                $this->model = $this->model->where('re_properties.number_bathroom', '>=', $filters['bathroom']);
            }
        }

        if ($filters['floor']) {
            if ($filters['floor'] < 5) {
                $this->model = $this->model->where('re_properties.number_floor', $filters['floor']);
            } else {
                $this->model = $this->model->where('re_properties.number_floor', '>=', $filters['floor']);
            }
        }
        if ($filters['min_square'] !== null || $filters['max_square'] !== null) {
            $this->model = $this->model
                ->where(function ($query) use ($filters) {
                    $minSquare = Arr::get($filters, 'min_square');
                    $maxSquare = Arr::get($filters, 'max_square');

                    if ($minSquare !== null) {
                        $query = $query->where('re_properties.square', '>=', $minSquare);
                    }

                    if ($maxSquare !== null) {
                        $query = $query->where('re_properties.square', '<=', $maxSquare);
                    }

                    return $query;
                });
        }
        */
        
        if( !empty($filters['min_price']) || !empty($filters['max_price']) ){
            $this->model = $this->model
                ->where(function ($query) use ($filters) {

                    $minPrice = Arr::get($filters, 'min_price');
                    $maxPrice = Arr::get($filters, 'max_price');

                    /**
                     * @var Builder $query
                     */
                    if ($minPrice !== null) {
                        $query = $query->where('re_properties.price', '>=', $minPrice);
                    }

                    if ($maxPrice !== null) {
                        $query = $query->where('re_properties.price', '<=', $maxPrice);
                    }

                    return $query;
                });
        }

        /*
        if ($filters['features'] !== null) {
            $this->model = $this->model->whereHas('features', function (Builder $q) use ($filters) {
                $q->whereIn('re_features.id', $filters['features']);
            });
        }
        */

        if ($filters['city_id']) {
            $this->model = $this->model->where('re_properties.city_id', $filters['city_id']);
        } elseif ($filters['location']) {
            $locationData = explode(',', $filters['location']);
            if (count($locationData) > 1) {
                $this->model = $this->model
                    ->join('cities', 'cities.id', '=', 're_properties.city_id')
                    ->join('states', 'states.id', '=', 'cities.state_id')
                    ->where(function ($query) use ($locationData) {
                        return $query
                            ->where('cities.name', 'LIKE', '%' . trim($locationData[0]) . '%')
                            ->orWhere('states.name', 'LIKE', '%' . trim($locationData[0]) . '%');
                    });
            } else {
                $this->model = $this->model
                    ->join('cities', 'cities.id', '=', 're_properties.city_id')
                    ->join('states', 'states.id', '=', 'cities.state_id')
                    ->where(function ($query) use ($filters) {
                        return $query
                            ->where('cities.name', 'LIKE', '%' . trim($filters['location']) . '%')
                            ->orWhere('states.name', 'LIKE', '%' . trim($filters['location']) . '%');
                    });
            }
        }

        return $this->advancedGet($params);
    }

    /**
     * {@inheritDoc}
     */
    public function getProperty(int $propertyId, array $with = [])
    {
        $params = [
            'condition' => [
                're_properties.id'                => $propertyId,
                're_properties.moderation_status' => ModerationStatusEnum::APPROVED,
            ],
            'with'      => $with,
            'take'      => 1,
        ];
        $withCount = [];
        if (is_review_enabled()) {
            $withCount = [
                'reviews',
                'reviews as reviews_avg' => function ($query) {
                    $query->select(DB::raw('avg(star)'));
                },
            ];
        }
        $params['withCount'] = $withCount;
        $this->model = $this->originalModel->notExpired();
        return $this->advancedGet($params);
    }

    /**
     * {@inheritDoc}
     */
    public function getPropertiesByConditions(array $condition, $limit, array $with = [], array $withCount = [])
    {
        $this->model = $this->originalModel->notExpired();

        $params = [
            'condition' => $condition,
            'with'      => $with,
            'take'      => $limit,
            'order_by'  => ['re_properties.created_at' => 'desc'],
            'withCount' => $withCount,
        ];

        return $this->advancedGet($params);
    }
}

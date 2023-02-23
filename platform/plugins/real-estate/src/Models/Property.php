<?php

namespace Botble\RealEstate\Models;

use Botble\Base\Models\BaseModel;
use Botble\Base\Traits\EnumCastable;
use Botble\Location\Models\City;
use Botble\RealEstate\Enums\ModerationStatusEnum;
use Botble\RealEstate\Enums\PropertyPeriodEnum;
use Botble\RealEstate\Enums\PropertyTypeEnum;
use Exception;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Arr;
use RvMedia;
use Illuminate\Support\Str;

class Property extends BaseModel
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 're_properties';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'type',
        'description',
        'content',
        'location',
        'images',
        'number_bedroom',
        'number_bathroom',
        'number_floor',
        'square',
        'price',
        'is_featured',
        'currency_id',
        'city_id',
        'period',
        'author_id',
        'author_type',
        'category_id',
        'expire_date',
        'auto_renew',
        'latitude',
        'longitude',
        'type_id',
        'arriving_time',
        'departing_time',
        'no_of_spaces',
        'addons',
        'spaces',
        'unitstype',
        'monthly_price',
        'brand',		
        'made_in',
        'color',		
        'weight',		
        'length',		
        'width',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'moderation_status' => ModerationStatusEnum::class,
        'period'            => PropertyPeriodEnum::class,
    ];

    /**
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'expire_date',
    ];

    /**
     * @return BelongsToMany
     */
    public function features(): BelongsToMany
    {
        return $this->belongsToMany(Feature::class, 're_property_features', 'property_id');
    }
 

    /**
     * @return BelongsToMany
     */
    public function facilities(): BelongsToMany
    {
        return $this->morphToMany(Facility::class, 'reference', 're_facilities_distances')->withPivot('distance');
    }

    /**
     * @param string $value
     * @return array
     */
    public function getImagesAttribute($value)
    {
        try {
            if ($value === '[null]') {
                return [];
            }

            $images = json_decode((string)$value, true);

            if (is_array($images)) {
                $images = array_filter($images);
            }

            return $images ?: [];
        } catch (Exception $exception) {
            return [];
        }
    }

    public function getAddonsAttribute($value)
    {
        try {
            if ( empty($value) ) {
                return [];
            }

            $addons = json_decode((string)$value, true);
            return $addons ?: [];
            
        } catch (Exception $exception) {
            return [];
        }
    }

    public function getSpacesAttribute($value)
    {
        try {
            if ( empty($value) ) {
                return [];
            }

            $spaces = json_decode((string)$value, true);
            return $spaces ?: [];
            
        } catch (Exception $exception) {
            return [];
        }
    }


    public function getUnitstypeAttribute($value)
    {
        try {
            if ( empty($value) ) {
                return [];
            }

            $spaces = json_decode((string)$value, true);
            return $spaces ?: [];
            
        } catch (Exception $exception) {
            return [];
        }
    }

    /**
     * @return string|null
     */
    public function getImageAttribute(): ?string
    {
        return Arr::first($this->images) ?? null;
    }

    /**
     * @return string
     */
    public function getSquareTextAttribute()
    {
        return $this->square . ' ' . setting('real_estate_square_unit', 'm²');
    }

    /**
     * @return BelongsTo
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class)->withDefault();
    }

    /**
     * @return BelongsTo
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class)->withDefault();
    }

    public function getLocation(){
        $city = \DB::select("SELECT c.name city, s.name state FROM `cities` c, states s WHERE c.id = ".(int)$this->city_id." AND s.id = c.state_id");
        return !empty($city[0]) ? $city[0]->city.', '.$city[0]->state : '';
    }

    public function getFeatureValues($only_valus = false){
        $list = [];
        
        if( $only_valus ){
            $ftrs = \DB::select("SELECT f.id,fc.value,f.name,f.type FROM `feature_categories` fc, re_features f WHERE f.id = fc.feature_id AND f.status = 'published' AND trim(fc.value) != '' AND f.show_on_listing = 1 AND fc.property_id = '".(int)$this->id."' order by f.order ASC");

            $feature_lang = [];
            if( !empty($ftrs) ){
                $_feature_lang = \DB::select("SELECT `reference_id`, `field`, `value`  FROM `language_meta` WHERE `reference_type` = 'Botble\\\RealEstate\\\Models\\\Feature' AND `lang_meta_code` = '$this->langCode' AND field LIKE 'name' AND `reference_id` IN (".implode(",",array_map(function($f){ return $f->id; },$ftrs)).")");
                foreach($_feature_lang as $fl){
                    $feature_lang[$fl->reference_id] = $fl->value;
                }
            }
            
            foreach($ftrs as $f){
                if( !empty($feature_lang[$f->id]) )
                    $f->name = $feature_lang[$f->id];

                if( $f->type =='checkbox' ){
                    if( $f->value == 'Y')
                        $list[] = $f->name;
                }
                else
                    $list[] = ($f->type !='select' ? $f->name.' ': '').($f->value);
            }  

            if( empty($this->type->is_fixable) && !empty($this->number_bedroom))
                $list[] =  __("Sleeps").' '.$this->number_bedroom;

        } else {

            $_features = \DB::table('re_features') 
            ->join('re_feature_types', 're_features.id', '=', 're_feature_types.feature_id')
            ->join('re_feature_groups', 're_feature_groups.id', '=', 're_features.group')
            
            ->select('re_features.*','re_feature_groups.name as group_name','re_feature_groups.id as group_id','re_feature_groups.order as gorder')
            ->where('re_feature_types.type_id', '=', (int)$this->type_id)
            ->orderBy('re_feature_groups.order', 'ASC')
            ->get()->toArray();

            $feature_lang = [];
            $feature_grp_lang = [];
            
            if( !empty($_features) ){
                $_feature_lang = \DB::select("SELECT `reference_id`, `field`, `value`  FROM `language_meta` WHERE `reference_type` = 'Botble\\\RealEstate\\\Models\\\Feature' AND `lang_meta_code` = '$this->langCode' AND `reference_id` IN (".implode(",",array_map(function($f){ return $f->id; },$_features)).")");
                foreach($_feature_lang as $fl){
                    $feature_lang[$fl->reference_id][$fl->field] = $fl->value;
                }
                $_feature_grp_lang = \DB::select("SELECT `reference_id`, `field`, `value`  FROM `language_meta` WHERE `reference_type` = 'Botble\\\RealEstate\\\Models\\\FeatureGroups' AND `lang_meta_code` = '$this->langCode' AND field LIKE 'name' AND `reference_id` IN (".implode(",",array_unique(array_map(function($f){ return $f->group_id; },$_features))).")");
                foreach($_feature_grp_lang as $fl){
                    $feature_grp_lang[$fl->reference_id] = $fl->value;
                }
            }
           
            foreach($_features as $fidx => $frow){
                if( isset($feature_lang[$frow->id]['name']) )
                    $_features[$fidx]->name = $feature_lang[$frow->id]['name'];
                if( isset($feature_grp_lang[$frow->group_id]) )
                    $_features[$fidx]->group_name = $feature_grp_lang[$frow->group_id];
            }

            $f_values = $features = [];
            $feature_categories = \DB::table('feature_categories')->select('*')->where('property_id', '=', (int)$this->id)->get()->toArray();
            foreach($feature_categories as $vl){
                $f_values[(int)$vl->feature_id] = trim($vl->value); 
            }

            foreach($_features as $feature){
            
                if( !empty($f_values[(int)$feature->id]) ){
                    $feature->value = $f_values[(int)$feature->id];                    
                    if( !isset($features[$feature->group]))
                        $features[$feature->group] = ['name'=>$feature->group_name];

                    $features[$feature->group]['fields'][$feature->id] =$feature;
                }
            }

            foreach($features as $fid => $ftr){
                usort($ftr['fields'],function($r1,$r2){ return (int)$r1->order > (int)$r2->order; });
                $features[$fid] = (object)$ftr;
            }

            $list = $features;
        }

        return $only_valus ? implode(' / ',$list) :$list;
    }

    /**
     * @return MorphTo
     */
    public function author(): MorphTo
    {
        return $this->morphTo()->withDefault();
    }

    /**
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id')->withDefault();
    }

    /**
     * @return BelongsTo
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class, 'type_id')->withDefault();
    } 



    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotExpired($query)
    {
        return $query->where(function ($query) {
            $query->where('expire_date', '>=', now()->toDateTimeString())
                ->orWhere('never_expired', true);
        });
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeExpired($query)
    {
        return $query->where(function ($query) {
            $query->where('expire_date', '<', now()->toDateTimeString())
                ->where('never_expired', false);
        });
    }

    /**
     * @return string
     */
    public function getCityNameAttribute()
    {
        return $this->city->name . ', ' . $this->city->state->name;
    }

    /**
     * @return string
     */
    public function getTypeNameAttribute()
    {
        return $this->type->name;
    }




    /**
     * @return string
     */
    public function getCategoryNameAttribute()
    {
        return $this->category->name;
    }

    /**
     * @return string|null
     */
    public function getImageThumbAttribute()
    {
        return $this->image ? RvMedia::getImageUrl($this->image, 'medium', false, RvMedia::getDefaultImage()) : null;
    }

    /**
     * @return string|null
     */
    public function getImageSmallAttribute()
    {
        return $this->image ? RvMedia::getImageUrl($this->image, 'thumb', false, RvMedia::getDefaultImage()) : null;
    }

    /**
     * @return string
     */
    public function getPriceHtmlAttribute()
    {
        $price = $this->price_format;

        if ($this->type->slug == PropertyTypeEnum::RENT) {
            $price .= ' / ' . Str::lower($this->period->label());
        }
        return $price;
    }

    /**
     * @return string
     */
    public function getPriceFormatAttribute()
    {
        if ($this->price_formatted) {
            return $this->price_formatted;
        }
        return $this->price_formatted = format_price($this->price, $this->currency);
    }

    /**
     * @return string
     */
    public function getMapIconAttribute()
    {
        return $this->type_name . ': ' . $this->price_format;
    }

    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }









    public function Calendars(): HasMany
    {
        return $this->hasMany(Calendar::class);
    }






}

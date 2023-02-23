<?php

namespace Botble\RealEstate\Tables;

use Botble\RealEstate\Repositories\Interfaces\FeatureInterface;
use Botble\Table\Abstracts\TableAbstract;
use Html;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Throwable;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB; 

class FeatureTable extends TableAbstract
{

    /**
     * @var bool
    **/
    protected $hasActions = true;

    /**
     * @var bool
    **/
    protected $hasFilter = true;

    /**
     * TagTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param FeatureInterface $featureRepository
    **/
    public function __construct(
        DataTables $table,
        UrlGenerator $urlGenerator,
        FeatureInterface $featureRepository
    ) {
        parent::__construct($table, $urlGenerator);

        $this->repository = $featureRepository;
    }

    /**
     * Display ajax response.
     *
     * @return JsonResponse
     * @since 2.1
    */
    public function ajax()
    {

        $data = $this->table
        ->eloquent($this->query())
        ->editColumn('name', function ($item) {
            return Html::link(route('property_feature.edit', $item->id), $item->name);
        })
        ->editColumn('checkbox', function ($item) {
            return $this->getCheckbox($item->id);
        })
        // ->editColumn('group', function ($item) {
        //     if(!$item->group){
        //         return '';
        //     }else{
        //         // $group = DB::table('re_feature_groups')->select('name')->where('id', '=', $item->group)->get();
        //         $group = @DB::select("SELECT * FROM re_feature_groups WHERE id = '".$item->group."'"); 
        //         echo "<pre>";
        //         print_r($group[0]->name);
        //         echo "<pre>";
        //         die;
        //         // return $group[0]->name;
        //     }
        // })
        ->editColumn('type', function ($item) {
            return ucfirst($item->type);
        })
        ->addColumn('operations', function ($item) {
            return $this->getOperations('property_feature.edit', 'property_feature.destroy', $item);
        });
        return $this->toJson($data);
    }


    /**
     * Get the query object to be processed by table.
     *
     * @return \Illuminate\Database\Query\Builder|Builder
     * @since 2.1
     */
    public function query()
    {
        $query = $this->repository->getModel()->select([
            're_features.id',
            're_features.name',
            're_features.type',
        ]);
        // ])->join('re_feature_groups', 're_feature_groups.id', '=', 're_features.group');

        return $this->applyScopes($query);
    }

    /**
     * @return array
     * @since 2.1
     */
    public function columns()
    {
        return [
            'id'   => [
                'name'  => 're_features.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'name' => [
                'name'  => 're_features.name',
                'title' => trans('core/base::tables.name'),
                'class' => 'text-start',
            ],
            'type' => [
                'name'  => 're_features.type',
                'title' => trans('core/base::tables.type'),
                'class' => 'text-start',
            ], 
            // 'group' => [
            //     'name'  => 're_features.group',
            //     'title' => trans('core/base::tables.group'),
            //     'class' => 'text-start',
            // ],
        ];
    }

    /**
     * @return array
     *
     * @throws Throwable
     * @since 2.1
     */
    public function buttons()
    {
        return $this->addCreateButton(route('property_feature.create'), 'property_feature.create');
    }

    /**
     * @return array
     * @throws Throwable
     */
    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('property_feature.deletes'), 'property_feature.destroy',
            parent::bulkActions());
    }

    /**
     * 
     * @return mixed
     * 
    **/
    public function getBulkChanges(): array
    {
        return [
            're_features.name' => [
                'title'    => trans('core/base::tables.name'),
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            're_features.type' => [
                'title'    => trans('core/base::tables.type'),
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            're_features.group' => [
                'title'    => trans('core/base::tables.group'),
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
        ];
    }
}

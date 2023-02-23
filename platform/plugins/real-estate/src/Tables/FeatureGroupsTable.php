<?php

namespace Botble\RealEstate\Tables;

use Auth;
use BaseHelper;
use Botble\RealEstate\Repositories\Interfaces\FeatureGroupsInterface;
use Botble\Table\Abstracts\TableAbstract;
use Html;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Throwable;
use Yajra\DataTables\DataTables;
 
class FeatureGroupsTable extends TableAbstract
{

    /**
     * @var bool
     */
    protected $hasActions = true;

    /**
     * @var bool
     */
    protected $hasFilter = true;

    /**
     * FeatureGroupsTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param FeatureGroupsInterface $featureGroupsRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, FeatureGroupsInterface $featureGroupsRepository)
    {
        parent::__construct($table, $urlGenerator);
        $this->repository = $featureGroupsRepository;
        if (!Auth::user()->hasAnyPermission(['feature_groups.edit', 'feature_groups.destroy'])) {
            $this->hasOperations = false;
            $this->hasActions = false;
        }
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
                if (!Auth::user()->hasPermission('feature_groups.edit')) {
                    return $item->name;
                }

                return Html::link(route('feature_groups.edit', $item->id), $item->name);
            })
            ->editColumn('checkbox', function ($item) {
                return $this->getCheckbox($item->id);
            })
            ->editColumn('created_at', function ($item) {
                return BaseHelper::formatDate($item->created_at);
            })
            ->addColumn('operations', function ($item) {
                return $this->getOperations('feature_groups.edit', 'feature_groups.destroy', $item);
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
            're_feature_groups.id',
            're_feature_groups.name',
            're_feature_groups.icon',
            're_feature_groups.description',
        ]);

        return $this->applyScopes($query);
    }

    /**
     * @return array
     * @since 2.1
     */
    public function columns()
    {
        return [
            'id'         => [
                'name'  => 're_feature_groups.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'name'       => [
                'name'  => 're_feature_groups.name',
                'title' => trans('core/base::tables.name'),
                'class' => 'text-start',
            ],
            'icon'       => [
                'name'  => 're_feature_groups.icon',
                'title' => trans('core/base::tables.icon'),
                'class' => 'text-start',
            ],
            'description'       => [
                'name'  => 're_feature_groups.description',
                'title' => trans('core/base::tables.description'),
                'class' => 'text-start',
            ],
        ];
    }

    /**
     * @return array
     * @throws Throwable
     * @since 2.1
     */
    public function buttons()
    {
        return $this->addCreateButton(route('feature_groups.create'), 'feature_groups.create');
    }

    /**
     * @return array
     * @throws Throwable
     */
    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('feature_groups.deletes'), 'feature_groups.destroy', parent::bulkActions());
    }

    /**
     * @return array
     */
    public function getBulkChanges(): array
    {
        return [
            're_feature_groups.name'       => [
                'title'    => trans('core/base::tables.name'),
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            're_feature_groups.icon'       => [
                'title'    => trans('core/base::tables.icon'),
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            're_feature_groups.description'       => [
                'title'    => trans('core/base::tables.description'),
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
        ];
    }

}

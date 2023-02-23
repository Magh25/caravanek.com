<?php
namespace Botble\RealEstate\Tables;
use Auth;
use BaseHelper;
use Botble\RealEstate\Repositories\Interfaces\AddonInterface;
use Botble\Table\Abstracts\TableAbstract;
use Html;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Throwable;
use Yajra\DataTables\DataTables;

class AddonTable extends TableAbstract
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
     * AddonTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param AddonInterface $addon
    **/
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, AddonInterface $addon)
    {
        parent::__construct($table, $urlGenerator);
        $this->repository = $addon;
        if (!Auth::user()->hasAnyPermission(['addon.edit', 'addon.destroy'])) {
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
                if (!Auth::user()->hasPermission('addon.edit')) {
                    return $item->name;
                }

                return Html::link(route('addon.edit', $item->id), $item->name);
            })
            ->editColumn('checkbox', function ($item) {
                return $this->getCheckbox($item->id);
            })
            ->editColumn('created_at', function ($item) {
                return BaseHelper::formatDate($item->created_at);
            })
            ->addColumn('operations', function ($item) {
                return $this->getOperations('addon.edit', 'addon.destroy', $item);
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
            're_addon.id',
            're_addon.name',
            're_addon.description',
            're_addon.price',
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
                'name'  => 're_addon.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'name'       => [
                'name'  => 're_addon.name',
                'title' => trans('core/base::tables.name'),
                'class' => 'text-start',
            ],
            'description'       => [
                'name'  => 're_addon.description',
                'title' => trans('core/base::tables.description'),
                'class' => 'text-start',
            ],
            'price'       => [
                'name'  => 're_addon.price',
                'title' => trans('core/base::tables.price'),
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
        return $this->addCreateButton(route('addon.create'), 'addon.create');
    }

    /**
     * @return array
     * @throws Throwable
     */
    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('addon.deletes'), 'addon.destroy', parent::bulkActions());
    }

    /**
     * @return array
     */
    public function getBulkChanges(): array
    {
        return [ 
            're_addon.name'       => [
                'title'    => trans('core/base::tables.name'),
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            're_addon.description'       => [
                'title'    => trans('core/base::tables.description'),
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            're_addon.price'       => [
                'title'    => trans('core/base::tables.price'),
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            're_addon.status'       => [
                'title'    => trans('core/base::tables.status'),
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
        ];
    }

}

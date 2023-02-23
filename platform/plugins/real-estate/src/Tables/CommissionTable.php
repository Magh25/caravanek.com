<?php

namespace Botble\RealEstate\Tables;

use Auth;
use BaseHelper;
use Botble\RealEstate\Enums\ConsultStatusEnum;
use Botble\RealEstate\Repositories\Interfaces\ConsultInterface;
use Botble\Table\Abstracts\TableAbstract;
use Html;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Throwable;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class CommissionTable extends TableAbstract
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
     * CommissionTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param ConsultInterface $consultRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, ConsultInterface $consultRepository)
    {
        parent::__construct($table, $urlGenerator);

        $this->repository = $consultRepository;

        if (!Auth::user()->hasAnyPermission(['commission.edit', 'commission.destroy'])) {
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
        $urlsegment = $this->request->segment('2');
        $query = $this->query();
        $query->orWhere("status", "=" , "completed" );



        if (isset($_GET['name'])) {
            $startDate = ''; 
            $endDate = '';
            if($_GET['property_type'] != "null"){
                $query->where('property_type', $_GET['property_type'] );
            }
            $query->where('property_name', 'LIKE', '%'.$_GET['name'].'%' );
            if(auth('account')->isVendor() && $urlsegment != 'booked-by-me'){
                $query->orWhere('name', 'LIKE', '%'.$_GET['name'].'%' );
            }
            $data = DB::table('re_accounts')->select('id')->orWhereRaw("concat(first_name, ' ', last_name) like '%".$_GET['name']."%' ")->get()->toArray()[0]; 
            if(!empty($data)){
                $account_id = $data->id;
                $query->orWhere('vendor_id', '=', $account_id );
            }

            if(isset($_GET['to'])){
                $startDate =  $_GET['to'];
                $endDate  = $_GET['from'];
                $query->whereBetween('from_date', [$startDate, $endDate]);
            }
        }


        $data = $this->table
            ->eloquent($query)
            ->editColumn('name', function ($item) {
                if (!Auth::user()->hasPermission('consult.edit')) {
                    return $item->name;
                }
                return Html::link(route('consult.edit', $item->id), $item->name);
            })
            ->editColumn('checkbox', function ($item) {
                return $this->getCheckbox($item->id);
            })
            ->editColumn('vendor_name', function ($item) {
                return $this->getTheVendor($item);
                // return $item->id;
            })
            ->editColumn('total_price', function ($item) {
                return $item->total_price;
            })
            ->editColumn('commission', function ($item) {
                return $item->commission;
            })
            ->editColumn('created_at', function ($item) {
                return BaseHelper::formatDate($item->created_at);
            })
            ->editColumn('status', function ($item) {
                return $item->status->toHtml();
            })
            ->addColumn('operations', function ($item) {
             return '<a href="commissions/'.$item->id.'"><i class="fa fa-eye" aria-hidden="true"></i></a>'; 
                // return $this->getOperations('consult.edit', 'consult.destroy', $item);
            });

        return $this->toJson($data);
    }

    public function getTheVendor($item)
    {
        $data = DB::table('re_accounts')->select('first_name','last_name')->where('id', '=',  $item->vendor_id )->get()->toArray();
        return $data['0']->first_name.' '.$data['0']->last_name;
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
            're_consults.id',
            're_consults.name',
            're_consults.vendor_id',
            're_consults.commission',
            're_consults.total_price', 
            're_consults.created_at',
            're_consults.status',
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
                'name'  => 're_consults.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'name'       => [
                'name'  => 're_consults.name',
                'title' => trans('core/base::tables.name'),
                'class' => 'text-start',
            ],
            'vendor_name'       => [
                'name'  => 're_consults.vendor_name', 
                'title' => trans('plugins/real-estate::commission.vendor_name'),
                'class' => 'text-start',
            ],
            'commission'       => [
                'name'  => 're_consults.commission', 
                'title' => trans('plugins/real-estate::commission.commission'),
                'class' => 'text-start',
            ],
            'total_price'       => [
                'name'  => 're_consults.total_price', 
                'title' => trans('plugins/real-estate::commission.total_price'),
                'class' => 'text-start',
            ],
            // 'email'      => [
            //     'name'  => 're_consults.email',
            //     'title' => trans('plugins/real-estate::consult.email.header'),
            //     'class' => 'text-start',
            // ],
            // 'phone'      => [
            //     'name'  => 're_consults.phone',
            //     'title' => trans('plugins/real-estate::consult.phone'),
            // ],
            // 'from_date'      => [
            //     'name'  => 're_consults.from_date',
            //     'title' => trans('plugins/real-estate::consult.from_date'),
            // ],
            // 'to_date'      => [
            //     'name'  => 're_consults.to_date',
            //     'title' => trans('plugins/real-estate::consult.to_date'),
            // ],
            'created_at' => [
                'name'  => 're_consults.created_at',
                'title' => trans('core/base::tables.created_at'),
                'width' => '100px',
            ],
            'status'     => [
                'name'  => 're_consults.status',
                'title' => trans('core/base::tables.status'),
                'width' => '100px',
            ],
        ];
    }

    /**
     * @return array
     * @throws Throwable
     */
    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('commission.deletes'), 'commission.destroy', parent::bulkActions());
    }

    /**
     * @return array
     */
    public function getBulkChanges(): array
    {
        return [
            're_consults.name'       => [
                'title'    => trans('core/base::tables.name'),
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            're_consults.status'     => [
                'title'    => trans('core/base::tables.status'),
                'type'     => 'select',
                'choices'  => ConsultStatusEnum::labels(),
                'validate' => 'required|in:' . implode(',', ConsultStatusEnum::values()),
            ],
            're_consults.created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type'  => 'date',
            ],
        ];
    }
}

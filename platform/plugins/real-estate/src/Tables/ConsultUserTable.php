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
use Botble\Base\Enums\BaseStatusEnum;


class ConsultUserTable extends TableAbstract
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
     * ConsultUserTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param ConsultInterface $consultRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, ConsultInterface $consultRepository)
    {
        parent::__construct($table, $urlGenerator);
        $this->repository = $consultRepository;
    
        // if (!auth('account')->isVendor()) {
            $this->hasOperations = true;
            $this->hasActions = true;

            // $this->hasOperations = false;
            // $this->hasActions = false;
        // }
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
        if(auth('account')->isVendor() && $urlsegment != 'booked-by-me'){
            $query->where('vendor_id', auth('account')->id());
        }else{
            $query->where('user_id', auth('account')->id());
        }
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
            if(isset($_GET['to'])){
                $startDate =  $_GET['to'];
                $endDate  = $_GET['from'];
                $query->whereBetween('from_date', [$startDate, $endDate]);
            }
        }

        $data = $this->table
        ->eloquent($query)
        ->editColumn('id', function ($item) {
            return 'CR000'.$item->id;
        }) 
        ->editColumn('property_name', function ($item) {
            return $item->property_name.' Property Type: '.$item->property_type;
        })
        ->editColumn('name', function ($item) {
            return $item->name;
        })
        ->editColumn('checkbox', function ($item) {
            return $this->getCheckbox($item->id);
        })
        ->editColumn('created_at', function ($item) {
            return BaseHelper::formatDate($item->created_at);
        })
        ->editColumn('date', function ($item) {
            $count = '';
            
            if($item->property_type == 'Parking'){
                $count = 'No of Spaces: '.$item->guests;
            }else{
                $count = 'No of Guest: '.$item->guests;
            } 
            return  str_replace("-","/", $item->from_date).' - '.str_replace("-","/", $item->to_date).' '.$count;
        }) 
        ->editColumn('total_price', function ($item) { 
            return $item->total_price;
        })  
        ->editColumn('updated_at', function ($item) { 
            return $item->updated_at;
        }) 
        ->editColumn('status', function ($item) { 
             return $item->status; 
        }) 
        ->addColumn('operations', function($item){
             $urlsegment = $this->request->segment('2'); 
            if(auth('account')->isVendor()  && $urlsegment != 'booked-by-me'){
                $btn = '<a href="my-bookings/'.$item->id.'" class="btn btn-icon btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-original-title="Edit"><i class="fa fa-eye"></i></a>';
                $btn .= '<a href="my-bookings/edit/'.$item->id.'" class="btn btn-icon btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-original-title="Edit"><i class="fa fa-edit"></i></a>';
            }else{
                $btn = '<a href="booked-by-me/'.$item->id.'" class="btn btn-icon btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-original-title="Edit"><i class="fa fa-eye"></i></a>';
                $btn .= '<a href="booked-by-me/edit/'.$item->id.'" class="btn btn-icon btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-original-title="Edit"><i class="fa fa-edit"></i></a>';
            }
            return $btn;
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
            're_consults.id',
            're_consults.name',
            're_consults.property_name',
            're_consults.property_type', 
            're_consults.from_date',
            're_consults.to_date',
            're_consults.guests',
            're_consults.total_price',
            're_consults.updated_at', 
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
        $data = [
            'id'        => [
                'name'  => 're_consults.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            
            'property_name'      => [
                'name'  => 're_consults.property_name',
                'title' => trans('core/base::tables.property_name'),
                'class' => 'text-start',
            ], 
            'updated_at'   => [
                'name'  => 're_consults.updated_at',
                'title' => trans('plugins/real-estate::consult.updated_at'),
            ],
            'total_price' => [
                'name'  => 're_consults.total_price',
                'title' => trans('core/base::tables.total_price'),
                'width' => '100px',
            ],
            'date'    => [
                'name'  => 're_consults.booking_date',
                'title' => trans('core/base::tables.booking_date'),
            ],
            'status'    => [
                'name'  => 're_consults.status',
                'title' => trans('core/base::tables.status'), 
            ],
        ];
        $last_segment = $this->request->segment(2);
        if(auth('account')->isVendor() && $last_segment == "my-bookings"){
            $data_name = [
                'name'      => [
                    'name'  => 're_consults.name',
                    'title' => trans('core/base::tables.name'),
                    'class' => 'text-start',
                ],
            ];
            $data = array_merge($data_name,$data);
        }
        return  $data;

    }

    /**
     * @return array
     * @throws Throwable
     */
    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('consult.deletes'), 'consult.destroy', parent::bulkActions());
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

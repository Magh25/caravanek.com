<?php

namespace Botble\RealEstate\Tables;

use BaseHelper;
use Botble\RealEstate\Repositories\Interfaces\AccountInterface;
use Botble\Table\Abstracts\TableAbstract;
use Html;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Facades\Auth;
use RvMedia;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
class AccountTable extends TableAbstract
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
     * AccountTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param AccountInterface $accountRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, AccountInterface $accountRepository)
    {
        parent::__construct($table, $urlGenerator);

        $this->repository = $accountRepository;

        if (!Auth::user()->hasAnyPermission(['account.edit', 'account.destroy'])) {
            $this->hasOperations = false;
            $this->hasActions = false;
        }

        $item = DB::select("SELECT COUNT(id) as count, author_id FROM `re_properties` GROUP BY author_id");
        $this->result = array_map(function ($value) {
            return (array)$value;
        }, $item); 
 

        // $item =  DB::table('re_accounts')->select('role')->where('id', '=', $item->id)->get()->toArray();
    }

    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @since 2.1
     */
    public function ajax()
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('first_name', function ($item) {
                if (!Auth::user()->hasPermission('account.edit')) {
                    return $item->name;
                } 
                return Html::link(route('account.edit', $item->id), $item->name);
            })
            ->editColumn('avatar_id', function ($item) {
                return Html::image(
                    RvMedia::getImageUrl(
                        $item->avatar->url, 
                        'thumb', 
                        false, 
                        RvMedia::getDefaultImage()
                    ), 
                    $item->name, ['width' => 50]
                );
            })
            ->editColumn('checkbox', function ($item) {
                return $this->getCheckbox($item->id);
            })
            // ->editColumn('user_properties', function ($item) {
            //     return $this->GetUserPropertyCount($item);
            // })
            ->editColumn('role', function ($item) {
                return $this->GetUserRole($item); // : __('User');
            })
            ->editColumn('created_at', function ($item) {
                return BaseHelper::formatDate($item->created_at);
            })
            ->addColumn('operations', function ($item) {
                return $this->getOperations('account.edit', 'account.destroy', $item);
            });

        return $this->toJson($data);
    } 

    public function GetUserRole($item)
    { 
        $item =  DB::table('re_accounts')->select('role')->where('id', '=', $item->id)->get()->toArray();
        // SELECT COUNT(id), author_id FROM `re_properties` GROUP BY author_id;

        return  ($item[0]->role == 'v') ?  __('Vendor') :  __('User') ;
    }


    public function GetUserPropertyCount($item)
    {  
        $count = '';
        foreach ($this->result as $key => $val) { 
            if($val['author_id'] == $item->id){
                $count = $val['count'];
            } 
        }  
        return $count;
    }


    /**
     * Get the query object to be processed by the table.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     *
     * @since 2.1
     */
    public function query()
    {
        $query = $this->repository->getModel()->select([
            'id',
            'first_name',
            'last_name',
            'email',
            'created_at',
            // 'credits',
            'avatar_id',
        ])->with(['avatar']);

        return $this->applyScopes($query);
    }

    /**
     * @return array
     *
     * @since 2.1
     */
    public function columns()
    {
        return [
            'id'         => [
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'avatar_id'  => [
                'title' => trans('core/base::tables.image'),
                'width' => '70px',
            ],
            'first_name' => [
                'title' => trans('core/base::tables.name'),
                'class' => 'text-start',
            ],
            'role'      => [
                'title' => trans('plugins/real-estate::property.role'),
                'class' => 'text-start',
            ],
            'email'      => [
                'title' => trans('core/base::tables.email'),
                'class' => 'text-start',
            ],

            // 'email'      => [
            //     'title' => trans('core/base::tables.email'),
            //     'class' => 'text-start',
            // ],
            // 'credits'    => [
            //     'title' => trans('plugins/real-estate::account.credits'),
            //     'class' => 'text-start',
            // ],

            // 'user_properties' => [
            //     'name'  => 'user_properties',
            //     'title' => trans('plugins/real-estate::property.user_properties'),
            //     'width' => '100px',
            //     'class' => 'text-start',
            // ],
            'created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'width' => '100px',
            ],
        ];
    }

    /**
     * @return array
     *
     * @throws \Throwable
     * @since 2.1
     */
    public function buttons()
    {
        return $this->addCreateButton(route('account.create'), 'account.create');
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('account.deletes'), 'account.destroy', parent::bulkActions());
    }

    /**
     * @return array
     */
    public function getBulkChanges(): array
    {
        return [
            'first_name' => [
                'title'    => trans('plugins/real-estate::account.first_name'),
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            'last_name'  => [
                'title'    => trans('plugins/real-estate::account.last_name'),
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            'email'      => [
                'title'    => trans('core/base::tables.email'),
                'type'     => 'text',
                'validate' => 'required|max:120|email',
            ],
            'created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type'  => 'date',
            ],
        ];
    }
}

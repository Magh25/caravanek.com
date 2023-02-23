<?php

namespace Botble\RealEstate\Tables;

use BaseHelper;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Blog\Exports\PostExport;
use Botble\Blog\Repositories\Interfaces\CategoryInterface;
use Botble\Blog\Repositories\Interfaces\PostInterface;
use Botble\Table\Abstracts\TableAbstract;
use Html;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables; 
use Botble\RealEstate\Models\Account;

class PostAccountTable extends TableAbstract
{
    /**
     * @var bool
     */
    protected $hasActions = false;



    public $hasCheckbox = false;


    /**
     * @var bool
     */
    protected $hasFilter = false;

    /**
     * @var CategoryInterface
     */
    protected $categoryRepository;

    /**
     * @var string
     */
    protected $exportClass = PostExport::class;

    /**
     * @var int
     */
    protected $defaultSortColumn = 6;


    // auth('account')->user()

    /**
     * PostTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param PostInterface $postRepository
     * @param CategoryInterface $categoryRepository
     */
    public function __construct(
        DataTables $table,
        UrlGenerator $urlGenerator,
        PostInterface $postRepository 
    ) {
        parent::__construct($table, $urlGenerator);

        $this->repository = $postRepository; 

        // if (!auth('account')->user()->hasAnyPermission(['public.account.blogs.edit', 'public.account.blogs.destroy'])) {
            $this->hasOperations = true;
            $this->hasActions = true;
        // }
    }

    /**
     * {@inheritDoc}
     */
    public function ajax()
    {
        $query = $this->query();

        $data = $this->table
            ->eloquent($query)
            ->editColumn('name', function ($item) {
                if (!auth('account')->user()->canPost()) {
                    return $item->name;
                }

                return Html::link(route('public.account.blogs.edit', $item->id), $item->name);
            })
            ->editColumn('image', function ($item) {
                return $this->displayThumbnail($item->image);
            })
            ->editColumn('checkbox', function ($item) {
                return $this->getCheckbox($item->id);
            })
            ->editColumn('created_at', function ($item) {
                return BaseHelper::formatDate($item->created_at);
            })
            ->editColumn('updated_at', function ($item) {
                $categories = '';
                foreach ($item->categories as $category) {
                    $categories .=   $category->name .' , ';
                }

                return rtrim($categories, ', ');
            })
            ->editColumn('author_id', function ($item) {
                return $item->author ? $item->author->name : null;
            })
            ->editColumn('status', function ($item) {
                if ($this->request()->input('action') === 'excel') {
                    return $item->status->getValue();
                }

                return $item->status->toHtml();
            })
            // ->addColumn('operations', function ($item) {
            //     return $this->getOperations('public.account.blogs.edit', 'public.account.blogs.destroy', $item);
            // });
            ->addColumn('operations', function ($item) {
                $edit = 'public.account.blogs.edit';
                $delete = 'public.account.blogs.destroy';

                return view('plugins/real-estate::account.table.actions', compact('edit', 'delete', 'item'))->render();
            });

        return $this->toJson($data);
    }

    /**
     * {@inheritDoc}
     */
    public function query()
    {
        $query = $this->repository->getModel()
            ->with([
                'categories' => function ($query) {
                    $query->select(['categories.id', 'categories.name']);
                },
                'author',
            ])
            ->select([
                'posts.id',
                'posts.name',
                'posts.image',
                'posts.created_at',
                'posts.status',
                'posts.updated_at',
                'posts.author_id',
                'posts.author_type',
                'posts.news',
            ])
            ->where([
                'posts.author_id'   => auth('account')->id(),
                'posts.author_type' => Account::class,
            ])
            ;

        return $this->applyScopes($query);
    }

    /**
     * {@inheritDoc}
     */
    public function columns()
    {
        return [
            'id'         => [
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'image'      => [
                'title' => trans('core/base::tables.image'),
                'width' => '70px',
            ],
            'name'       => [
                'title' => trans('core/base::tables.name'),
                'class' => 'text-start',
            ],
            
            'updated_at' => [
                'title'     => trans('plugins/blog::posts.categories'),
                'width'     => '150px',
                'class'     => 'no-sort text-center',
                'orderable' => false,
            ],
            // 'author_id'  => [
            //     'title'     => trans('plugins/blog::posts.author'),
            //     'width'     => '150px',
            //     'class'     => 'no-sort text-center',
            //     'orderable' => false,
            // ],
            'created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'width' => '100px',
                'class' => 'text-center',
            ],
            'status'     => [
                'title' => trans('core/base::tables.status'),
                'width' => '100px',
                'class' => 'text-center',
            ],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function buttons()
    {
        $buttons = [];
        if (auth('account')->user()->canPost()) {
            $buttons = $this->addCreateButton(route('public.account.blogs.create'));
        } 
        return $buttons;

        // return $this->addCreateButton(route('public.account.blogs.create'), 'posts.create');
    }

    /**
     * {@inheritDoc}
     */
    // public function bulkActions(): array
    // {
    //     return $this->addDeleteAction(route('posts.deletes'), 'posts.destroy', parent::bulkActions());
    // }

    /**
     * {@inheritDoc}
     */
    // public function getBulkChanges(): array
    // {
    //     return [
    //         'name'       => [
    //             'title'    => trans('core/base::tables.name'),
    //             'type'     => 'text',
    //             'validate' => 'required|max:120',
    //         ],
    //         'status'     => [
    //             'title'    => trans('core/base::tables.status'),
    //             'type'     => 'customSelect',
    //             'choices'  => BaseStatusEnum::labels(),
    //             'validate' => 'required|in:' . implode(',', BaseStatusEnum::values()),
    //         ],
    //         'category'         => [
    //             'title'    => trans('plugins/blog::posts.category'),
    //             'type'     => 'select-search',
    //             'validate' => 'required',
    //             'callback' => 'getCategories',
    //         ],
    //         'created_at' => [
    //             'title'    => trans('core/base::tables.created_at'),
    //             'type'     => 'date',
    //             'validate' => 'required',
    //         ],
    //     ];
    // }

    /**
     * @return array
     */
    // public function getCategories(): array
    // {
    //     return $this->categoryRepository->pluck('name', 'id');
    // }

    /**
     * {@inheritDoc}
     */
    // public function applyFilterCondition($query, string $key, string $operator, ?string $value)
    // {
    //     switch ($key) {
    //         case 'created_at':
    //             if (!$value) {
    //                 break;
    //             }

    //             $value = BaseHelper::formatDate($value);

    //             return $query->whereDate($key, $operator, $value);
    //         case 'category':
    //             if (!$value) {
    //                 break;
    //             }

    //             if (!BaseHelper::isJoined($query, 'post_categories')) {
    //                 $query = $query
    //                     ->join('post_categories', 'post_categories.post_id', '=', 'posts.id')
    //                     ->join('categories', 'post_categories.category_id', '=', 'categories.id')
    //                     ->select($query->getModel()->getTable() . '.*');
    //             }

    //             return $query->where('post_categories.category_id', $value);
    //     }

    //     return parent::applyFilterCondition($query, $key, $operator, $value);
    // }

    /**
     * {@inheritDoc}
     */
    // public function saveBulkChangeItem($item, string $inputKey, ?string $inputValue)
    // {
    //     if ($inputKey === 'category') {
    //         $item->categories()->sync([$inputValue]);

    //         return $item;
    //     }

    //     return parent::saveBulkChangeItem($item, $inputKey, $inputValue);
    // }

    /**
     * {@inheritDoc}
     */
    public function getDefaultButtons(): array
    {
        return [
            // 'export',
            'reload',
        ];
    }
}

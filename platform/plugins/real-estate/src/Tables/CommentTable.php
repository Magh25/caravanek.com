<?php

namespace Botble\RealEstate\Tables;

use BaseHelper;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\RealEstate\Repositories\Interfaces\CommentInterface;
use Botble\RealEstate\Repositories\Interfaces\ReviewInterface;
use Botble\Table\Abstracts\TableAbstract;
use Html;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class CommentTable extends TableAbstract
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
     * ReviewTable constructor.
     * @param DataTables      $table
     * @param UrlGenerator    $urlGenerator
     * @param ReviewInterface $reviewRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, CommentInterface $commentRepository)
    {
        parent::__construct($table, $urlGenerator);

        $this->repository = $commentRepository;

        if (!Auth::user()->hasAnyPermission(['comments.edit', 'comments.destroy'])) {
            $this->hasOperations = false;
            $this->hasActions = false;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function ajax()
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('able_id', function ($item) {
                if (!empty($item->able)) {
                    return Html::link($item->able->url,
                        $item->able->name,
                        ['target' => '_blank']
                    );
                }
                return null;
            })
            // ->editColumn('star', function ($item) {
            //     return view('plugins/real-estate::reviews.partials.rating', ['star' => $item->star])->render();
            // })
            ->editColumn('account_id', function ($item) {
                return !empty($item->account->id) ? Html::link(route('account.edit', $item->account->id), $item->account->name)->toHtml() : '';
            })
            ->editColumn('checkbox', function ($item) {
                return $this->getCheckbox($item->id);
            })
            ->editColumn('status', function ($item) {
                return $item->status->toHtml();
            })
            ->editColumn('created_at', function ($item) {
                return BaseHelper::formatDate($item->created_at);
            })
            ->addColumn('operations', function ($item) {
                return view('plugins/real-estate::reviews.partials.actions', compact('item'))->render();
            })
            ;

        return $this->toJson($data);
    }

    /**
     * {@inheritDoc}
     */
    public function query()
    {
        $query = $this->repository->getModel()
            ->select([
                're_comments.id',
                // 're_comments.star',
                're_comments.comment',
                're_comments.able_id',
                're_comments.able_type',
                're_comments.account_id',
                're_comments.status',
                're_comments.created_at',
            ])
            ->with(['account', 'able']);

        return $this->applyScopes($query);
    }

    /**
     * {@inheritDoc}
     */
    public function columns()
    {
        return [
            'id'            => [
                'name'  => 're_comments.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
                'class' => 'text-start',
            ],
            'able_id' => [
                'name'  => 're_comments.able_id',
                'title' => trans('post'),
                'class' => 'text-start',
            ],
            'account_id'    => [
                'name'  => 're_comments.account_id',
                'title' => trans('plugins/real-estate::review.user'),
                'class' => 'text-start',
            ],
            // 'star'          => [
            //     'name'  => 're_comments.star',
            //     'title' => trans('plugins/real-estate::review.star'),
            //     'class' => 'text-center',
            // ],
            'comment'       => [
                'name'  => 're_comments.comment',
                'title' => trans('plugins/real-estate::review.comment'),
                'class' => 'text-start',
            ],
            'status'        => [
                'name'  => 're_comments.status',
                'title' => trans('plugins/real-estate::review.status'),
                'class' => 'text-center',
            ],
            'created_at'    => [
                'name'  => 're_comments.created_at',
                'title' => trans('core/base::tables.created_at'),
                'width' => '100px',
                'class' => 'text-start',
            ],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('comments.deletes'), 'comment.destroy', parent::bulkActions());
    }

    /**
     * {@inheritDoc}
     */
    public function getBulkChanges(): array
    {
        return [
            're_comments.status'     => [
                'title'    => trans('core/base::tables.status'),
                'type'     => 'select',
                'choices'  => BaseStatusEnum::labels(),
                'validate' => 'required|in:' . implode(',', BaseStatusEnum::values()),
            ],
            // 're_comments.created_at' => [
            //     'title' => trans('core/base::tables.created_at'),
            //     'type'  => 'date',
            // ],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function renderTable($data = [], $mergeData = [])
    {
        if ($this->query()->count() === 0 &&
            !$this->request()->wantsJson() &&
            $this->request()->input('filter_table_id') !== $this->getOption('id')
        ) {
            return view('plugins/real-estate::reviews.intro');
        }

        return parent::renderTable($data, $mergeData);
    }
}

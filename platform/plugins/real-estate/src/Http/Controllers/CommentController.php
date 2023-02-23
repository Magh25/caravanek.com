<?php

namespace Botble\RealEstate\Http\Controllers;

use Assets;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\RealEstate\Repositories\Interfaces\ReviewInterface;
use Botble\RealEstate\Repositories\Interfaces\CommentInterface;
use Botble\RealEstate\Tables\CommentTable;
use Botble\RealEstate\Tables\ReviewTable;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class CommentController extends BaseController
{
    /**
     * @var ReviewInterface
     */
    protected $commentRepository;

     
    public function __construct(
        CommentInterface $commentRepository
        )
    {
        $this->commentRepository = $commentRepository;
    }

     
    public function index(CommentTable $dataTable)
    {
        page_title()->setTitle(trans('plugins/real-estate::review.name'));

        Assets::addStylesDirectly('vendor/core/plugins/real-estate/css/review.css');

        return $dataTable->renderTable();
    }

    /**
     * @param Request $request
     * @param int $id
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function destroy(Request $request, $id, BaseHttpResponse $response)
    {
        try {
            $review = $this->commentRepository->findOrFail($id);
            $this->commentRepository->delete($review);

            event(new DeletedContentEvent(COMMENT_MODULE_SCREEN_NAME, $request, $review));

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Exception
     */
    public function deletes(Request $request, BaseHttpResponse $response)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return $response
                ->setError()
                ->setMessage(trans('core/base::notices.no_select'));
        }

        foreach ($ids as $id) {
            $review = $this->commentRepository->findOrFail($id);
            $this->commentRepository->delete($review);

            event(new DeletedContentEvent(COMMENT_MODULE_SCREEN_NAME, $request, $review));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}

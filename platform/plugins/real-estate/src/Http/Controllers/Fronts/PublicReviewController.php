<?php

namespace Botble\RealEstate\Http\Controllers\Fronts;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\RealEstate\Http\Requests\CommentRequest;
use Botble\RealEstate\Repositories\Interfaces\ReviewInterface;
use Botble\RealEstate\Http\Requests\ReviewRequest;
use Botble\RealEstate\Models\Comment;
use Botble\RealEstate\Models\ReviewMeta;
use Botble\RealEstate\Repositories\Interfaces\CommentInterface;

class PublicReviewController
{

    /**
     * @var ReviewInterface
     */
    protected $reviewRepository;

    /**
     * @var CommentInterface
     */
    protected $commentRepository ;
   

   

    /**
     * PublicReviewController constructor.
     * @param ReviewInterface $reviewRepository
     */
    public function __construct(
        ReviewInterface $reviewRepository
        // CommentInterface $commentRepository
        
    ) {
        $this->reviewRepository = $reviewRepository;
        // $this->commentRepository = $commentRepository;
    }


    /**
     * @param ReviewRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function postCreateReview(ReviewRequest $request, BaseHttpResponse $response)
    {
        $exists = $this->reviewRepository->count([
            'account_id' => auth('account')->id(),
            'reviewable_id'  => $request->input('reviewable_id'),
            'reviewable_type'  => $request->input('reviewable_type'),
        ]);
        
        if ($exists > 0) {
            return $response
                ->setError()
                ->setMessage(__('You have reviewed this product already!'));
        }

        $request->merge(['account_id' => auth('account')->id()]);

        $review = $this->reviewRepository->createOrUpdate($request->input());
        
        foreach ($request->input('meta') as $key => $value) {
            ReviewMeta::setMeta($key, $value, $review->id);
        }

        return $response->setMessage(__('Added review successfully!'));
    }

    /**
     * @param int $id
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Exception
     */
    public function getDeleteReview($id, BaseHttpResponse $response)
    {
        $review = $this->reviewRepository->findOrFail($id);

        if (auth()->check() || (auth('account')->check() && auth('account')->id() == $review->account_id)) {

            $review->meta()->delete();
            $this->reviewRepository->delete($review);

            return $response->setMessage(__('Deleted review successfully!'));
        }

        abort(401);
    }







    // public function postCreateComment(CommentRequest $request, BaseHttpResponse $response)
    // { 
         
    //     $request->merge(['account_id' => auth('account')->id()]);
    //     // dd($request->input());
        
    //     $review = $this->commentRepository->createOrUpdate($request->input());
    //     // $review = Comment::create($request->input());
         

    //     return $response->setMessage(__('Added review successfully!'));
    // }




}

<?php

namespace Botble\RealEstate\Http\Controllers\Fronts;

use App\Http\Controllers\Controller;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Blog\Models\Post;
use Botble\RealEstate\Http\Requests\CommentRequest;
use Botble\RealEstate\Http\Requests\LikeRequest;
use Botble\RealEstate\Repositories\Interfaces\ReviewInterface;
use Botble\RealEstate\Http\Requests\ReviewRequest;
use Botble\RealEstate\Models\Comment;
use Botble\RealEstate\Models\Like;
use Botble\RealEstate\Models\ReviewMeta;
use Botble\RealEstate\Repositories\Interfaces\CommentInterface;
use Botble\Support\Http\Requests\Request;

class PublicCommentController  
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
        // CommentInterface $commentRepository
        
    ) { 
        // $this->commentRepository = $commentRepository;
    }
 






    public function postCreateComment(CommentRequest $request, BaseHttpResponse $response)
    { 
         
        $request->merge(['account_id' => auth('account')->id()]);
        // dd($request['able_id']);
        
        // $review = $this->commentRepository->createOrUpdate($request->input());
        $review = Comment::create($request->input());
         

        return $response->setMessage(__('Added Comment successfully!'));
    }




    public function postLike(LikeRequest $request, BaseHttpResponse $response)
    { 
         

        $Like = Like::where('account_id', auth('account')->id())
                    ->where('able_id', $request->input('able_id'))
                    ->where('able_type', $request->input('able_type'))
                    ->get();

        // dd( $Like->count() );    
        if( $Like->count() > 0){ 
            return $response->setMessage(__('You have liked  already!'));
        }
        $request->merge(['account_id' => auth('account')->id()]); 
        $review = Like::create($request->input());
         

        return $response->setMessage(__('Added Like successfully!'));
    }


    




    


}

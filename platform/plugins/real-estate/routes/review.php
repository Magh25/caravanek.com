<?php

Route::group(['namespace' => 'Botble\RealEstate\Http\Controllers', 'middleware' => ['web', 'core']], function () {
    Route::group(['prefix' => BaseHelper::getAdminPrefix(), 'middleware' => 'auth'], function () {
       
        Route::group(['prefix' => 'reviews', 'as' => 'reviews.'], function () {
            Route::resource('', 'ReviewController')->parameters(['' => 'review'])->only(['index', 'destroy']);

            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'ReviewController@deletes',
                'permission' => 'reviews.destroy',
            ]);
        });

        // -----------------------
        Route::group(['prefix' => 'comments', 'as' => 'comments.'], function () {
            Route::resource('', 'CommentController')->parameters(['' => 'comment'])->only(['index', 'destroy']);

            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'CommentController@deletes',
                'permission' => 'comments.destroy',
            ]);
        });
        // -----------------------
        



    });
});

Route::group(['namespace' => 'Botble\RealEstate\Http\Controllers\Fronts', 'middleware' => ['web', 'core', 'account']],
    function () {
        Route::group(apply_filters(BASE_FILTER_GROUP_PUBLIC_ROUTE, []), function () {
            Route::post('review/create', [
                'as'   => 'public.reviews.create',
                'uses' => 'PublicReviewController@postCreateReview',
            ]);

            Route::get('review/delete/{id}', [
                'as'   => 'public.reviews.destroy',
                'uses' => 'PublicReviewController@getDeleteReview',
            ]);
        });
    });




// Route::group(['namespace' => 'Botble\RealEstate\Http\Controllers', 'middleware' => ['web', 'core']], function () {
//     Route::group(['prefix' => BaseHelper::getAdminPrefix(), 'middleware' => 'auth'], function () {
//         Route::group(['prefix' => 'reviews', 'as' => 'reviews.'], function () {
//             Route::resource('', 'ReviewController')->parameters(['' => 'review'])->only(['index', 'destroy']);

//             Route::delete('items/destroy', [
//                 'as'         => 'deletes',
//                 'uses'       => 'ReviewController@deletes',
//                 'permission' => 'reviews.destroy',
//             ]);
//         });
//     });
// });

Route::group(['namespace' => 'Botble\RealEstate\Http\Controllers\Fronts', 'middleware' => ['web', 'core', 'account']],
    function () {
        Route::group(apply_filters(BASE_FILTER_GROUP_PUBLIC_ROUTE, []), function () {
            Route::post('comment/create', [
                'as'   => 'public.comment.create',
                'uses' => 'PublicCommentController@postCreateComment',
            ]);

            // Route::get('comment/delete/{id}', [
            //     'as'   => 'public.comment.destroy',
            //     'uses' => 'PublicCommentController@getDeleteComment',
            // ]);
            
            Route::post('post/like', [
                'as'   => 'public.post.like',
                'uses' => 'PublicCommentController@postLike',
            ]);
    
            
        });
    });


// Route::group(['namespace' => 'Botble\RealEstate\Http\Controllers', 'middleware' => ['web', 'core', 'account']],
// function () {
//     Route::group(apply_filters(BASE_FILTER_GROUP_PUBLIC_ROUTE, []), function () {
        
        
//         Route::post('post/like', [
//             'as'   => 'public.post.like',
//             'uses' => 'AccountBlogController@postLike',
//         ]);

        
//     });
// });

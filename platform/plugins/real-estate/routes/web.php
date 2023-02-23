<?php

use Botble\RealEstate\Models\Category;
use Botble\RealEstate\Models\Property;

Route::group(['namespace' => 'Botble\RealEstate\Http\Controllers', 'middleware' => ['web', 'core']], function () {

    Route::group([
        'prefix'     => BaseHelper::getAdminPrefix() . '/properties',
        'middleware' => 'auth',
    ], function () {

        Route::get('settings', [
            'as'   => 'real-estate.settings',
            'uses' => 'RealEstateController@getSettings',
        ]);

        Route::post('settings', [
            'as'         => 'real-estate.settings.post',
            'uses'       => 'RealEstateController@postSettings',
            'permission' => 'real-estate.settings',
        ]);

        Route::group(['prefix' => 'properties', 'as' => 'property.'], function () {
            Route::resource('', 'PropertyController')
                ->parameters(['' => 'property']);

            // Route::get('get-features-by-category/{category_id}', [
            //     'as'         => 'get_features_by_category',
            //     'uses'       => 'PropertyController@get_features_by_category',
            // ]);    
            
            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'PropertyController@deletes',
                'permission' => 'property.destroy',
            ]);
        });

        Route::group(['prefix' => 'property-features', 'as' => 'property_feature.'], function () {
            Route::resource('', 'FeatureController')
                ->parameters(['' => 'property_feature']);

            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'FeatureController@deletes',
                'permission' => 'property_feature.destroy',
            ]);
        });

        Route::group(['prefix' => 'consults', 'as' => 'consult.'], function () {
            Route::resource('', 'ConsultController')
                ->parameters(['' => 'consult'])
                ->except(['create', 'store']);

            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'ConsultController@deletes',
                'permission' => 'consult.destroy',
            ]);
        });

        Route::group(['prefix' => 'commissions', 'as' => 'commission.'], function () {
            Route::resource('', 'CommissionController')
                ->parameters(['' => 'commission'])
                ->except(['create', 'store']);

            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'CommissionController@deletes',
                'permission' => 'consult.destroy',
            ]);
        });

        Route::group(['prefix' => 'categories', 'as' => 'property_category.'], function () {
            Route::resource('', 'CategoryController')
                ->parameters(['' => 'category']);
            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'CategoryController@deletes',
                'permission' => 'property_category.destroy',
            ]);
        });


        /*Feature Groups code start here*/
        Route::group(['prefix' => 'groups', 'as' => 'feature_groups.'], function (){
            Route::resource('', 'FeatureGroupsController')->parameters(['' => 'feature_groups']);
            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'FeatureGroupsController@deletes',
                'permission' => 'feature_groups.destroy',
            ]);
        });  
       
        Route::group(['prefix' => 'addon', 'as' => 'addon.'], function (){
            Route::resource('', 'AddonController')->parameters(['' => 'addon']);
            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'AddonController@deletes',
                'permission' => 'addon.destroy',
            ]);
        });
        /*Feature Groups code end here*/


        Route::group(['prefix' => 'types', 'as' => 'property_type.'], function () {
            Route::resource('', 'TypeController')->parameters(['' => 'type']);
            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'TypeController@deletes',
                'permission' => 'property_type.destroy',
            ]);
        });

        Route::group(['prefix' => 'facilities', 'as' => 'facility.'], function () {
            Route::resource('', 'FacilityController')
                ->parameters(['' => 'facility']);
            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'FacilityController@deletes',
                'permission' => 'facility.destroy',
            ]);
        });

        Route::group(['prefix' => 'accounts', 'as' => 'account.'], function () {

            Route::resource('', 'AccountController')
                ->parameters(['' => 'account']);

            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'AccountController@deletes',
                'permission' => 'account.destroy',
            ]);

            Route::get('list', [
                'as'         => 'list',
                'uses'       => 'AccountController@getList',
                'permission' => 'account.index',
            ]);

            Route::post('credits/{id}', [
                'as'         => 'credits.add',
                'uses'       => 'TransactionController@postCreate',
                'permission' => 'account.edit',
            ]);
        });
// php artisan route:list
        Route::group(['prefix' => 'packages', 'as' => 'package.'], function () {
            Route::resource('', 'PackageController')
                ->parameters(['' => 'package']);
            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'PackageController@deletes',
                'permission' => 'package.destroy',
            ]);
        });

    });












    // ------------------------------------------------

    
     Route::get('/payment_status', 'PublicController@getConsultAfterPayment' )->name('payment_status');
    //  Route::post('/callback_url', 'PublicController@callback_url' )->name('callback_url');

    // Route::get('/return_url', 'PublicController@return_url' )->name('return_url');
    
    // Route::get('/return_url', 'PublicController@return_url' )->name('return_url');
    // // -----------------------------------------------

    // Route::get('/contact', 'PublicController@contact' )->name('contact');

    if (defined('THEME_MODULE_SCREEN_NAME')) {

        Route::group(apply_filters(BASE_FILTER_GROUP_PUBLIC_ROUTE, []), function () {

            Route::get('listing', 'PublicController@getProperties')
                ->name('public.properties');

            Route::get('listing/{type}', 'PublicController@getPropertyType')
                ->name('public.properties-type');

            Route::get(SlugHelper::getPrefix(Category::class, 'property-category') . '/{slug}',
                'PublicController@getPropertyCategory')
                ->name('public.property-category');

            Route::get(SlugHelper::getPrefix(Property::class, 'properties') . '/{slug}',
                'PublicController@getProperty');

            Route::post('creating-booking', 'PublicController@postSendConsult')->name('public.send.consult');
                


         

            Route::get('currency/switch/{code?}', [
                'as'   => 'public.change-currency',
                'uses' => 'PublicController@changeCurrency',
            ]);

            Route::group(['as' => 'public.account.'], function () {

                Route::group(['middleware' => ['account.guest']], function () {
                    Route::get('login', 'LoginController@showLoginForm')
                        ->name('login');
                    Route::post('login', 'LoginController@login')
                        ->name('login.post');

                    Route::get('register', 'RegisterController@showRegistrationForm')
                        ->name('register');
                    Route::post('register', 'RegisterController@register')
                        ->name('register.post');

                    Route::get('verify', 'RegisterController@getVerify')
                        ->name('verify');

                    Route::get('password/request',
                        'ForgotPasswordController@showLinkRequestForm')
                        ->name('password.request');
                    Route::post('password/email',
                        'ForgotPasswordController@sendResetLinkEmail')
                        ->name('password.email');
                    Route::post('password/reset', 'ResetPasswordController@reset')
                        ->name('password.update');
                    Route::get('password/reset/{token}',
                        'ResetPasswordController@showResetForm')
                        ->name('password.reset');
                });

                Route::group([
                    'middleware' => [
                        setting('verify_account_email',
                            config('plugins.real-estate.real-estate.verify_email')) ? 'account.guest' : 'account',
                    ],
                ], function () {
                    Route::get('register/confirm/resend',
                        'RegisterController@resendConfirmation')
                        ->name('resend_confirmation');
                    Route::get('register/confirm/{user}', 'RegisterController@confirm')
                        ->name('confirm');
                });
            });

            Route::get('feed/properties', [
                'as'   => 'feeds.properties',
                'uses' => 'PublicController@getPropertyFeeds',
            ]);

            Route::group(['middleware' => ['account'], 'as' => 'public.account.'], function () {
                Route::group(['prefix' => 'account'], function () {

                    Route::post('logout', 'LoginController@logout')
                        ->name('logout');

                    Route::get('dashboard', [
                        'as'   => 'dashboard',
                        'uses' => 'PublicAccountController@getDashboard',
                    ]);

                    Route::get('settings', [
                        'as'   => 'settings',
                        'uses' => 'PublicAccountController@getSettings',
                    ]);

                    Route::post('settings', [
                        'as'   => 'post.settings',
                        'uses' => 'PublicAccountController@postSettings',
                    ]);

                    /* New routes start */
                    Route::get('booked-by-me', [
                        'as'   => 'booked-by-me',
                        'uses' => 'PublicAccountController@myBookings',
                    ]);
                    Route::get('booked-by-me/{id}', [
                        'as'   => 'booked-by-me',
                        'uses' => 'PublicAccountController@singleBookings',
                    ]);
                    Route::post('booked-by-me', [
                        'as'   => 'booked-by-me',
                        'uses' => 'PublicAccountController@bookedByMe',
                    ]); 
                    Route::get('booked-by-me/edit/{id}', [
                        'as'   => 'my-bookings',
                        'uses' => 'PublicAccountController@editMyBooking',
                    ]);
                    Route::post('booked-by-me/edit/{id}', [
                        'as'   => 'my-bookings',
                        'uses' => 'PublicAccountController@updateBookedByMe',
                    ]);
                    // ******************************
                    Route::get('my-bookings', [
                        'as'   => 'my-bookings',
                        'uses' => 'PublicAccountController@myBookings',
                    ]);
                    Route::get('my-bookings/{id}', [
                        'as'   => 'my-bookings',
                        'uses' => 'PublicAccountController@singleBookings',
                    ]);
                    Route::get('my-bookings/edit/{id}', [
                        'as'   => 'my-bookings',
                        'uses' => 'PublicAccountController@editBookingStatus',
                    ]);
                    Route::post('my-bookings/edit/{id}', [
                        'as'   => 'my-bookings',
                        'uses' => 'PublicAccountController@updateBookingStatus',
                    ]);
                    Route::post('my-bookings', [
                        'as'   => 'my-bookings',
                        'uses' => 'PublicAccountController@myBookings',
                    ]);
                    // ******************************
                    Route::get('commissions', [
                        'as'   => 'commissions',
                        'uses' => 'PublicAccountController@commissions',
                    ]); 
                    Route::get('commissions/{id}', [
                        'as'   => 'commissions',
                        'uses' => 'PublicAccountController@singleCommissionsBooking',
                    ]);
                    // Route::get('my-bookings/edit/{id}', [
                    //     'as'   => 'my-bookings',
                    //     'uses' => 'PublicAccountController@editBookingStatus',
                    // ]);
                    // Route::post('my-bookings/edit/{id}', [
                    //     'as'   => 'my-bookings',
                    //     'uses' => 'PublicAccountController@updateBookingStatus',
                    // ]);
                    Route::post('commissions', [
                        'as'   => 'commissions',
                        'uses' => 'PublicAccountController@commissions',
                    ]);
                    /* New Route end */







                    Route::get('security', [
                        'as'   => 'security',
                        'uses' => 'PublicAccountController@getSecurity',
                    ]);

                    Route::put('security', [
                        'as'   => 'post.security',
                        'uses' => 'PublicAccountController@postSecurity',
                    ]);

                    Route::post('avatar', [
                        'as'   => 'avatar',
                        'uses' => 'PublicAccountController@postAvatar',
                    ]);

                    Route::get('packages', [
                        'as'   => 'packages',
                        'uses' => 'PublicAccountController@getPackages',
                    ]);

                    Route::get('transactions', [
                        'as'   => 'transactions',
                        'uses' => 'PublicAccountController@getTransactions',
                    ]);

                });

                Route::group(['prefix' => 'account/ajax'], function () {
                    Route::get('activity-logs', [
                        'as'   => 'activity-logs',
                        'uses' => 'PublicAccountController@getActivityLogs',
                    ]);

                    Route::get('transactions', [
                        'as'   => 'ajax.transactions',
                        'uses' => 'PublicAccountController@ajaxGetTransactions',
                    ]);

                    Route::post('upload', [
                        'as'   => 'upload',
                        'uses' => 'PublicAccountController@postUpload',
                    ]);

                    Route::post('upload-from-editor', [
                        'as'   => 'upload-from-editor',
                        'uses' => 'PublicAccountController@postUploadFromEditor',
                    ]);

                    Route::get('packages', 'PublicAccountController@ajaxGetPackages')
                        ->name('ajax.packages');
                    Route::put('packages', 'PublicAccountController@ajaxSubscribePackage')
                        ->name('ajax.package.subscribe');
                });

                Route::group(['prefix' => 'account/properties', 'as' => 'properties.'], function () {
                    Route::resource('', 'AccountPropertyController')
                        ->parameters(['' => 'property']);

                    Route::post('renew/{id}', [
                        'as'   => 'renew',
                        'uses' => 'AccountPropertyController@renew',
                    ]);

                    Route::get('calendar/{id}', [
                        'as'   => 'calendar',
                        'uses' => 'AccountPropertyController@calendar',
                    ]); 
                    Route::post('calendar_create/{id}', [
                        'as'   => 'calendar_create',
                        'uses' => 'AccountPropertyController@calendar_create',
                    ]);
                    Route::post('calendar_update/{id}', [
                        'as'   => 'calendar_update',
                        'uses' => 'AccountPropertyController@calendar_update',
                    ]);
                    Route::post('calendar_delete/{id}', [
                        'as'   => 'calendar_delete',
                        'uses' => 'AccountPropertyController@calendar_delete',
                    ]);
                    
                });






                // ------------blog-----------
                
                
                Route::group(['prefix' => 'account/blogs', 'as' => 'blogs.'], function () {
                    Route::resource('', 'AccountBlogController')
                        ->parameters(['' => 'blog']);


                        Route::get('getAllTags', [
                            'as'         => 'getAllTags',
                            'uses'       => 'AccountBlogController@getAllTags',
                            // 'permission' => 'tags.index',
                        ]);
        
 
                });


                // ------------blog-----------


                Route::group(['prefix' => 'account'], function () {
                    Route::get('packages/{id}/subscribe', 'PublicAccountController@getSubscribePackage')
                        ->name('package.subscribe');

                    Route::get('packages/{id}/subscribe/callback',
                        'PublicAccountController@getPackageSubscribeCallback')
                        ->name('package.subscribe.callback');
                });
                
            });
        });
    }
});

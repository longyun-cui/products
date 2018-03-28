<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
//    return view('welcome');
    return redirect('/peoples');
});


/*Common 通用功能*/
Route::group(['prefix' => 'common'], function () {

    $controller = "CommonController";

    // 验证码
    Route::match(['get','post'], 'change_captcha', $controller.'@change_captcha');

    //
    Route::get('dataTableI18n', function () {
        return trans('pagination.i18n');
    });
});




/*
 * User Frontend
 */
Route::group(['namespace' => 'Front'], function () {

    Route::get('/', function () {
        return redirect('/peoples');
    });

    Route::get('peoples', 'RootController@view_peoples');
    Route::get('people', 'RootController@view_people');
    Route::get('products', 'RootController@view_products');
    Route::get('product', 'RootController@view_product');
    Route::get('events', 'RootController@view_events');
    Route::get('event', 'RootController@view_event');

});



/*
 * auth
 */
Route::match(['get','post'], 'login', 'AuthController@user_login');
Route::match(['get','post'], 'logout', 'AuthController@user_logout');
Route::match(['get','post'], 'register', 'AuthController@user_register');


/*
 * Admin Backend
 */
Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function () {


    Route::match(['get','post'], 'login', 'AuthController@admin_login');
    Route::match(['get','post'], 'logout', 'AuthController@admin_logout');
    Route::match(['get','post'], 'register', 'AuthController@admin_register');

    /*
     * 需要登录
     */
    Route::group(['middleware' => 'admin'], function () {

        $adminController = 'AdminController';

        Route::get('/', $adminController.'@index');

        // 作者
        Route::group(['prefix' => 'people'], function () {

            $controller = 'PeopleController';

            Route::get('/', $controller.'@index');
            Route::get('create', $controller.'@createAction');
            Route::match(['get','post'], 'edit', $controller.'@editAction');
            Route::match(['get','post'], 'list', $controller.'@viewList');
            Route::post('delete', $controller.'@deleteAction');
            Route::post('enable', $controller.'@enableAction');
            Route::post('disable', $controller.'@disableAction');

            Route::match(['get','post'], 'product', $controller.'@view_people_product_list');
        });

        // 作品
        Route::group(['prefix' => 'product'], function () {

            $controller = 'ProductController';

            Route::get('/', $controller.'@index');
            Route::get('create', $controller.'@createAction');
            Route::match(['get','post'], 'edit', $controller.'@editAction');
            Route::match(['get','post'], 'list', $controller.'@viewList');
            Route::post('delete', $controller.'@deleteAction');
            Route::post('enable', $controller.'@enableAction');
            Route::post('disable', $controller.'@disableAction');


            Route::get('select2_peoples', $controller.'@select2_peoples');

        });

        // 作品
        Route::group(['prefix' => 'event'], function () {

            $controller = 'EventController';

            Route::get('/', $controller.'@index');
            Route::get('create', $controller.'@createAction');
            Route::match(['get','post'], 'edit', $controller.'@editAction');
            Route::match(['get','post'], 'list', $controller.'@viewList');
            Route::post('delete', $controller.'@deleteAction');
            Route::post('enable', $controller.'@enableAction');
            Route::post('disable', $controller.'@disableAction');


            Route::get('select2_peoples', $controller.'@select2_peoples');
            Route::get('select2_products', $controller.'@select2_products');

        });

        // 组
        Route::group(['prefix' => 'group'], function () {

            $controller = 'GroupController';

            Route::get('/', $controller.'@index');
            Route::get('create', $controller.'@createAction');
            Route::match(['get','post'], 'edit', $controller.'@editAction');
            Route::match(['get','post'], 'list', $controller.'@viewList');
            Route::post('delete', $controller.'@deleteAction');
            Route::post('enable', $controller.'@enableAction');
            Route::post('disable', $controller.'@disableAction');


            Route::get('select2_peoples', $controller.'@select2_peoples');
            Route::get('select2_products', $controller.'@select2_products');

        });
    });
});


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

            $peopleController = 'PeopleController';

            Route::get('/', $peopleController.'@index');
            Route::get('create', $peopleController.'@createAction');
            Route::match(['get','post'], 'edit', $peopleController.'@editAction');
            Route::match(['get','post'], 'list', $peopleController.'@viewList');
            Route::post('delete', $peopleController.'@deleteAction');
            Route::post('enable', $peopleController.'@enableAction');
            Route::post('disable', $peopleController.'@disableAction');

            Route::match(['get','post'], 'product', $peopleController.'@view_people_product_list');
        });

        // 作品
        Route::group(['prefix' => 'product'], function () {

            $productController = 'ProductController';

            Route::get('/', $productController.'@index');
            Route::get('create', $productController.'@createAction');
            Route::match(['get','post'], 'edit', $productController.'@editAction');
            Route::match(['get','post'], 'list', $productController.'@viewList');
            Route::post('delete', $productController.'@deleteAction');
            Route::post('enable', $productController.'@enableAction');
            Route::post('disable', $productController.'@disableAction');


            Route::get('select2', $productController.'@select2');

        });

        // 组
        Route::group(['prefix' => 'group'], function () {

            $groupController = 'GroupController';

            Route::get('/', $groupController.'@index');
            Route::get('create', $groupController.'@createAction');
            Route::match(['get','post'], 'edit', $groupController.'@editAction');
            Route::match(['get','post'], 'list', $groupController.'@viewList');
            Route::post('delete', $groupController.'@deleteAction');
            Route::post('enable', $groupController.'@enableAction');
            Route::post('disable', $groupController.'@disableAction');


            Route::get('select2', $groupController.'@select2');

        });
    });
});


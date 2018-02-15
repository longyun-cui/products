<?php

namespace App\Http\Controllers\Admin;

use function foo\func;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

//use App\Services\Admin\ProductService;
use App\Repositories\Admin\ProductRepository;


class ProductController extends Controller
{
    //
    private $service;
    private $repo;
    public function __construct()
    {
//        $this->service = new ProductService;
        $this->repo = new ProductRepository;
    }


    public function index()
    {
        return $this->repo->index();
    }

    // 列表
    public function viewList()
    {
        if(request()->isMethod('get')) return view('admin.product.list');
        else if(request()->isMethod('post')) return $this->repo->get_list_datatable(request()->all());
    }

    // 创建
    public function createAction()
    {
        return $this->repo->view_create();
    }

    // 编辑
    public function editAction()
    {
        if(request()->isMethod('get')) return $this->repo->view_edit();
        else if (request()->isMethod('post')) return $this->repo->save(request()->all());
    }

    // 【删除】
    public function deleteAction()
    {
        return $this->repo->delete(request()->all());
    }

    // 【分享】
    public function enableAction()
    {
        return $this->repo->enable(request()->all());
    }

    // 【取消分享】
    public function disableAction()
    {
        return $this->repo->disable(request()->all());
    }


    // 【取消分享】
    public function select2()
    {
        return $this->repo->select2(request()->all());
    }






}

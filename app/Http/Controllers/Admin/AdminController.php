<?php

namespace App\Http\Controllers\Admin;

use function foo\func;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Repositories\Admin\AdminRepository;


class AdminController extends Controller
{
    //
    private $repo;
    public function __construct()
    {
//        $this->repo = new AdminRepository;
    }


    public function index()
    {
        return view('admin.index');
    }



}

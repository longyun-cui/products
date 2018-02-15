<?php

namespace App\Http\Controllers\Front;

use function foo\func;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Repositories\Front\RootRepository;


class RootController extends Controller
{
    //
    private $repo;
    public function __construct()
    {
        $this->repo = new RootRepository;
    }


    public function view_peoples()
    {
        return $this->repo->view_peoples(request()->all());
    }

    public function view_people()
    {
        return $this->repo->view_people(request()->all());
    }

    public function view_products()
    {
        return $this->repo->view_products(request()->all());
    }

    public function view_product()
    {
        return $this->repo->view_product(request()->all());
    }



}

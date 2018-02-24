<?php
namespace App\Repositories\Front;

use App\Models\People;
use App\Models\Product;

use App\Repositories\Common\CommonRepository;

use Response, Auth, Validator, DB, Exception;
use QrCode;

class RootRepository {

    private $model;
    public function __construct()
    {
    }

    public function view_peoples($post_data)
    {
        $peoples = People::select('*')->orderBy('id','desc')->paginate(20);
        return view('frontend.root.peoples')->with(['datas'=>$peoples,'people_active'=>'active']);
    }

    public function view_people($post_data)
    {
        $people_encode = $post_data['id'];
        $people_decode = decode($people_encode);
        if(!$people_decode && intval($people_decode) !== 0) return view('frontend.404');

        $people = People::select('*')->with([
            'products'=>function($query) { $query->orderBy('id','desc'); }
        ])->where('id',$people_decode)->first();
        return view('frontend.root.people')->with(['people'=>$people]);
    }

    public function view_products($post_data)
    {
        $products = Product::select('*')->with(['peoples'])->orderBy('id','desc')->paginate(20);
        return view('frontend.root.products')->with(['datas'=>$products,'product_active'=>'active']);
    }

    public function view_product($post_data)
    {
        $product_encode = $post_data['id'];
        $product_decode = decode($product_encode);
        if(!$product_decode && intval($product_decode) !== 0) return view('frontend.404');

        $product = Product::with(['peoples'])->where('id',$product_decode)->first();
        return view('frontend.root.product')->with(['data'=>$product]);
    }



}
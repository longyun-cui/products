<?php
namespace App\Repositories\Front;

use App\Models\People;
use App\Models\Product;
use App\Models\Event;

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
        return view('frontend.root.peoples')->with(['peoples'=>$peoples,'people_active'=>'active']);
    }

    public function view_people($post_data)
    {
        $people_encode = $post_data['id'];
        $people_decode = decode($people_encode);
        if(!$people_decode && intval($people_decode) !== 0) return view('frontend.404');

        $people = People::select('*')->with([
            'products'=>function($query) { $query->with(['peoples'])->orderBy('id','desc'); }
        ])->where('id',$people_decode)->first();
        $peoples[0] = $people;
        return view('frontend.root.people')->with(['people'=>$people,'peoples'=>$peoples]);
    }



    public function view_products($post_data)
    {
        $products = Product::select('*')->with(['peoples'])->orderBy('id','desc')->paginate(20);
        return view('frontend.root.products')->with(['products'=>$products,'product_active'=>'active']);
    }

    public function view_product($post_data)
    {
        $product_encode = $post_data['id'];
        $product_decode = decode($product_encode);
        if(!$product_decode && intval($product_decode) !== 0) return view('frontend.404');

        $product = Product::with(['peoples'])->where('id',$product_decode)->first();
        $products[0] = $product;
        return view('frontend.root.product')->with(['product'=>$product,'products'=>$products]);
    }



    public function view_events($post_data)
    {
        $events = Event::select('*')->orderBy('id','desc')->paginate(20);
        return view('frontend.root.events')->with(['events'=>$events,'event_active'=>'active']);
    }

    public function view_event($post_data)
    {
        $event_encode = $post_data['id'];
        $event_decode = decode($event_encode);
        if(!$event_decode && intval($event_decode) !== 0) return view('frontend.404');

        $event = Event::select('*')->where('id',$event_decode)->first();
        $events[0] = $event;
        return view('frontend.root.event')->with(['event'=>$event,'events'=>$events]);
    }



}
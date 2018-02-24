<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Pivot_Product_People extends Model
{
    //
    protected $table = "pivot_product_people";
    protected $fillable = [
        'sort', 'type', 'active', 'product_id', 'people_id', 'description'
    ];
    protected $dateFormat = 'U';


    // 作品
    function product()
    {
        return $this->hasOne('App\Models\Product','product_id','id');
    }

    // 人
    function people()
    {
        return $this->hasOne('App\Models\People','people_id','id');
    }





}

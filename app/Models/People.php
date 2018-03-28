<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class People extends Model
{
    //
    protected $table = "peoples";
    protected $fillable = [
        'sort', 'type', 'active', 'admin_id', 'name', 'description', 'content', 'major', 'nation', 'birth', 'death', 'cover_pic',
        'is_shared', 'visit_num', 'share_num'
    ];
    protected $dateFormat = 'U';


    // 管理员
    function admin()
    {
        return $this->belongsTo('App\Administrator','admin_id','id');
    }

    // 一对多 作品
    function _products()
    {
        return $this->hasMany('App\Models\Product','people_id','id');
    }

    // 多对多 关联的作品
    function products()
    {
        return $this->belongsToMany('App\Models\Product','pivot_product_people','people_id','product_id');
    }

    /**
     * 获得此人的所有标签。
     */
    public function tags()
    {
        return $this->morphToMany('App\Models\Tag', 'taggable');
    }




}

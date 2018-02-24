<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    protected $table = "products";
    protected $fillable = [
        'sort', 'type', 'active', 'admin_id', 'people_id', 'category', 'name', 'title', 'description', 'content', 'time', 'is_shared',
        'visit_num', 'share_num'
    ];
    protected $dateFormat = 'U';


    // 管理员
    function admin()
    {
        return $this->belongsTo('App\Administrator','admin_id','id');
    }

    // 所属
    function people()
    {
        return $this->belongsTo('App\Models\People','people_id','id');
    }

    // 多对多 关联的人
    function peoples()
    {
        return $this->belongsToMany('App\Models\People','pivot_product_people','product_id','people_id');
    }


    /**
     * 获得此作品的所有标签。
     */
    public function tags()
    {
        return $this->morphToMany('App\Models\Tag', 'taggable');
    }




}

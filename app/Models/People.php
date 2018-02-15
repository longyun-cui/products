<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class People extends Model
{
    //
    protected $table = "peoples";
    protected $fillable = [
        'sort', 'type', 'active', 'admin_id', 'name', 'description', 'content', 'major', 'nation', 'birth', 'death', 'is_shared',
        'visit_num', 'share_num'
    ];
    protected $dateFormat = 'U';


    // 管理员
    function admin()
    {
        return $this->belongsTo('App\Administrator','admin_id','id');
    }

    // 作品
    function products()
    {
        return $this->hasMany('App\Models\Product','people_id','id');
    }

    /**
     * 获得此人的所有标签。
     */
    public function tags()
    {
        return $this->morphToMany('App\Models\Tag', 'taggable');
    }




}

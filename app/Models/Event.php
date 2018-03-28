<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    //
    protected $table = "events";
    protected $fillable = [
        'sort', 'type', 'active', 'admin_id', 'title', 'description', 'content', 'start_time', 'end_time', 'category', 'cover_pic',
        'is_shared', 'visit_num', 'share_num'
    ];
    protected $dateFormat = 'U';


    // 管理员
    function admin()
    {
        return $this->belongsTo('App\Administrator','admin_id','id');
    }


    /**
     * 获得此作品的所有标签。
     */
    public function tags()
    {
        return $this->morphToMany('App\Models\Tag', 'taggable');
    }




}

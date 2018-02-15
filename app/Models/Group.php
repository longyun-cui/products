<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    //
    protected $table = "groups";
    protected $fillable = [
        'sort', 'type', 'active', 'admin_id', 'title', 'description', 'content', 'visit_num', 'share_num'
    ];
    protected $dateFormat = 'U';


    // 管理员
    function admin()
    {
        return $this->belongsTo('App\Administrator','admin_id','id');
    }

    /**
     * 获得此分组下所有的人。
     */
    public function peoples()
    {
        return $this->morphedByMany('App\Models\People', 'groupable');
    }

    /**
     *  获得此分组下所有的作品。
     */
    public function products()
    {
        return $this->morphedByMany('App\Models\Product', 'groupable');
    }




}

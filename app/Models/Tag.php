<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    //
    protected $table = "tags";
    protected $fillable = [
        'sort', 'type', 'content', 'visit_num', 'share_num'
    ];
    protected $dateFormat = 'U';



    /**
     * 获得此标签下所有的人。
     */
    public function peoples()
    {
        return $this->morphedByMany('App\Models\People', 'taggable');
    }

    /**
     *  获得此标签下所有的作品。
     */
    public function products()
    {
        return $this->morphedByMany('App\Models\Product', 'taggable');
    }




}

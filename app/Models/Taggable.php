<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Taggable extends Model
{
    //
    protected $table = "taggables";
    protected $fillable = [
        'sort', 'type', 'tag_id', 'taggable_id', 'taggable_type'
    ];
    protected $dateFormat = 'U';



}

<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Groupable extends Model
{
    //
    protected $table = "taggables";
    protected $fillable = [
        'sort', 'type', 'group_id', 'groupable_id', 'groupable_type'
    ];
    protected $dateFormat = 'U';



}

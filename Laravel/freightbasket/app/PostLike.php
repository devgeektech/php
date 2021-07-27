<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class PostLike extends Model
{

    protected $table = 'post_likes';

    public $incrementing = false;

    protected $primaryKey = ['timeline_id', 'like_user_id'];

    protected $dates = [
        'created_at',
        'updated_at'
    ];


    public function timeline(){
        return $this->belongsTo('App\Timeline', 'timeline_id');
    }


    public function user(){
        return $this->belongsTo('App\User', 'like_user_id');
    }

}
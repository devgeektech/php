<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class PostComment extends Model
{

    protected $table = 'post_comments';

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public function user(){
        return $this->belongsTo('App\User', 'comment_user_id');
    }

    public function post(){
        return $this->belongsTo('App\Timeline', 'timeline_id');
    }

}
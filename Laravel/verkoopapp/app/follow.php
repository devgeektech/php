<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class follow extends Model
{
    public function scopeGetFollowerId($query, $id)
    {
        return $query->select(['user_id'])->where('follower_id', $id)->get();
    }

    public static function getFollowingIds($user_id)
    {
        return self::where('follower_id', $user_id)->pluck('user_id');
    }
}

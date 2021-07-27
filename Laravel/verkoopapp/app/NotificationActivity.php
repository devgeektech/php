<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotificationActivity extends Model
{
    protected $table = 'notification_activity';

    public function items()
    {
        return $this->belongsTo(Items::class, 'id', 'notification_id');
    }

    public function follow()
    {
        return $this->belongsTo(follow::class, 'id', 'notification_id');
    }

    public function items_like()
    {
        return $this->belongsTo(like::class, 'id', 'notification_id');
    }

    public function rating()
    {
        return $this->belongsTo(Rating::class, 'id', 'notification_id');
    }

    public function comments()
    {
        return $this->belongsTo(comments::class, 'id', 'notification_id');
    }

    public static function getActivityData($user_id, $followings)
    {
        $data = self::with(['items', 'items_like', 'rating', 'comments'])->orderBy('created_at', 'desc')->where('to', $user_id);
        if (count($followings)) {
            $data = $data->orWhereRaw('`to` = 0 && `from` in ('.implode(',', $followings).')');
        }

        return $data->get();
    }
}

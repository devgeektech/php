<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Items extends Model
{
    public $table = 'items';

    public function scopeSearchName($query, $id)
    {
        return $query->select(['name'])->where('id', $id)->first();
    }

    public function scopeGetUserId($query, $id)
    {
        return $query->select(['user_id'])->where('id', $id)->first();
    }

    public function user()
    {
        return $this->belongsTo(User::class)->select(array('id', 'first_name', 'username', 'profile_pic'));
    }

    public function items_images()
    {
        return $this->hasOne(Item_images::class, 'item_id', 'id')->select(array('item_id', 'url'));
    }

    public function items_image()
    {
        return $this->hasMany(Item_images::class, 'item_id', 'id')->select(array('id as image_id', 'item_id', 'url', 'label'));
    }

    public function Rating()
    {
        return $this->hasMany(Rating::class, 'item_id', 'id')->select(DB::raw('avg(rating) avg'));
    }

    public function items_like()
    {
        return $this->hasMany(like::class, 'item_id', 'id')->select(array('item_id', 'id', 'user_id'));
    }

    public function Likes()
    {
        return $this->hasOne(like::class, 'item_id', 'id')->select(array('item_id', 'id', 'user_id'));
    }

    public function category()
    {
        return $this->belongsTo(Categories::class)->select(array('id', 'name', 'parent_id'));
    }

    public static function getSoldItemsDetails()
    {
        return self::with(['category', 'user', 'items_image'])->where('is_sold', 1)->orderBy('created_at', 'desc')->get();
    }

    public static function getItemDetail($item_id)
    {
        return self::join('users', 'users.id', 'user_id')->where('items.id', $item_id)->get(['items.id as item_id', 'items.name', 'users.first_name', 'users.first_name'])->first();
    }

    public static function getDashboardItems($date)
    {
        return self::with(['category', 'user', 'items_image'])->where('created_at', '>=', $date)->orderBy('created_at', 'desc')->get();
    }

    public static function getUnpurchasedItems($offset)
    {
        return self::with(['category', 'user', 'items_image'])->where('is_sold', 0)->limit(20)->offset($offset)->orderBy('created_at', 'desc')->get();
    }
}

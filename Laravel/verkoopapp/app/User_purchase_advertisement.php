<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User_purchase_advertisement extends Model
{
    public $table = 'user_purchase_advertisement';

    public static function getAllBanners()
    {
        $data = self::join('advertisement_plans', 'advertisement_plan_id', 'advertisement_plans.id')->orderBy('user_purchase_advertisement.created_at', 'desc')->get(['advertisement_plans.name as plan', 'user_purchase_advertisement.*']);

        return $data;
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payments extends Model
{
    public static function getAllPaymentTransactions()
    {
        $data = self::join('users', 'users.id', 'payments.user_id')
                ->orderby('payments.created_at', 'desc')
                ->get(['payments.id as tnx_id', 'payments.*', 'users.*']);

        return $data;
    }
}

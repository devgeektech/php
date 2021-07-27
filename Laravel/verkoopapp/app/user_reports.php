<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class user_reports extends Model
{
    public static function getAllDisputeReport()
    {
        $data = self::join('reports', 'reports.id', 'report_id')->orderBy('user_reports.created_at', 'desc');
        $data->join('users', 'users.id', 'user_id');

        return $data->get(['user_reports.*', 'user_reports.id as report_id', 'user_reports.created_at as reported_on', 'reports.*', 'users.first_name as reported_by', 'users.email as reported_by_email']);
    }
}

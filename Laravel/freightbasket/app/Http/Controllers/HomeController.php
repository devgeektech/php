<?php

namespace App\Http\Controllers;
use Mail;
use Illuminate\Http\Request;
use App\News;
use App\Freight;
use App\VesselSchedule;
use App\Category;


class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        /*Mail::send('emails.test', ['user' => "Jass"], function ($message) {
            $message->from('us@example.com', 'Laravel');

            $message->to('jaskaran.geektech@gmail.com')->cc('bar@example.com');
        });
        dd("Jass");*/
        $topnewslist = News::latest()->whereHas('category')->where('status',1)->take(5)->get();
        $topfreightlist = Freight::latest()->where('status',1)->where('freightvalidity','>=', date('m/d/Y'))->take(5)->get();
        $vesselSchedule = VesselSchedule::latest()->where('est_arrival_date','>=', date('m/d/Y'))->take(5)->get();
        return view('home', compact(
                'topnewslist',
                'topfreightlist',
                'vesselSchedule'
            )
        );
    }
}

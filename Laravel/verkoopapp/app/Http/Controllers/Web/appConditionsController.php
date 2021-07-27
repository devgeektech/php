<?php

namespace App\Http\Controllers;

class appConditionsController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function termsOfConditions()
    {
        return view('app.terms-of-condition');
    }

    public function privacyPolicy()
    {
        return view('app.privacy-policy');
    }

    public function helpCenter()
    {
        return view('app.help-center');
    }

    public function contactUs()
    {
        return view('app.contact-us');
    }

    public function about()
    {
        return view('app.about');
    }

    public function privacySettings()
    {
        return view('app.data-and-privacy-settings');
    }
}

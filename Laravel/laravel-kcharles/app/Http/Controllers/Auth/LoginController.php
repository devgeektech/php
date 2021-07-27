<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Mail;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('guest')->except('logout');
    }

	public function send_quote_email(){
		$from_zip = $_POST['from_zip'];
		$to_zip = $_POST['to_zip'];
		$custom_name = $_POST['custom_name'];
		$custom_email = $_POST['custom_email'];
		$custom_phone = $_POST['custom_phone'];
<<<<<<< HEAD
		$custom_notes = $_POST['custom_notes'];
		
		Mail::send('emails.send_quote_to_admin', ['custom_name' => $custom_name,'custom_email' => $custom_email,'custom_phone' => $custom_phone,'custom_notes' => $custom_notes,'from_zip' => $from_zip,'to_zip' => $to_zip], function ($message) {
=======

		Mail::send('emails.send_quote_to_admin', ['custom_name' => $custom_name,'custom_email' => $custom_email,'custom_phone' => $custom_phone,'from_zip' => $from_zip,'to_zip' => $to_zip], function ($message) {
>>>>>>> da08093ea53f7bf6c2cc0b5e919f24d1729e7faf
			$message->subject('New Quote Received');
			$admin_email = env('ADMIN_EMAIL');
			$message->to($admin_email);
			// $message->to('noreply@kcharles.co.uk');
		});
		Mail::send('emails.send_quote_automatic_reply', [], function ($message) use($custom_email) {
			$message->subject('Confirmation Email');
			$message->to($custom_email);
			// $message->to('ksingh@getyoursolutions.com');
			// $message->to('noreply@kcharles.co.uk');
		});

		// if( count(Mail::failures()) > 0 ) {

		   // echo "There was one or more failures. They were: <br />";

		   // foreach(Mail::failures() as $email_address) {
			   // echo " - $email_address <br />";
			// }

		// } else {
			// echo "No errors, all sent successfully!";
		// }
		// die;

		// if(mail( $admin_email, 'New Quote Received', $message, $headers )){
			// mail( $custom_email, 'Confirmation Email', $message2, $headers );
			$json['success'] = 'yes';
			$json['msg'] = '<h2>Thanks for submitting your details.</h2> <br> We have received your response. One of our representatives will contact you shortly.';
			echo json_encode($json);
			exit();
		// }else{
			// $json['success'] = 'no';
			// $json['msg'] = 'Email not send, please try again.';
			// echo json_encode($json);
			// exit();
		// }
    }

}

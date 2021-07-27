<?php

/**

 * Created by PhpStorm.

 * User: mobilecoderz

 * Date: 4/10/18

 * Time: 10:25 AM

 */



return [



    /*

    |--------------------------------------------------------------------------

    | Authentication Language Lines

    |--------------------------------------------------------------------------

    |

    | The following language lines are used during authentication for various

    | messages that we need to display to the user. You are free to modify

    | these language lines according to your application's requirements.

    |

    */

    'success_message' => 'Success',



    'userNotActive' => 'Sorry! your account is temporary disabled',



    'notExists' => 'Sorry ! Data doesn\'t exists',



    'token' => [



        'invalid' => 'You are not authorized to access, you may have invalid token.',



        'expired' => 'Your session has expired! You need to login again.',



    ],



    'login' => [



        'success' => 'Login successful',

        'invalid' => 'Invalid username and password combination',

        'invalidMatch' => [

          'username' => 'User name not exists',

          'password' => 'Password didn\'t match'

        ],

        'error' => 'Oops! Something went worng'

    ],



    'passwordReset' => [



        'invalid' => 'This password reset token is invalid.',



        'notFound' => 'We can\'t find a user with that e-mail address.',



        'success' =>  'Password updated successfully',

    ],



    'user' => [

        'registration' => [

            'successful' => 'User :userName Registration was Successful',

        ],

        'forgetPassword' => 'A link has been sent to your email :email.',



        'setting' => [

          'updated' => 'User settings updated successfully',

        ],



        'updated' => 'User updated successfully',



        // Invalid token

        'unAuthorized' => 'You seems to be not authorized. You may have invalid token.',



        ''

    ],



    'bike' => [



        'details' => [

            'added' => 'Bike details added successfully',



            'updated' => 'Bike details updated successfully',



            'deleted' => 'Bike details deleted  successfully',

        ],

        'setup' => [

            'added' => 'Bike setup added successfully',



            'updated' => 'Bike setup updated successfully',



            'deleted' => 'Bike setup deleted successfully',

        ],
        'attachment' => [
            'deleted' => 'Bike attachment deleted successfully'
        ]

    ]





];
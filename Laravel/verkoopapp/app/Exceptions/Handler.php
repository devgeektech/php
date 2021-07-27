<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        Log::info($exception->getMessage(), [
            'url' => Request::url(),
            'input' => Request::all()
        ]);
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        //dd($exception->getPrevious());
        if ($exception instanceof \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException) {
            
            switch (get_class($exception->getPrevious())) {
                case \Tymon\JWTAuth\Exceptions\TokenExpiredException::class:
                    $result = [
                        'status' => $exception->getStatusCode(),
                        'message' =>  __('ApiLang.token.expired'),
                        'data' => null
                    ];

                    return response()->json($result, 200);
                case \Tymon\JWTAuth\Exceptions\TokenInvalidException::class:
                case \Tymon\JWTAuth\Exceptions\TokenBlacklistedException::class:
                $result = [
                    'status' => $exception->getStatusCode(),
                    'message' =>  __('ApiLang.token.invalid'),
                    'data' => null
                ];

                return response()->json($result, 200);

                default:
                    break;
            }
        }
        if ($exception instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException) {
            
            $result = [
                        'status' => $exception->getStatusCode(),
                        'message' =>  'Sorry You are using wrong method',
                        'data' => null
                    ];
                    return response()->json($result, 200);
        }

        return parent::render($request, $exception);
    }
}

<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler {
    protected $dontReport = [
        //
    ];

    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    public function report(Throwable $exception) {
        parent::report($exception);
    }

    public function render($request, Throwable $exception) {

        if ($this->isHttpException($exception)) {
            $code = $exception->getStatusCode();

            $isFrontend = $request->is('customer/*') || $request->is('/') || $request->is('shop/*');

            $viewPath = $isFrontend ? 'frontend.error.' : 'error.';

            if ($code == 404) {
                return response()->view($viewPath . '404', [], 404);
            }

            if ($code == 500) {
                return response()->view($viewPath . '500', [], 500);
            }

        }

        return parent::render($request, $exception);
    }

}

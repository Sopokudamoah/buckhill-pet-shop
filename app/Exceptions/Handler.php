<?php

namespace App\Exceptions;

use App\Http\Resources\V1\BaseApiResource;
use Firebase\JWT\ExpiredException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Psr\Log\LogLevel;
use Spatie\QueryBuilder\Exceptions\InvalidFilterQuery;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        if ($request->expectsJson()) {
            if ($e instanceof ValidationException) {
                return (new BaseApiResource())->errors($e->validator->getMessageBag()->toArray())
                    ->message($e->getMessage())
                    ->success(0)
                    ->response()
                    ->setStatusCode($e->status);
            }

            if ($e instanceof InvalidFilterQuery) {
                return (new BaseApiResource())
                    ->errors([$e->getMessage()])
                    ->message($e->getMessage())
                    ->success(0)
                    ->response()
                    ->setStatusCode($e->getStatusCode());
            }

            if ($e instanceof AuthorizationException) {
                return (new BaseApiResource())
                    ->errors([$e->getMessage()])
                    ->message($e->getMessage())
                    ->success(0)
                    ->response()
                    ->setStatusCode(403);
            }

            if ($e instanceof ModelNotFoundException) {
                $message = "No results found for requested query";
                return (new BaseApiResource())
                    ->errors([$message])
                    ->message($message)
                    ->success(0)
                    ->response()
                    ->setStatusCode(404);
            }

            if ($e instanceof ExpiredException) {
                $message = $e->getMessage();
                return (new BaseApiResource())
                    ->errors([$message])
                    ->message($message)
                    ->success(0)
                    ->response()
                    ->setStatusCode(400);
            }
        }

        return parent::render($request, $e);
    }
}

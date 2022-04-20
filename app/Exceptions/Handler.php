<?php

namespace App\Exceptions;

use App\Traits\ApiResponse;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException as ModelNotFoundException;
use Illuminate\Auth\AuthenticationException as AuthenticationException;
use Illuminate\Validation\ValidationException as ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException as MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException as NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException as HttpException;
use Illuminate\Database\QueryException as QueryException;

use Illuminate\Auth\Access\AuthorizationException as AuthorizationException;

use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponse;
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
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
     * A list of the inputs that are never flashed for validation exceptions.
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

    public function render($request, Throwable $exception)
    {

        if ($exception instanceof ValidationException) {
            return $this->convertValidationExceptionToResponse($exception, $request);
        }

        if ($exception instanceof AuthorizationException) {
            return $this->respondError(message: __('messages.unauthorized'), error_code: 403, status_code: 403);
        }

        if ($exception instanceof AuthenticationException) {
            return $this->respondError(message: __('messages.forbidden'), error_code: 403, status_code: 403);
        }

        if ($exception instanceof ModelNotFoundException) {
            $modelName = strtolower(class_basename($exception->getModel()));
            return $this->respondError(message: __('messages.modelNotFound', ['modelName' => $modelName]),  status_code: 405, error_code: 405);
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            return $this->respondError(message: __('messages.methodNotAllowed'), status_code: 405, error_code: 405);
        }

        if ($exception instanceof NotFoundHttpException) {
            return $this->respondError(message: __('messages.urlNotFound'), status_code: 404, error_code: 404);
        }

        if ($exception instanceof HttpException) {
            return $this->respondError(message: __('messages.httpException', ['message' => $exception->getMessage()]), status_code: $exception->getStatusCode(), exception: $exception);
        }
        if ($exception instanceof QueryException) {

            $errorCode = $exception->errorInfo[0] ?? null;
            $errorMessage =  $exception->errorInfo[2] ?? null;
            if ($errorCode == 1451) {
                return $this->respondError(message: __('messages.cannotRemoveResource'), status_code: 409, error_code: $errorCode);
            }
            if ($errorCode == 1364) {
                return $this->respondError(message: __('messages.cannotRemoveResource'), status_code: 409, error_code: $errorCode);
            }
            if ($errorCode == 23505) {
                return $this->respondError(message: __('messages.duplicate'), status_code: 409, error_code: $errorCode);
            }
            if ($errorCode == '22P02') {
                return $this->respondError(message: __('messages.invalidUuidSyntax'), status_code: 409, error_code: $errorCode);
            }
            if ($errorCode == 23514) {
                return $this->respondError(message: $errorMessage, status_code: 409, error_code: $errorCode);
            }
            if (config('app.debug')) {
                return $this->respondError(message: $exception->getMessage(), error_code: $errorCode, status_code: 409, exception: $exception);
            } else {
                return $this->respondError(message: __('messages.unExpectedException'), status_code: 500, exception: $exception);
            }
        }
        if ($exception instanceof Exception) {
            return $this->respondError(message: $exception->getMessage(), error_code: 409);
        }

        if (config('app.debug')) {
            return parent::render($request, $exception);
        }

        return $this->respondError(message: __('messages.unExpectedException'), status_code: 500, exception: $exception);
    }

    protected function convertValidationExceptionToResponse(ValidationException $validationException, $request)
    {
        $errors = $validationException->validator->errors()->getMessages();
        if (!$this->isFrontend($request)) {
            return $this->respondValidationErrors($validationException, 422);
        } else {
            return redirect()->back()->withInput()->withErrors($errors);
        }

        //return $this->errorResponse($errors,422);
    }
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if (!$this->isFrontend($request)) {
            return $this->respondError(message: __('messages.unauthorized'), status_code: 401);
        }
        return redirect()->guest('login');
    }
    private function isFrontend($request)
    {
        return $request->acceptsHtml() && collect($request->route()->middleware())->contains('web');
    }
}

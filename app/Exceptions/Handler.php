<?php

namespace App\Exceptions;

use App\Traits\ApiResponse;
use ArgumentCountError;
use BadMethodCallException;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Support\Str;
use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{

    use ApiResponse;

    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
     */
    public function register(): void
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

        if ($exception instanceof ModelNotFoundException) {
            $model = strtolower((class_basename($exception->getModel())));
            return $this->errorResponse("No existe ninguna instancia de {$model} con el id especificado.", 404);
        }

        if ($exception instanceof AuthenticationException) {
            return $this->unauthenticated($request, $exception);
        }

        // if ($exception instanceof AuthorizationException) {
        //     return $this->errorResponse("No posee los privilegios necesarios para acceder a este sitio.", 403);
        // }

        if ($exception instanceof NotFoundHttpException) {
            return $this->errorResponse("No se encontró la URL especificada.", 404);
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            return $this->errorResponse("El método especificado en la petición no es valido.", 405);
        }

        // if ($exception instanceof BadMethodCallException) {
        //     $errorMessage = $exception->getMessage();

        //     return $this->errorResponse("Error en la llamada a un método: $errorMessage", 500);
        // }

        if ($exception instanceof HttpException) {
            return $this->errorResponse($exception->getMessage(), $exception->getStatusCode());
        }
        if ($exception instanceof QueryException) {
            $code = $exception->errorInfo[1];
            if ($code == 1451) {
                return $this->errorResponse('No se puede eliminar el recurso porque está relacionado con otro.', 409);
            }

            if ($code == 1146) {
                $errorInfo = $exception->errorInfo[2];
                $parts = explode("'", $errorInfo);

                if (count($parts) >= 2) {
                    $table = $parts[1];
                    return $this->errorResponse("No existe la tabla '$table'", 404);
                }
            }

            if ($code == 1062) {
                return $this->errorResponse('El recurso ya existe.', 409);
            }

            if ($code == 1048) {
                $errorMessage = $exception->getMessage();
                $fieldName = $this->extractFieldNameFromErrorMessage($errorMessage);
                $fieldName = Str::snake($fieldName);
                return $this->errorResponse("El campo '$fieldName' es requerido.", 422);
            }

            if ($code == 1066) {
                return $this->errorResponse('El campo especificado no existe.', 422);
            }

            if ($code == 1452) {
                $errorMessage = $exception->getMessage();

                if (preg_match("/`([^`]+)`\.\`([^`]+)`/", $errorMessage, $matches)) {
                    $databaseName = $matches[1];
                    $tableName = $matches[2];
                } else {
                    $databaseName = "desconocida";
                    $tableName = "desconocida";
                }

                $foreignKeyId = null;
                if (preg_match("/values\s*\(([^)]+)\)/i", $errorMessage, $valuesMatches)) {
                    $values = explode(',', $valuesMatches[1]);
                    $foreignKeyId = trim($values[1] ?? '');
                }

                $foreignKeyIdPart = $foreignKeyId ? " con el ID '$foreignKeyId'" : "";
                $customMessage = "Error de integridad de datos: No se puede añadir o actualizar un registro$foreignKeyIdPart en la tabla '$tableName' de la base de datos '$databaseName' debido a una restricción de clave foránea.";

                return $this->errorResponse($customMessage, 409);
            }
        }

        // if ($exception instanceof ArgumentCountError) {
        //     return $this->errorResponse('Error en la llamada a un método: El número de argumentos es incorrecto.', 500);
        // }

        if ($exception instanceof TokenMismatchException) {
            return redirect()->back()->withInput($request->input());
        }

        if (config('app.debug')) {
            return parent::render($request, $exception);
        }

        return $this->errorResponse('Falla inesperada.Inténtelo más tarde', 500);
    }

    protected function extractFieldNameFromErrorMessage($errorMessage)
    {
        $pattern = "/Column '(.*?)'/";
        preg_match($pattern, $errorMessage, $matches);

        if (isset($matches[1])) {
            return $matches[1];
        }

        return 'Campo desconocido';
    }

    public function handleException($request, Exception $exception)
    {

        if (config('app.debug')) {
            return parent::render($request, $exception);
        }
        return $this->errorResponse('Falla inesperada.Inténtelo más tarde', 500);
    }

    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {

        $errors = $e->validator->errors()->getMessages();

        if ($this->isFrontend($request)) {
            return $request->ajax() ? response()->json($errors, 422) : redirect()
                ->back()
                ->withInput($request->input())
                ->withErrors($errors);
        }

        return $this->errorResponse($errors, 422);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($this->isFrontend($request)) {
            return redirect()->guest('login');
        }
        return $this->errorResponse('No autenticado', 401);
    }

    private function isFrontend($request)
    {
        return $request->acceptsHtml() && collect($request->route()->middleware())->contains('web');
    }
}

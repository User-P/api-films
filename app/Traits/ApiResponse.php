<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

trait ApiResponse
{
    protected function successResponse($data, $resource = 'data', $code = 200)
    {
        return response()->json(['success' => true, $resource => $data], $code);
    }

    protected function errorResponse($message, $code): JsonResponse
    {
        return response()->json(['success' => false, 'error' => $message, 'code' => $code], $code);
    }

    protected function showCollection(Collection $collection, $resource = 'data', $code = 200): JsonResponse
    {

        if ($collection->isEmpty()) {
            return $this->successResponse($collection, $resource, $code);
        }

        return $this->successResponse($collection, $resource, $code);
    }

    protected function showInstance(Model $instance, $resource = 'data', $code = 200): JsonResponse
    {
        return $this->successResponse($instance, $resource, $code);
    }
}

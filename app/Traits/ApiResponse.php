<?php

namespace App\Traits;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Validation\ValidationException;

trait ApiResponse
{


    /**
     * @param JsonResource $resource
     * @param null $message
     * @param int $statusCode
     * @param array $headers
     * @return JsonResponse
     */
    protected function respondWithResource(JsonResource $resource, $message = null, $statusCode = 200, $headers = [])
    {

        return $this->apiResponse(
            $resource->additional([
                'status' => 'ok',
                'message' => !empty($message) ? $message :  __('messages.fetchedRecords', [
                    'total' =>  1
                ])
            ])->response()->getData(),
            $statusCode,
            $headers
        );
    }


    /**
     * Return generic json response with the given data.
     *
     * @param       $data
     * @param int $statusCode
     * @param array $headers
     *
     * @return JsonResponse
     */
    protected function apiResponse($data = [], $statusCode = 200, $headers = [])
    {
        return response()->json(
            $data,
            $statusCode,
            $headers
        );
    }


    /**
     * @param ResourceCollection $resourceCollection
     * @param null $message
     * @param int $statusCode
     * @param array $headers
     * @return JsonResponse
     */
    protected function respondWithResourceCollection(ResourceCollection $resourceCollection, $message = null, $statusCode = 200, $headers = [])
    {
        $total = $resourceCollection->count();
        return $this->apiResponse(
            $resourceCollection->additional([
                'status' => 'ok',
                'message' => $message ? $message : (($total > 0) ? __('messages.fetchedRecords', ['total' => $total]) : __('messages.noRecords'))
            ])->response()->getData(),
            $statusCode,
            $headers
        );
    }

    /**
     * Respond with success.
     *
     * @param string $message
     *
     * @return JsonResponse
     */
    protected function respondSuccess($message = '')
    {
        return $this->apiResponse([
            'success' => true,
            'message' => $message
        ]);
    }

    /**
     * Respond with created.
     *
     * @param $data
     *
     * @return JsonResponse
     */
    protected function respondCreated($data)
    {
        if ($data instanceof JsonResource) {
            $data = $data->additional([
                'status'    => 'ok',
                'message'   => __('messages.created')
            ])->response()->getData();
        } else {
            $data = [
                'status'    => 'ok',
                'message'   => __('messages.created'),
                'data'      => $data
            ];
        }
        return $this->apiResponse($data, 201);
    }

    /**
     * Respond with created.
     *
     * @param $data
     *
     * @return JsonResponse
     */
    protected function respondUpdated($data)
    {
        if ($data instanceof JsonResource) {
            $data = $data->additional([
                'status'    => 'ok',
                'message'   => __('messages.updated')
            ])->response()->getData();
        } else {
            $data = [
                'status'    => 'ok',
                'message'   => __('messages.updated'),
                'data'      => $data
            ];
        }
        return $this->apiResponse($data, $this->HTTP_ACCEPTED);
    }



    /**
     * Respond with error.
     *
     * @param $message
     * @param int $statusCode
     *
     * @param Exception|null $exception
     * @param bool|null $error_code
     * @return JsonResponse
     */
    protected function respondError($message, int $status_code = 400, Exception $exception = null, $error_code = 1)
    {
        return $this->apiResponse(
            [
                'success'       => false,
                'message'       => $message ?? 'There was an internal error, Pls try again later',
                'exception'     => $exception,
                'error_code'    => $error_code
            ],
            $status_code
        );
    }

    protected function respondValidationErrors(ValidationException $exception)
    {
        return $this->apiResponse(
            [
                'success'   => false,
                'message'   => $exception->getMessage(),
                'errors'    => $exception->errors()
            ],
            422
        );
    }
    /**
     * Return generic json response with the given data.
     *
     * @param       $data
     * @param int $statusCode
     * @param array $headers
     *
     * @return JsonResponse
     */
    protected function respondWithCustomData($data = [], $statusCode = 200, $headers = [])
    {
        return $this->apiResponse(
            [
                'status'    => 'ok',
                'message'   => __('messages.fetched'),
                'data'      => $data
            ],
            $statusCode,
            $headers
        );
    }
}

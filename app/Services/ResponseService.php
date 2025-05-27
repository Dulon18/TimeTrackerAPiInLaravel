<?php

namespace App\Services;

class ResponseService
{
    public function successResponse($result, $message)
    {
    	$response = [
            'success' => 'success',
            'message' => $message,
            'data'    => $result,
        ];
        return response()->json($response, 200);
    }
    public function validationError($errors, $message = 'Validation failed', $code = 422)
    {
        return response()->json([
            'status'  => 'error',
            'message' => $message,
            'errors'  => $errors,
        ], $code);
    }

    public function errorResponse( $errorMessages,$error, $code = 404)
    {
    	$response = [
            'success' => 'failed',
            'message' => $errorMessages ?? '',
            'data' => $error ?? (object)[],
        ];
        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }
        return response()->json($response, $code);
    }
}

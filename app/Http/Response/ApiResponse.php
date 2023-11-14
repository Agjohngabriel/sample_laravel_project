<?php

namespace App\Http\Response;

class ApiResponse
{
    public static function format($statusCode, $message, $data = null): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'statusCode' => $statusCode,
            'message' => $message,
            'data' => $data,
        ]);
    }
}

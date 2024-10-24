<?php

namespace App\Helper;

class ResponseHelper
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * function : common function to dispaly success - json response
     * @param string $status
     * @param string $message
     * @param string $data
     * @param string $statusCode
     * @return response
     */

    public static function success($status = 'sucess', $message = null, $data = [], $statusCode = 200){
 
         return response()->json([

            'status' => $status,
            'message' => $message,
            'data' => $data,
         ], $statusCode);
    }


     /**
     * function : common function to dispaly error - json response
     * @param string $status
     * @param string $message
     * @param string $statusCode
     * @return response
     */

    public static function error($status = 'error', $message = null, $statusCode = 400){
        return response()->json([

            'status' => $status,
            'message' => $message,
         ], $statusCode); 
    } 
}

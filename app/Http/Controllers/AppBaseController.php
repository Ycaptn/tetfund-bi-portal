<?php

namespace App\Http\Controllers;


use Response;
use App\Models\Setting;
use Illuminate\Support\Facades\Schema;

class AppBaseController extends Controller
{

    public function sendResponse($result, $message)
    {
        return Response::json(self::makeResponse($message, $result));
    }

    public function sendError($error, $code = 404)
    {
        return Response::json(self::makeError($error), $code);
    }

    public function sendSuccess($message)
    {
        return Response::json([
            'success' => true,
            'message' => $message
        ], 200);
    }


        /**
     * @param string $message
     * @param mixed  $data
     *
     * @return array
     */
    public static function makeResponse($message, $data)
    {
        return [
            'success' => true,
            'data'    => $data,
            'message' => $message,
        ];
    }

    /**
     * @param string $message
     * @param array  $data
     *
     * @return array
     */
    public static function makeError($message, array $data = [])
    {
        $res = [
            'success' => false,
            'message' => $message,
        ];

        if (!empty($data)) {
            $res['data'] = $data;
        }

        return $res;
    }
}

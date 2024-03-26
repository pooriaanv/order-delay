<?php


function success($data = null, string $message = '', int $code = 200): \Illuminate\Http\JsonResponse
{
    $res = [
        'code'    => $code,
        'message' => $message
    ];
    if ($data) {
        $res['data'] = $data;
    }

    return response()->json($res, $code);
}

function fail(string $message = '', string $error = '', int $code = 500): \Illuminate\Http\JsonResponse
{
    $res = [
        'code'    => $code,
        'message' => $message,
        'error'   => $error
    ];

    return response()->json($res, $code);
}

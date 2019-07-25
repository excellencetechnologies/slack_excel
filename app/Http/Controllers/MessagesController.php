<?php

namespace App\Http\Controllers;

use Exception;
use App\Messages;
use Illuminate\Http\Request;

class MessagesController extends Controller
{
    // send message
    public function sendMessage()
    {
        try {
            $params = request()->all();
            $message = new Messages();
            $data = $message->sendMessage($params);
            $response = [ 'error' => 0, 'data' => $data ];

        } catch( Exception $ex ) {
            $response = [ 'error' => 1, 'message' => $ex->getMessage() ];
        }

        return response()->json($response);
    }
}

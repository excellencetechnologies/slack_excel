<?php

namespace App\Http\Controllers;

use Exception;
use App\Messages;
use Illuminate\Http\Request;

class MessagesController extends Controller
{
    // add or update message
    public function addMessage()
    {
        try {
            $params = request()->all();
            $message = new Messages();
            $data = $message->addMessage($params);
            $response = [ 'error' => 0, 'data' => $data ];

        } catch( Exception $ex ) {
            $response = [ 'error' => 1, 'message' => $ex->getMessage() ];
        }

        return response()->json($response);
    }

    // all messages
    public function allMessages()
    {
        try {
            $params = request()->all();
            $message = new Messages();
            $data = $message->allMessages($params);
            $response = [ 'error' => 0, 'data' => $data ];

        } catch( Exception $ex ) {
            $response = [ 'error' => 1, 'message' => $ex->getMessage() ];
        }

        return response()->json($response);
    }

    // delete message
    public function deleteMessage()
    {
        try {
            $id = request()->id;
            $message = new Messages();
            $data = $message->deleteMessage($id);
            $response = [ 'error' => 0, 'data' => $data ];

        } catch( Exception $ex ) {
            $response = [ 'error' => 1, 'message' => $ex->getMessage() ];
        }

        return response()->json($response);
    }

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

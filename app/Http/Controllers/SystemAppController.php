<?php

namespace App\Http\Controllers;

use Exception;
use App\SystemApp;
use Illuminate\Http\Request;

class SystemAppController extends Controller
{
    // add system app
    public function addSystemApp()
    {
        try {
            $params = request()->all();
            $app = new SystemApp();
            $data = $app->addSystemApp($params);
            $response = [ 'error' => 0, 'data' => $data ];

        } catch( Exception $ex ) {
            $response = [ 'error' => 1, 'message' => $ex->getMessage() ];
        }

        return response()->json($response);
    }
}

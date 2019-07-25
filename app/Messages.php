<?php

namespace App;

use Exception;
use App\SystemApp;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Exception\GuzzleException;

class Messages extends Model
{
    protected $table = 'messages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['system_id', 'identifier', 'text', 'attachments'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at'];

    // send message
    public function sendMessage($params)
    {
        $validator = Validator::make($params, [
            'app_name' => 'required',
            'identifier' => 'required',
            'channel' => 'required',
            'variables' => 'required|array'
        ]);

        if( $validator->fails() ){
            throw new Exception( $validator->errors()->first() );
        }

        $app = SystemApp::where('app_name', $params['app_name'])->get();
        if( $app->count() < 1 ){
            throw new Exception($params['app_name'] . " not found");
        }

        $message = Messages::where('system_id', $app->first()->id)->where('identifier', $params['identifier'])->get();
        if( $message->count() < 1 ){
            throw new Exception("No message found by identifier " . $params['identifier']);
        }

        $raw_text = explode(" ", $message->first()->text);

        // check for all needed variables and generating the text.
        foreach( $raw_text as $index => $variable ){
            if( strpos($variable, '#') !== false ){
                $key = str_replace('#', '', $variable);
                if( !array_key_exists($key, $params['variables']) ){
                    throw new Exception( $key . " is a required variable." );                                        
                } else {
                    if( !$params['variables'][$key] ){
                        throw new Exception( $key . " is a required variable." );
                    }
                    $raw_text[$index] = $params['variables'][$key];
                }  
            }
        }

        $text = implode(" ", $raw_text);

        $payload = [];
        $payload['token'] = env('SLACK_TOKEN');
        $payload['text'] = $text;
        $payload['channel'] = $params['channel'];

        $client = new Client();
        $response = $client->request('POST', env('SLACK_POST_MESSAGE_URL'), [
            'form_params' => $payload
        ]);
        
        return json_decode($response->getBody()->getContents(), true);
    }
}

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


    // add or update message
    public function addMessage($params)
    {
        $validator = Validator::make($params, [
            'app_name' => 'required',
            'identifier' => 'required',
            'text' => 'required'
        ]);

        if( $validator->fails() ){
            throw new Exception( $validator->errors()->first() );
        }

        $system = SystemApp::where('app_name', $params['app_name'])->get();
        if( $system->count() < 1 ){
            throw new Exception("System not found");
        }

        if( array_key_exists('attachments', $params) ){
            $params['attachments'] = json_encode($params['attachments']);
        }
        
        $message = Messages::updateOrCreate([
                'system_id' => $system->first()->id,
                'identifier' => $params['identifier'],
            ], 
            $params
        )->toArray();

        return $message;
    }

    // all messages
    public function allMessages($params)
    {
        $validator = Validator::make($params, [
            'app_name' => 'required'
        ]);

        if( $validator->fails() ){
            throw new Exception( $validator->errors()->first() );
        }

        $system = SystemApp::where('app_name', $params['app_name'])->get();
        if( $system->count() < 1 ){
            throw new Exception("System not found");
        }

        $message = Messages::where('system_id', $system->first()->id)->get()->toArray();
        return $message;
    }

    // delete message
    public function deleteMessage($id)
    {
        $message = Messages::find($id);
        if( !$message ){
            throw new Exception("Message not found");

        } else {
            $message->delete();
        }

        return $message;
    }

    // send message
    public function sendMessage($params)
    {
        $validator = Validator::make($params, [
            'text' => 'required',
            'channel' => 'required'
        ]);

        if( $validator->fails() ){
            throw new Exception( $validator->errors()->first() );
        }

        $url = env('SLACK_POST_MESSAGE_URL');

        $params['token'] = env('SLACK_TOKEN');
        if( array_key_exists('attachments', $params) ){
            $params['attachments'] = json_encode($params['attachments']);
        }
        $client = new Client();
        $response = $client->request('POST', $url, [
            'form_params' => $params
        ]);
        
        return json_decode($response->getBody()->getContents(), true);
    }
}

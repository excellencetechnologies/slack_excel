<?php

namespace App;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class SystemApp extends Model
{
    protected $table = 'system_apps';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['app_name'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at'];

    // add system app
    public function addSystemApp($params)
    {
        $validator = Validator::make($params, [
            'app_name' => 'required'
        ]);

        if( $validator->fails() ){
            throw new Exception( $validator->errors()->first() );
        }

        $app = SystemApp::updateOrCreate( ['app_name' => $params['app_name']], $params )->toArray();

        return $app;
    }
}

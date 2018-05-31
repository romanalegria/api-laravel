<?php

namespace App\Http\Controllers;

class ControllerResponses {

	public static $ok = 200;
    public static $created = 201;
    public static $notFound = 404;
    public static $unprocesableEntity = 422;
    public static $internalServerError = 500;
    public static $badRequest = 400;
    public static $okStatus = 'ok';
    public static $failStatus = 'error';
	
    public static function okResp($data)
    {
        return (object)
        [
            'status'    =>  ControllerResponses::$okStatus,
            'data'  =>  $data,
            'code' => ControllerResponses::$ok
        ];
    }
    public static function createdResp($data)
    {
        return (object)
        [
            'status'    =>  'ok',
            'data'  =>  $data,
            'code' => ControllerResponses::$created
        ];
    }
	
    public static function notFoundResp()
    {
        return (object)
        [
            'status'    =>  ControllerResponses::$failStatus,
            'data'  =>  [],
            'message' => 'Not found',
            'code' => ControllerResponses::$notFound
        ];
    }
    public static function nadRequestResp()
    {
        return (object)
        [
            'status'    =>  ControllerResponses::$failStatus,
            'data' => [],
            'message' => 'Bad Request',
            'code' => ControllerResponses::$badRequest
        ];
    }

    public static function unprocesableResp($message = '')
    {
        $messageText = '';
        if(is_array($message)){
            foreach ($message as $m){ $messageText .= $m . ' .'; }
        }else $messageText = $message;
        return (object)
        [
            'status'    =>  ControllerResponses::$failStatus,
            'data'  =>  [],
            'message' => (trim($messageText)=='')?'Error processing information':$messageText,
            'code' => ControllerResponses::$unprocesableEntity
        ];
    }
}
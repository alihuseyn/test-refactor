<?php

namespace App\Http\Controllers;

use Exception;
use Laravel\Lumen\Routing\Controller;

class API extends Controller
{

    /**
     * Get email and token information from request and return it as array
     * @param $request
     * @return array
     */
    public static function getAuthInfo($request)
    {
        return [
            'email' => $request->has('email') ? $request->email : null,
            'token' => $request->has('token') ? $request->token : null,
        ];
    }

    /**
     * Get Error code from errors under config according to given code
     * @param $code
     * @return mixed
     * @throws Exception
     */
    public static function getErrorContent($code)
    {
        $error =  config("errors.{$code}");
        if (is_null($error) || empty($error)) {
            throw new Exception("Null error content for: {$code}");
        }
        return $error;
    }

    /**
     * Get Success code from success under config according to given code
     * @param $code
     * @return mixed
     * @throws Exception
     */
    public static function getSuccessContent($code)
    {
        $success =  config("success.{$code}");
        if (is_null($success) || empty($success)) {
            throw new Exception("Null success content for: {$code}");
        }
        return $success;
    }

    /**
     * For post method, get content of body and return it as array
     * @param $request
     * @return array
     */
    public function getBodyParams($request)
    {
        return [
            'user_id' => $request->user->id,
            'value'   => $request->has('value') ? $request->value : null,
            'translation' => $request->has('translation') ? $request->translation : null,
            'from'    => $request->has('from') ? $request->from : config('languages.default_from'),
            'to'      => $request->has('to') ? $request->to : config('languages.default_to'),
        ];
    }

    /**
     * Add given $content to the general response format and return general format
     * - if $errorExist - true => then error format return
     * - else success format return
     * @param $content
     * @param bool $errorExist - default true
     * @return array
     */
    public static function prettyResponse($content, $errorExist = true)
    {
        if($errorExist) {
            return [
                'errors' => $content,
                'version' => 'v1.0',
                'status' => false,
            ];
        }else{
            return [
                'data' => $content,
                'version' => 'v1.0',
                'status' => true,
            ];
        }
    }

    /**
     * Validate given post method body to check whether error exists or not
     * - if none error seen then return true
     * - else return $errors array
     * @param array $params
     * @return array|bool
     */
    protected function validator(array $params)
    {
        $from = config("languages.languages.{$params['from']}");
        $to = config("languages.languages.{$params['to']}");
        $errors = [];
        if(is_null($from) || empty($from)){
            array_push($errors, 'ERR-006');
        }
        if(is_null($to) || empty($to)){
            array_push($errors, 'ERR-007');
        }
        if(!empty($errors)) {
            return $errors;
        }
        return true;
    }

}

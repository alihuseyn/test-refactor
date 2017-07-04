<?php

namespace App\Http\Controllers;

use App\Model\Translate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class TranslateController extends API
{
    /**
     * Add given content to the translation table
     * if the value already exists in there then return message
     * @param Request $request
     * @return Response
     */
    public function add(Request $request)
    {
        $params = $this->getBodyParams($request);
        $validator = $this->validator($params);
        if(is_array($validator)){
            $response = [];
            foreach ($validator as $error){
                array_push($response, API::getErrorContent($error));
            }
            return response(API::prettyResponse($response), 400)
                    ->header('Content-Type', 'application/json ; charset=utf-8');
        }
        $result = Translate::add($params);
        if($result == Translate::TRANSLATED){
            return response(API::prettyResponse(API::getSuccessContent('MSG-001'), false), 200)
                    ->header('Content-Type', 'application/json ; charset=utf-8');
        }else if($result == Translate::NOT_EXIST){
            return response(API::prettyResponse(API::getSuccessContent('MSG-007'), false), 200)
                ->header('Content-Type', 'application/json ; charset=utf-8');
        }
        return response(API::prettyResponse(API::getSuccessContent('MSG-002'), false), 200)
                    ->header('Content-Type', 'application/json ; charset=utf-8');
    }

    /**
     * Validate post method body content to check whether error exists or not
     * @param array $params
     * @return array|bool
     */
    protected function validator(array $params)
    {
        $parent = parent::validator($params);
        if(is_null($params['value']) || empty($params['value'])){
            if(is_array($parent)) {
                array_push($parent, 'ERR-005');
            }else{
                $parent = ['ERR-005'];
            }
        }
        if(is_null($params['translation']) || empty($params['translation'])){
            if(is_array($parent)) {
                array_push($parent, 'ERR-008');
            }else{
                $parent = ['ERR-008'];
            }
        }
        return $parent;
    }
}
<?php

namespace App\Http\Controllers;

use App\Model\Base;
use App\Model\Translate;
use Illuminate\Http\Request;
use App\Model\Request as TranslateRequest;
use Illuminate\Support\Facades\Response;

class ValueController extends API
{

    /**
     * Add given content as request
     * - if the content exists as a request or translation then return error message
     * - else return success message
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
        $result = TranslateRequest::add($params);
        if($result == TranslateRequest::REQUESTED){
            return response(API::prettyResponse(API::getSuccessContent('MSG-003'), false), 200)
                    ->header('Content-Type', 'application/json ; charset=utf-8');
        }
        if($result == TranslateRequest::TRANSLATED){
            return response(API::prettyResponse(API::getSuccessContent('MSG-004'), false), 200)
                    ->header('Content-Type', 'application/json ; charset=utf-8');
        }
        return response(API::prettyResponse(API::getSuccessContent('MSG-005'), false), 200)
                    ->header('Content-Type', 'application/json ; charset=utf-8');
    }

    /**
     * Validate post method content to check whether any error exists or not
     * @param array $params
     * @return array|bool
     */
    public function validator(array $params)
    {
        $parent = parent::validator($params);
        if(is_null($params['value']) || empty($params['value'])){
            if(is_array($parent)) {
                array_push($parent, 'ERR-005');
            }else{
                $parent = ['ERR-005'];
            }
        }
        return $parent;
    }


    /**
     * Return requested value or all the values translated as a list
     * @param null $value
     * @return Response
     */
    public function get($value = null)
    {
        if(!is_null($value) && !empty($value)){
            $result = Translate::fetch(['value' => $value]);
        }else{
            $result = Translate::fetch(null);
        }

        if($result == Base::NOT_EXIST){
            return response(API::prettyResponse(API::getSuccessContent('MSG-006'), false), 200)
                ->header('Content-Type', 'application/json ; charset=utf-8');
        }
        return response(API::prettyResponse($result, false), 200)
            ->header('Content-Type', 'application/json ; charset=utf-8');
    }




}
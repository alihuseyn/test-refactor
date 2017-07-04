<?php

namespace App\Model;

use App\Http\Controllers\API;

class Translate extends Base
{
    // Table name which Model will reference
    public $table = "translations";
    public static $allow_translation_if_request_not_exists = true;
    /**
     * Add given translation content to DB table
     * - if not exists then add and return constant ADDED
     * - else return constant to back
     * @param array $params
     * @return integer
     */
    public static function add(array $params)
    {
        $existence = self::exists($params);
        if(!$existence) {
            $translation = new Translate;
            $translation->value = $params['value'];
            $translation->translation = $params['translation'];
            $translation->from = $params['from'];
            $translation->to = $params['to'];
            $translation->agent_id = $params['user_id'];
            $translation->save();
            Request::setTranslated($params); // update request and set its value as translated
            return self::ADDED;
        } else{
            return $existence;
        }
    }

    /**
     * Check whether content exists in DB table translation
     * @param array $params
     * @return bool|int
     */
    public static function exists(array $params){
        $translation = Translate::where(function ($query) use($params) {
                    $query->where('value', '=', $params['value'])
                        ->where('translation', '=',$params['translation'])
                        ->where('from', '=', $params['from'])
                        ->where('to', '=', $params['to']);
                })->orWhere(function ($query) use($params) {
                    $query->where('translation', '=', $params['value'])
                        ->where('value', '=',$params['translation'])
                        ->where('to', '=', $params['from'])
                        ->where('from', '=', $params['to']);
                })->first();
        $existence = true;
        if(!self::$allow_translation_if_request_not_exists) {
            $existence = self::requestExistence($params);
        }
        if(!is_null($translation) && !empty($translation)){
            return self::TRANSLATED;
        }
        if(!$existence){
            return self::NOT_EXIST;
        }
        return false;
    }

    /**
     * Check existence of item in request table
     * @param array $params
     * @return bool
     */
    public static function requestExistence(array $params){
        $request = Request::where('value', '=', $params['value'])
            ->where('from', '=', $params['from'])
            ->where('to', '=', $params['to'])
            ->where('situation', '=', 0)
            ->first();
        if(!is_null($request) && !empty($request)){
            return true;
        }
        return false;
    }

    /**
     * According to given content try to fetch data from translations
     * @param array|null $params
     * @return array
     */
    public static function fetch(array $params = null)
    {
        $result = [];
        if (!is_null($params) && !empty($params)) {
            // Check both translated content and value to determine all equal item with value
            $translations = Translate::where('value', '=', $params['value'])
                                        ->orWhere('translation', '=', $params['value'])->get();
            $language = "";
            foreach ($translations as $translation){
                if($translation->value == $params['value']){
                    $language = $translation->from;
                }else{
                    $language = $translation->to;
                }

                if(isset($result[$translation->from])){
                    if(!in_array($translation->value,$result[$translation->from])) {
                        array_push($result[$translation->from], $translation->value);
                    }
                }else{
                    $result[$translation->from] = [$translation->value];
                }

                if(isset($result[$translation->to])){
                    if(!in_array($translation->translation,$result[$translation->to])) {
                        array_push($result[$translation->to], $translation->translation);
                    }
                }else{
                    $result[$translation->to] = [$translation->translation];
                }
            }
            if(!empty($result)){
                $result = array_merge([
                    'value' => $params['value'],
                    'language' => $language,
                ],$result);
            }
        }else{
            // In this part all contents are fetched
            // firstly check for from and to values exists or not
            // if one of them exists then append will done
            // else if both exist not any operation required
            // else if non of exists then add operation to result done
            $translations = Translate::all();
            foreach ($translations as $translation){
                $for_from = self::determineSameItemPlace($result, $translation->from, $translation->value);
                $for_to = self::determineSameItemPlace($result, $translation->to, $translation->translation);
                if($for_from != -1 || $for_to != -1){
                    if($for_from != -1 && $for_to == -1){
                        if(isset($result[$for_from][$translation->to])){
                            array_push($result[$for_from][$translation->to], $translation->translation);
                        }else{
                            $result[$for_from][$translation->to] = [$translation->translation];
                        }
                    } else if($for_from == -1 && $for_to != -1){
                        if(isset($result[$for_to][$translation->from])){
                            array_push($result[$for_to][$translation->from], $translation->value);
                        }else{
                            $result[$for_to][$translation->from] = [$translation->value];
                        }
                    }
                } else if($for_from == -1 && $for_to == -1){
                    array_push($result, [
                       $translation->from => [$translation->value],
                       $translation->to => [$translation->translation],
                    ]);
                }
            }
        }

        if(empty($result)){
            $result = API::getSuccessContent('MSG-007');
        }
        return $result;
    }

    /**
     * Determine index of given $key and $value on $result array
     * If not any found then -1 return
     * @param array $result
     * @param $key
     * @param $value
     * @return int
     */
    protected static function determineSameItemPlace(array $result, $key, $value)
    {
        $response = -1;
        $counter = 0;
        foreach ($result as $item){
            if(isset($item[$key]) && in_array($value, $item[$key])){
                $response = $counter;
                break;
            }
            $counter += 1;
        }

        return $response;
    }

}

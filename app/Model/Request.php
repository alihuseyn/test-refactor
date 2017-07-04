<?php

namespace App\Model;

class Request extends Base
{
    // Table name which model will reference
    public $table = "requests";

    /**
     * Return user information which request done by.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Model\Request');
    }

    /**
     * check whether requested item exists or not
     *  - if exists then return message which declare request exists
     *  - else add request
     * @param array $params
     * @return integer
     */
    public static function add(array $params)
    {
        $existence = self::exists($params);
        if(!$existence) {
            $request = new Request;
            $request->user_id = $params['user_id'];
            $request->value = $params['value'];
            $request->from = $params['from'];
            $request->to = $params['to'];
            $request->situation = 0;
            $request->save();
            return self::ADDED;
        } else{
            return $existence;
        }
    }


    /**
     * Check whether request exists or not
     * - If request exist check situation of it to determine whether
     *  it is translated or not
     * @param array $params
     * @return bool|int
     */
    public static function exists(array $params)
    {
        $request = Request::where('value', '=', $params['value'])
                    ->where('from', '=', $params['from'])
                    ->where('to', '=', $params['to'])
                    ->where('situation', '=', 0)
                    ->first();
        if(!is_null($request) && !empty($request)){
            if($request->situation == 0){
                return self::REQUESTED;
            }
            return self::TRANSLATED;
        }
        if(self::translateExistence($params)){
            return self::TRANSLATED;
        }
        return false;
    }


    /**
     * Check existance in translation table for given parameters
     * @param array $params
     * @return bool
     */
    public static function translateExistence(array $params){
        $translate = Translate::where(function ($query) use($params) {
            $query->where('value', '=', $params['value'])
                ->where('from', '=', $params['from'])
                ->where('to', '=', $params['to']);
        })->orWhere(function ($query) use($params) {
            $query->where('translation', '=', $params['value'])
                ->where('to', '=', $params['from'])
                ->where('from', '=', $params['to']);
        })->first();

        if(!is_null($translate) && !empty($translate)){
            return true;
        }
        return false;
    }


    /**
     * Fetch waiting request list which has situation = 0
     * @param array|null $params
     * @return array
     */
    public static function fetch(array $params = null)
    {
        $requests = Request::where('situation', '=', 0)->get();
        $result = [];
        foreach ($requests as $request){
            array_push($result, [
                'value' => $request->value,
                'language' => [
                    'from' => $request->from,
                    'to'   => $request->to,
                ],
            ]);
        }
        return $result;
    }

    /**
     * Set given content as translated with updating it in request table
     * @param array $params
     */
    public static function setTranslated(array $params){
        Request::where('value', '=', $params['value'])
            ->where('from', '=', $params['from'])
            ->where('to', '=', $params['to'])
            ->update([
                'situation' => 1
            ]);
    }

}

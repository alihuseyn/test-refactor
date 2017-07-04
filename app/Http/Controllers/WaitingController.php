<?php

namespace App\Http\Controllers;

use App\Model\Request;

class WaitingController extends API
{
    /**
     * Get all waiting request for translation and return then as list
     * @return $this
     */
    public function retrieve()
    {
        $requests = Request::fetch();
        return response(API::prettyResponse($requests, false), 200)
                    ->header('Content-Type', 'application/json ; charset=utf-8');
    }

}
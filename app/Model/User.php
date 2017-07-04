<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    // Table name for Model which will reference
    public $table = "users";

    /**
     * Fetch User Information according to given array content with email and token
     * @param array $params
     * @return User
     * */
    public static function fetch(array $params)
    {
        return User::where('email', '=', $params['email'])
                ->where('token', '=', $params['token'])->first();
    }

    /**
     * Get all requests done by user profile has <USER>
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function request()
    {
        return $this->hasMany('App\Model\Request');
    }

}

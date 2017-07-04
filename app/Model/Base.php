<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

abstract class Base extends Model
{
    // ADDED => constant value - If the value exists
    const ADDED = 1;
    // REQUESTED => constant value - If the requested value already requested before
    const REQUESTED = 2;
    // TRANSLATED => constant value - If the translated value already translated before
    const TRANSLATED = 3;
    // NOT_EXIST => constant value - If the required value not exists in tranlation list
    const NOT_EXIST = 4;
}
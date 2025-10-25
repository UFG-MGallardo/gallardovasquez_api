<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Zona extends Model
{
    //
    protected $connection = 'mysql';
    protected $table = 'zona';
    protected $primatyKey = 'id';
    public $incrementing = true;
    public $timestamp = false;
    
}
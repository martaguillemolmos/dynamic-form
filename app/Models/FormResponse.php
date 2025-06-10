<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class FormResponse extends Model
{
   protected $connection = 'mongodb';
   protected $collection = 'form_responses'; 
   
   protected $fillable = ['form_id', 'data', 'created_at'];
   
   protected $casts = [
        'data' => 'array',
        'form_id' => 'string', 
        'created_at' => 'datetime'
    ];
}
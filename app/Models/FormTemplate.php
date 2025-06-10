<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class FormTemplate extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'form_templates';

    protected $fillable = [
        'name',
        'fields',
    ];

    protected $casts = [
        'fields' => 'array',
    ];
}

<?php

use Illuminate\Database\Migrations\Migration;
use MongoDB\Laravel\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormTemplatesCollection extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::connection('mongodb')->create('form_templates', function (Blueprint $collection) {
            $collection->index('name'); 
            $collection->index('fields'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::connection('mongodb')->dropIfExists('form_templates');
    }
}
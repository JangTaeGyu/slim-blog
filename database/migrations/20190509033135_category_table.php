<?php

use \App\Migration\Migration;

class CategoryTable extends Migration
{
    public function up()
    {
        $this->schema->create('categories', function (\Illuminate\Database\Schema\Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });
    }

    public function down()
    {
        $this->schema->drop('categories');
    }
}

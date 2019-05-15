<?php

use \App\Migration\Migration;

class TagTable extends Migration
{
    public function up()
    {
        $this->schema->create('tags', function (\Illuminate\Database\Schema\Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });
    }

    public function down()
    {
        $this->schema->drop('tags');
    }
}

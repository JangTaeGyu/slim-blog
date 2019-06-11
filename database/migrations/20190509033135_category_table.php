<?php

use \App\Migration\Migration;

class CategoryTable extends Migration
{
    public function up()
    {
        $this->schema->create('categories', function (\Illuminate\Database\Schema\Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->nullable()->unsigned();
            $table->string('name');
            $table->text('detail');
            $table->integer('count')->unsigned()->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        $this->schema->drop('categories');
    }
}

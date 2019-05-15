<?php

use \App\Migration\Migration;

class PostTagTable extends Migration
{
    public function up()
    {
        $this->schema->create('post_tag', function (\Illuminate\Database\Schema\Blueprint $table) {
            $table->increments('id');
            $table->integer('post_id')->unsigned();
            $table->foreign('post_id')->references('id')->on('posts');
            $table->integer('tag_id')->unsigned();
            $table->foreign('tag_id')->references('id')->on('tags');
        });
    }

    public function down()
    {
        $this->schema->drop('post_tag');
    }
}

<?php

use \App\Migration\Migration;

class CommentTable extends Migration
{
    public function up()
    {
        $this->schema->create('comments', function (\Illuminate\Database\Schema\Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email');
            $table->text('comment');
            $table->boolean('approved');
            $table->integer('post_id')->unsigned();
            $table->timestamps();
        });
    }

    public function down()
    {
        $this->schema->drop('comments');
    }
}

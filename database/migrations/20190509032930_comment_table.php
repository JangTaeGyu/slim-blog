<?php

use \App\Migration\Migration;

class CommentTable extends Migration
{
    public function up()
    {
        $this->schema->create('comments', function (\Illuminate\Database\Schema\Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->nullable()->unsigned();
            $table->integer('user_id')->nullable()->unsigned();
            $table->string('name')->nullable();
            $table->string('password', 60)->nullable();
            $table->text('comment');
            $table->boolean('approved')->default(true);
            $table->enum('target', ['posts', 'notices']);
            $table->integer('target_id')->unsigned();
            $table->ipAddress('ip');
            $table->timestamps();
        });
    }

    public function down()
    {
        $this->schema->drop('comments');
    }
}

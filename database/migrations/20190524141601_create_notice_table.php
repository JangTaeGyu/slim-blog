<?php

use \App\Migration\Migration;

class CreateNoticeTable extends Migration
{
    public function up()
    {
        $this->schema->create('notices', function (\Illuminate\Database\Schema\Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->text('content');
            $table->integer('user_id')->nullable()->unsigned();
            $table->string('name')->nullable();
            $table->boolean('accept_commnet')->default(true);
            $table->boolean('approved')->default(true);
            $table->integer('hit')->unsigned()->default(0);
            $table->integer('count')->unsigned()->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        $this->schema->drop('notices');
    }
}

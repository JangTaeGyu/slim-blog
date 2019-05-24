<?php

use \App\Migration\Migration;

class CreateGuestBookTable extends Migration
{
    public function up()
    {
        $this->schema->create('guestbooks', function (\Illuminate\Database\Schema\Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->nullable()->unsigned();
            $table->integer('user_id')->nullable()->unsigned();
            $table->string('name')->nullable();
            $table->string('password', 60)->nullable();
            $table->text('comment');
            $table->boolean('approved')->default(true);
            $table->ipAddress('ip');
            $table->timestamps();
        });
    }

    public function down()
    {
        $this->schema->drop('guestbooks');
    }
}

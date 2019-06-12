<?php

use \App\Migration\Migration;

class UserTable extends Migration
{
    public function up()
    {
        $this->schema->create('users', function (\Illuminate\Database\Schema\Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password', 60);
            $table->enum('grade', ['A', 'N'])->default('N');
            $table->boolean('approved')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        $this->schema->drop('users');
    }
}

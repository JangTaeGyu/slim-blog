<?php
use App\Migration\Migration;

class PostTable extends Migration
{
    public function up()
    {
        $this->schema->create('posts', function (\Illuminate\Database\Schema\Blueprint $table) {
            $table->increments('id');
            $table->integer('category_id')->nullable()->unsigned();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('image')->nullable();
            $table->text('body');
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
        $this->schema->drop( 'posts');
    }
}

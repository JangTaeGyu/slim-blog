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
            $table->timestamps();
        });
    }

    public function down()
    {
        $this->schema->drop( 'posts');
    }
}

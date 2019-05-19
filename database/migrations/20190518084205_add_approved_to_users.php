<?php

use \App\Migration\Migration;

class AddApprovedToUsers extends Migration
{
    public function up()
    {
        $this->schema->table('users', function (\Illuminate\Database\Schema\Blueprint $table) {
            $table->boolean('approved')->default(false)->after('email');
        });
    }

    public function down()
    {
        $this->schema->table('users', function (\Illuminate\Database\Schema\Blueprint $table) {
            $table->dropColumn('approved');
        });
    }
}

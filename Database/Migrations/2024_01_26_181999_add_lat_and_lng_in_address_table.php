<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('iprofile__addresses', function (Blueprint $table) {
  
          $table->string('lat')->nullable()->after('neighborhood');  
          $table->string('lng')->nullable()->after('lat');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('iprofile__addresses', function (Blueprint $table) {
  
          $table->dropColumn('lat');
          $table->dropColumn('lng');

        });
    }
};

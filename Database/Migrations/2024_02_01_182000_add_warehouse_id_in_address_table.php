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
  
          $table->integer('warehouse_id')->unsigned()->after('type')->nullable();

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
  
          $table->dropColumn('warehouse_id');
         
        });
    }
};

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminDayOffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_day_off', function (Blueprint $table) {
            $table->integer('admin_id')->unsigned()->nullable();
            $table->foreign('admin_id')->references('id')
                ->on('admins')->onDelete('cascade');

            $table->integer('day_off_id')->unsigned()->nullable();
            $table->foreign('day_off_id')->references('id')
                ->on('day_offs')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_dayoff');
    }
}

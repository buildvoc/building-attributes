<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sx_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('os_topo_toid')->nullable();
            $table->string('os_topo_toid_version')->nullable();
            $table->date('bha_process_date')->nullable();
            $table->string('tile_ref')->nullable();
            $table->string('abs_min')->nullable();
            $table->string('abs_h2')->nullable();
            $table->string('abs_h_max')->nullable();
            $table->string('rel_h2')->nullable();
            $table->string('rel_h_max')->nullable();
            $table->string('bha_conf')->nullable();
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
        Schema::dropIfExists('sx_data');
    }
};

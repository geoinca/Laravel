<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFileuploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fileuploads', function (Blueprint $table) {
            $table->id();
            $table->string('name', 40);
            $table->string('url', 80);
            $table->integer('user_id');
            $table->string('objid', 3)->default('obj');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.2
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fileuploads');
    }
}

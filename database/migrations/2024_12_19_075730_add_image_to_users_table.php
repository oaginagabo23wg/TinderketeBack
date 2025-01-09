<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('users', function (Blueprint $table) {
        $table->string('image')->nullable()->after('email'); // Añadir columna "image"
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('image'); // Revertir el cambio
    });
}

};
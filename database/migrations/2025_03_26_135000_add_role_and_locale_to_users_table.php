<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('user'); // Ajouter la colonne 'role' avec une valeur par défaut 'user'
            $table->string('locale')->default('en'); // Ajouter la colonne 'locale' avec une valeur par défaut 'en'
        });
    }
    
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'locale']);
        });
    }
    
};

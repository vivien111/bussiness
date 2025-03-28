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
        Schema::table('roles', function (Blueprint $table) {
            // 1. Trouver un ID d'utilisateur valide (ex: le premier admin)
            $defaultUserId = DB::table('users')->value('id');
    
            // 2. Ajouter la colonne avec une valeur par défaut
            $table->unsignedBigInteger('user_id')->default($defaultUserId);
    
            // 3. Nettoyer les user_id invalides (remplacer par $defaultUserId)
            DB::table('roles')
                ->whereNotIn('user_id', DB::table('users')->pluck('id'))
                ->update(['user_id' => $defaultUserId]);
    
            // 4. Ajouter la contrainte
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            //
        });
    }
};

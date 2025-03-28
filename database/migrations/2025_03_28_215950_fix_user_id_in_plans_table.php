<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Étape 1 : Ajouter la colonne sans contrainte d'abord
        Schema::table('plans', function (Blueprint $table) {
            if (!Schema::hasColumn('plans', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
            }
        });

        // Étape 2 : Mettre à jour les enregistrements existants
        $defaultUserId = DB::table('users')->value('id') ?? 1;
        DB::table('plans')->update(['user_id' => $defaultUserId]);

        // Étape 3 : Ajouter la contrainte de clé étrangère
        Schema::table('plans', function (Blueprint $table) {
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade')
                  ->change();
        });
    }

    public function down()
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
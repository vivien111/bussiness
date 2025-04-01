<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToSubscriptionsTable extends Migration
{
    /**
     * Exécute la migration.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            // Ajout des colonnes comme définies
            $table->morphs('billable'); // Ajoute les colonnes billable_id et billable_type
            $table->unsignedInteger('plan_id'); // La clé étrangère pour le plan
            $table->string('vendor_slug'); // Le slug du fournisseur
            $table->string('vendor_product_id')->nullable(); // L'ID produit du fournisseur
            $table->string('vendor_transaction_id')->nullable(); // L'ID de transaction du fournisseur
            $table->string('vendor_customer_id')->nullable(); // L'ID client du fournisseur
            $table->string('vendor_subscription_id')->nullable(); // L'ID abonnement du fournisseur
            $table->string('status'); // Le statut de l'abonnement (actif, inactif, suspendu, etc.)
            $table->enum('cycle', ['month', 'year', 'onetime'])->default('month'); // Le cycle de facturation
            $table->integer('seats')->default(1); // Le nombre de sièges ou utilisateurs pour cet abonnement
            $table->timestamp('ends_at')->nullable(); // La date de fin de l'abonnement
            $table->timestamps();

            // Ajout des index pour billable_id, billable_type et plan_id
            $table->index(['billable_id', 'billable_type', 'plan_id']);

            // Définition de la contrainte unique sur vendor_slug et vendor_subscription_id
            $table->unique(['vendor_slug', 'vendor_subscription_id']);

            // Définir la clé étrangère pour plan_id
            $table->foreign('plan_id')->references('id')->on('plans')->onDelete('cascade');
        });
    }

    /**
     * Annule la migration.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            // Suppression des colonnes ajoutées dans le `up`
            $table->dropForeign(['plan_id']);
            $table->dropIndex(['billable_id', 'billable_type', 'plan_id']);
            $table->dropUnique(['vendor_slug', 'vendor_subscription_id']);
            $table->dropColumn([
                'billable_id', 
                'billable_type', 
                'plan_id', 
                'vendor_slug', 
                'vendor_product_id', 
                'vendor_transaction_id', 
                'vendor_customer_id', 
                'vendor_subscription_id', 
                'status', 
                'cycle', 
                'seats', 
                'ends_at'
            ]);
        });
    }
}

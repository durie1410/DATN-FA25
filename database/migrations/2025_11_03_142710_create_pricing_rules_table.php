<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePricingRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pricing_rules', function (Blueprint $table) {
            $table->id();
            $table->string('rule_type'); // rental, deposit
            $table->string('scope'); // global, category, book, user_status
            $table->json('condition')->nullable();
            $table->string('amount_type'); // flat, percent, tier
            $table->decimal('amount_value', 12, 4);
            $table->integer('priority')->default(0);
            $table->boolean('active')->default(true);
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
        Schema::dropIfExists('pricing_rules');
    }
}

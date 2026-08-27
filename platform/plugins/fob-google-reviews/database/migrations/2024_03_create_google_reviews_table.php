<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('google_reviews')) {
            return;
        }

        Schema::create('google_reviews', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->boolean('is_enabled')->default(false);
            $table->string('custom_place_id')->nullable();
            $table->timestamps();

            if (Schema::hasTable('ec_products')) {
                $table->foreign('product_id')
                    ->references('id')
                    ->on('ec_products')
                    ->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('google_reviews');
    }
};

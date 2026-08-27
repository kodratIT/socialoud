<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('google_reviews_data')) {
            return;
        }

        Schema::create('google_reviews_data', function (Blueprint $table): void {
            $table->id();
            $table->string('author_name');
            $table->string('author_url')->nullable();
            $table->string('profile_photo_url')->nullable();
            $table->unsignedTinyInteger('rating')->default(5);
            $table->text('text')->nullable();
            $table->timestamp('review_date')->nullable();
            $table->string('status', 60)->default('published');
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->index('status');
            $table->index('order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('google_reviews_data');
    }
};

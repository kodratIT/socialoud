<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('google_reviews')) {
            return;
        }

        Schema::table('google_reviews', function (Blueprint $table): void {
            if (! Schema::hasColumn('google_reviews', 'reviewable_id')) {
                $table->unsignedBigInteger('reviewable_id')->nullable()->after('id');
            }

            if (! Schema::hasColumn('google_reviews', 'reviewable_type')) {
                $table->string('reviewable_type')->nullable()->after('reviewable_id');
            }
        });

        if (Schema::hasColumn('google_reviews', 'product_id')) {
            DB::table('google_reviews')
                ->whereNotNull('product_id')
                ->whereNull('reviewable_id')
                ->update([
                    'reviewable_id' => DB::raw('product_id'),
                    'reviewable_type' => 'Botble\\Ecommerce\\Models\\Product',
                ]);

            Schema::table('google_reviews', function (Blueprint $table): void {
                if ($this->hasForeignKey('google_reviews', 'google_reviews_product_id_foreign')) {
                    $table->dropForeign('google_reviews_product_id_foreign');
                }

                $table->dropColumn('product_id');
            });
        }

        Schema::table('google_reviews', function (Blueprint $table): void {
            if (! $this->hasIndex('google_reviews', 'google_reviews_reviewable_id_reviewable_type_index')) {
                $table->index(['reviewable_id', 'reviewable_type']);
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('google_reviews')) {
            return;
        }

        Schema::table('google_reviews', function (Blueprint $table): void {
            if (! Schema::hasColumn('google_reviews', 'product_id')) {
                $table->unsignedBigInteger('product_id')->nullable()->after('id');
            }
        });

        if (Schema::hasColumn('google_reviews', 'reviewable_id') && Schema::hasColumn('google_reviews', 'product_id')) {
            DB::table('google_reviews')
                ->where('reviewable_type', 'Botble\\Ecommerce\\Models\\Product')
                ->update([
                    'product_id' => DB::raw('reviewable_id'),
                ]);
        }

        Schema::table('google_reviews', function (Blueprint $table): void {
            if ($this->hasIndex('google_reviews', 'google_reviews_reviewable_id_reviewable_type_index')) {
                $table->dropIndex('google_reviews_reviewable_id_reviewable_type_index');
            }

            if (Schema::hasColumn('google_reviews', 'reviewable_id')) {
                $table->dropColumn('reviewable_id');
            }

            if (Schema::hasColumn('google_reviews', 'reviewable_type')) {
                $table->dropColumn('reviewable_type');
            }
        });
    }

    protected function hasForeignKey(string $table, string $foreignKey): bool
    {
        $foreignKeys = Schema::getForeignKeys($table);

        foreach ($foreignKeys as $key) {
            if ($key['name'] === $foreignKey) {
                return true;
            }
        }

        return false;
    }

    protected function hasIndex(string $table, string $indexName): bool
    {
        $indexes = Schema::getIndexes($table);

        foreach ($indexes as $index) {
            if ($index['name'] === $indexName) {
                return true;
            }
        }

        return false;
    }
};

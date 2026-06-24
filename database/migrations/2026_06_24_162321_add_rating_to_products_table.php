<?php
// database/migrations/add_rating_to_products_table.php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Tambah kolom setelah "stock"
            $table->decimal('rating', 3, 2)->default(0)->after('stock');
            $table->integer('review_count')->default(0)->after('rating');

            // Ubah kolom yang sudah ada (butuh doctrine/dbal)
            $table->string('name', 300)->change(); // perbesar dari 255 ke 300

            // Tambah index baru
            $table->index('rating');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['rating']);
            $table->dropColumn(['rating', 'review_count']);
        });
    }
};


<?php
// database/migrations/xxxx_create_products_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')
                ->constrained()
                ->onDelete('restrict');
            $table->string('name', 200);
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 15, 2);
            $table->unsignedInteger('stock')->default(0);
            $table->string('image')->nullable();
            $table->enum('status', ['active', 'inactive', 'draft'])
                ->default('active');
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
            $table->softDeletes(); // Kolom deleted_at
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};


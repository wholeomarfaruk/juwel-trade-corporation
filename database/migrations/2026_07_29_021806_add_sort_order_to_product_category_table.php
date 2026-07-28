<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('product_category', function (Blueprint $table) {
            $table->integer('sort_order')->default(0)->after('category_id');
        });

        // Seed a per-category order for existing pivot rows (newest product gets lowest number).
        $categoryIds = \DB::table('product_category')->distinct()->pluck('category_id');
        foreach ($categoryIds as $categoryId) {
            $order = 1;
            $rows = \DB::table('product_category')
                ->where('category_id', $categoryId)
                ->orderByDesc('products_id')
                ->pluck('id');
            foreach ($rows as $id) {
                \DB::table('product_category')->where('id', $id)->update(['sort_order' => $order++]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_category', function (Blueprint $table) {
            $table->dropColumn('sort_order');
        });
    }
};

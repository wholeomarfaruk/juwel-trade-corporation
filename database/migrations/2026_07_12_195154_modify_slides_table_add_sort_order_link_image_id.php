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
        Schema::table('slides', function (Blueprint $table) {
            $table->unsignedInteger('sort_order')->default(0)->after('status');
            $table->string('link')->nullable()->after('title');
            $table->foreignId('image_id')->nullable()->after('link')
                ->constrained('media')->nullOnDelete();

            $table->dropColumn(['subtitle', 'tagline', 'image', 'title']);
        });

        Schema::table('slides', function (Blueprint $table) {
            $table->string('title')->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('slides', function (Blueprint $table) {
            $table->dropConstrainedForeignId('image_id');
            $table->dropColumn(['sort_order', 'link', 'title']);
        });

        Schema::table('slides', function (Blueprint $table) {
            $table->string('title')->after('id');
            $table->string('subtitle')->nullable();
            $table->string('tagline')->nullable();
            $table->string('image')->nullable();
        });
    }
};

<?php

use App\Models\Chapter;
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
        Schema::connection('temp')->create('chapter_images', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Chapter::class)
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('temp')->table('chapter_images', function (Blueprint $table) {
            $table->dropForeignIdFor(Chapter::class);
        });
        Schema::connection('temp')->dropIfExists('chapter_images');
    }
};

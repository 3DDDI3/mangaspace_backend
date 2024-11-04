<?php

use App\Models\Genre;
use App\Models\Title;
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
        Schema::connection('temp')->create('title_genres', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Title::class)
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignIdFor(Genre::class)
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
        Schema::connection('temp')->table('title_genres', function (Blueprint $table) {
            $table->dropForeignIdFor(Title::class);
            $table->dropForeignIdFor(Genre::class);
        });
        Schema::connection('temp')->dropIfExists('title_genres');
    }
};

<?php

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
        Schema::connection('temp')->create('title_covers', function (Blueprint $table) {
            $table->id();
            $table->text('path');
            $table->foreignIdFor(Title::class)
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
        Schema::connection('temp')->table('title_covers', function (Blueprint $table) {
            $table->dropForeignIdFor(Title::class);
        });
        Schema::connection('temp')->dropIfExists('title_covers');
    }
};

<?php

use App\Models\Category;
use App\Models\TitleStatus;
use App\Models\TranslateStatus;
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
        Schema::connection('temp')->create('titles', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Category::class)
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->string('ru_name', 255)->nullable();
            $table->string('eng_name', 255)->nullable();
            $table->string('other_names', 255);
            $table->longText('description')->nullable();
            $table->foreignIdFor(TitleStatus::class)
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignIdFor(TranslateStatus::class)
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->year('release_year')->nullable();
            $table->string('country', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('temp')->table('titles', function (Blueprint $table) {
            $table->dropForeignIdFor(Category::class);
            $table->dropForeignIdFor(TitleStatus::class);
            $table->dropForeignIdFor(TranslateStatus::class);
        });
        Schema::connection('temp')->dropIfExists('titles');
    }
};

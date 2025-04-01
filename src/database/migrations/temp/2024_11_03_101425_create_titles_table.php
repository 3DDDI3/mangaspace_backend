<?php

use App\Models\Category;
use App\Models\ReleaseFormat;
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
            $table->string('ru_name', 255)->nullable();
            $table->string('eng_name', 255)->nullable();
            $table->string('slug', 255);
            $table->string('path', 255)->nullable();
            $table->text('other_names')->nullable();
            $table->foreignIdFor(Category::class)
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignIdFor(ReleaseFormat::class)
                ->nullable()
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
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
            $table->boolean('is_hide')->default(0)->nullable();
            $table->bigInteger('rating')->unsigned()->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate();
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

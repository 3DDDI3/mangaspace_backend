<?php

use App\Models\Chapter;
use App\Models\Person;
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
        Schema::connection('temp')->create('chapter_images', function (Blueprint $table) {
            $table->id();
            $table->text('extensions')->comment('расширения картинок (*.jpeg|*.jpg|*.webp|*.png)');
            $table->foreignIdFor(Chapter::class)
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignIdFor(Person::class)
                ->constrained('persons')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('temp')->table('chapter_images', function (Blueprint $table) {
            $table->dropForeignIdFor(Chapter::class);
            $table->dropForeignIdFor(Person::class);
        });
        Schema::connection('temp')->dropIfExists('chapter_images');
    }
};

<?php

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
        Schema::connection('temp')->create('title_persons', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Title::class)
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
        Schema::connection('temp')->table('title_persons', function (Blueprint $table) {
            $table->dropForeignIdFor(Title::class);
            $table->dropForeignIdFor(Person::class);
        });
        Schema::connection('temp')->dropIfExists('title_persons');
    }
};

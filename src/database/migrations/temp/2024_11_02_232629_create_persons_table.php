<?php

use App\Models\PersonType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection('temp')->create('persons', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('slug', 255)->nullable();
            $table->string('alt_name', 255)->nullable();
            $table->text('description')->nullable();
            $table->foreignIdFor(PersonType::class)
                ->nullable()
                ->constrained()
                ->nullOnDelete()
                ->cascadeOnUpdate();
            $table->boolean('is_hide')->default(0)->nullable();
            $table->bigInteger('rating')
                ->unsigned()
                ->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('temp')->table('persons', function (Blueprint $table) {
            $table->dropForeignIdFor(PersonType::class);
        });
        Schema::connection('temp')->dropIfExists('persons');
    }
};

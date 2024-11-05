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
        Schema::connection('temp')->create('chapters', function (Blueprint $table) {
            $table->id();
            $table->string('path');
            $table->integer('volume')->nullable();
            $table->integer('number');
            $table->string('name', 1024)->nullable();
            $table->foreignIdFor(Title::class)
                ->constrained()
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
        Schema::connection('temp')->table('chapters', function (Blueprint $table) {
            $table->dropForeignIdFor(Title::class);
        });
        Schema::connection('temp')->dropIfExists('chapters');
    }
};

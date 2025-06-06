<?php

use App\Models\Person;
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
        Schema::connection('temp')->create('person_photos', function (Blueprint $table) {
            $table->id();
            $table->text('path');
            $table->foreignIdFor(Person::class)
                ->constrained('persons')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
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
        Schema::connection('temp')->table('person_photos', function (Blueprint $table) {
            $table->dropForeignIdFor(Person::class);
        });
        Schema::connection('temp')->dropIfExists('person_photos');
    }
};

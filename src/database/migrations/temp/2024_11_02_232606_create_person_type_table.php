<?php

use App\Enums\PersonType;
use Illuminate\Container\Attributes\Database;
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
        Schema::connection('temp')->create('person_types', function (Blueprint $table) {
            $table->id();
            $table->string('type', 30);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate();
        });

        DB::connection('temp')->table('person_types')->insert([
            ['id' => PersonType::Translator, 'type' => 'Переводчик', 'created_at' => now(), 'updated_at' => now()],
            ['id' => PersonType::Author, 'type' => 'Автор', 'created_at' => now(), 'updated_at' => now()],
            ['id' => PersonType::Painter, 'type' => 'Художник', 'created_at' => now(), 'updated_at' => now()],
            ['id' => PersonType::Publisher, 'type' => 'Издатель', 'created_at' => now(), 'updated_at' => now()],
            ['id' => PersonType::Magazine, 'type' => 'Журнал', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('temp')->dropIfExists('person_types');
    }
};

<?php

use App\Enums\TranslateStatus;
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
        Schema::connection('temp')->create('translate_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('status', 50);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate();
        });

        DB::connection('temp')->table('translate_statuses')->insert([
            ['id' => TranslateStatus::continues, 'status' => 'Продолжается', 'created_at' => now(), 'updated_at' => now()],
            ['id' => TranslateStatus::finished, 'status' => 'Завершен', 'created_at' => now(), 'updated_at' => now()],
            ['id' => TranslateStatus::freezed, 'status' => 'Заморожен', 'created_at' => now(), 'updated_at' => now()],
            ['id' => TranslateStatus::terminated, 'status' => 'Прекращен', 'created_at' => now(), 'updated_at' => now()],
            ['id' => TranslateStatus::licensed, 'status' => 'Лицензировано', 'created_at' => now(), 'updated_at' => now()],
            ['id' => TranslateStatus::noTranslator, 'status' => 'Нет переводчика', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('temp')->dropIfExists('translate_statuses');
    }
};

<?php

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
            $table->timestamps();
        });

        DB::connection('temp')->table('translate_statuses')->insert([
            ['id' => TranslateStatus::continues, 'status' => 'Продолжается'],
            ['id' => TranslateStatus::finished, 'status' => 'Завершен'],
            ['id' => TranslateStatus::freezed, 'status' => 'Заморожен'],
            ['id' => TranslateStatus::terminated, 'status' => 'Прекращен'],
            ['id' => TranslateStatus::licensed, 'status' => 'Лицензировано'],
            ['id' => TranslateStatus::noTranslator, 'status' => 'Нет переводчика'],
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

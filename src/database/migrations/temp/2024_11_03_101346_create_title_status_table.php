<?php

use App\Enums\TitleStatus;
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
        Schema::connection('temp')->create('title_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('status', 50);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate();
        });

        DB::connection('temp')->table('title_statuses')->insert([
            ['id' => TitleStatus::continues, 'status' => 'Продолжается', 'created_at' => now(), 'updated_at' => now()],
            ['id' => TitleStatus::announcement, 'status' => 'Анонс', 'created_at' => now(), 'updated_at' => now()],
            ['id' => TitleStatus::finished, 'status' => 'Завершен', 'created_at' => now(), 'updated_at' => now()],
            ['id' => TitleStatus::suspended, 'status' => 'Приостановлен', 'created_at' => now(), 'updated_at' => now()],
            ['id' => TitleStatus::terminated, 'status' => 'Прекращен', 'created_at' => now(), 'updated_at' => now()],
            ['id' => TitleStatus::licensed, 'status' => 'Лицензировано', 'created_at' => now(), 'updated_at' => now()],
            ['id' => TitleStatus::noTranslator, 'status' => 'Нет переводчика', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('temp')->dropIfExists('title_statuses');
    }
};

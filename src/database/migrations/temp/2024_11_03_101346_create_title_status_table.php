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
        Schema::connection('temp')->create('title_status', function (Blueprint $table) {
            $table->id();
            $table->string('statuses', 50);
            $table->timestamps();
        });

        DB::connection('temp')->table('title_statuses')->insert([
            ['id' => TitleStatus::continues, 'type' => 'Продолжается'],
            ['id' => TitleStatus::announcement, 'type' => 'Продолжается'],
            ['id' => TitleStatus::finished, 'type' => 'Продолжается'],
            ['id' => TitleStatus::suspended, 'type' => 'Продолжается'],
            ['id' => TitleStatus::terminated, 'type' => 'Продолжается'],
            ['id' => TitleStatus::licensed, 'type' => 'Продолжается'],
            ['id' => TitleStatus::noTranslator, 'type' => 'Продолжается'],
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

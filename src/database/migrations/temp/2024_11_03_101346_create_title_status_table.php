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
            $table->timestamps();
        });

        DB::connection('temp')->table('title_statuses')->insert([
            ['id' => TitleStatus::continues, 'status' => 'Продолжается'],
            ['id' => TitleStatus::announcement, 'status' => 'Продолжается'],
            ['id' => TitleStatus::finished, 'status' => 'Продолжается'],
            ['id' => TitleStatus::suspended, 'status' => 'Продолжается'],
            ['id' => TitleStatus::terminated, 'status' => 'Продолжается'],
            ['id' => TitleStatus::licensed, 'status' => 'Продолжается'],
            ['id' => TitleStatus::noTranslator, 'status' => 'Продолжается'],
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

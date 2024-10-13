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
        Schema::create('device_types', function (Blueprint $table) {
            $table->id();
            $table->string('type', 255);
            $table->text('icon');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate();
        });

        DB::table('device_types')->insert(
            [
                [
                    'type' => 'Телевизор',
                    'icon' => '',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'type' => 'Смартфон',
                    'icon' => '',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'type' => 'Компьютер',
                    'icon' => '',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('device_types');
    }
};

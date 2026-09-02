<?php

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
        Schema::table('jabatans', function (Blueprint $table) {
            $table->tinyInteger('level')->unsigned()->default(5)
                ->comment('0=Non-hierarki, 1=Dirut, 2=Direktur, 3=KaBag/Pimcab, 4=Kasie/Head, 5=Staff')
                ->after('urutan');

            $table->index('level', 'jabatans_level_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jabatans', function (Blueprint $table) {
            $table->dropIndex('jabatans_level_index');
            $table->dropColumn('level');
        });
    }
};

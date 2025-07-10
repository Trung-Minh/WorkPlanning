<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('CAU_HINH_THONG_BAO', function (Blueprint $table) {
            $table->char('ID_USER', 8)->after('ID_CAUHINH');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cau_hinh_thong_bao', function (Blueprint $table) {
            //
        });
    }
};

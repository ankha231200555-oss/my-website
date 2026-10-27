<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('tin_tucs', function (Blueprint $table) {
            $table->id();
            $table->string('tieude', 200);
            $table->string('tomtat', 300)->nullable();
            $table->longText('noidung');
            $table->string('hinhanh_path')->nullable();
            $table->date('ngaydang')->nullable();
            $table->foreignId('danhmuc_id')->nullable()->constrained('danh_mucs')->nullOnDelete();
            $table->timestamps(); $table->softDeletes();
        });
    }
    public function down(): void { Schema::dropIfExists('tin_tucs'); }
};

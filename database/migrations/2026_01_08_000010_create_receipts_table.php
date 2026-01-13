<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('expense_id')->nullable()->constrained('expenses')->onDelete('set null');
            $table->string('image_path');
            $table->text('ocr_text')->nullable();
            $table->decimal('extracted_amount', 12, 2)->nullable();
            $table->string('extracted_category')->nullable();
            $table->string('merchant')->nullable();
            $table->date('extracted_date')->nullable();
            $table->string('status')->default('pending'); // pending, processing, processed, failed
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('receipts');
    }
};

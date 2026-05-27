<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('users');
            $table->foreignId('employee_id')->nullable()->constrained('users');
            $table->foreignId('order_status_id')->constrained('order_statuses');
            $table->date('order_date')->nullable();
            $table->date('deadline')->nullable();
            $table->date('completed_at')->nullable();
            $table->text('description')->nullable();
            $table->decimal('total_price', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
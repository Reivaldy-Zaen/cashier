<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            // $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('user_id', 50);
            $table->decimal('total_amount', 12, 2);
            $table->decimal('payment_amount', 12, 2);
            $table->decimal('change_amount', 12, 2);
            $table->string('payment_method');
            $table->timestamps();
        });

        Schema::create('transaction_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained()->onDelete('cascade');

            // pakai uuid karena products.id adalah uuid
            $table->foreignUuid('product_id')->constrained('products')->onDelete('cascade');

            $table->string('product_name');
            $table->integer('quantity');
            $table->decimal('price', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transaction_items');
        Schema::dropIfExists('transactions');
    }
}

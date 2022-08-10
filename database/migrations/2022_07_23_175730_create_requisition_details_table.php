<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requisition_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requisition_id')->constrained('requisitions')->onDelete('cascade');
            $table->bigInteger('item_id');
            $table->string('item_code');
            $table->string('description')->nullable();
            $table->string('unit')->nullable();
            $table->string('transaction_id')->nullable();
            $table->decimal('available_qty', 18,2)->nullable();
            $table->decimal('request_qty', 18,2)->nullable();
            $table->decimal('approved_qty', 18,2)->nullable();
            $table->decimal('issued_qty', 18,2)->nullable();
            $table->decimal('cost', 18,2)->nullable();
            $table->enum('status', ['Request', 'Approved', 'Rejected', 'Partially Approved'])->default('Request');
            $table->integer('approved_by')->nullable();
            $table->string('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('requisition_details');
    }
};

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
        Schema::create('requisition_returns', function (Blueprint $table) {
            $table->id();
            $table->string('requistion_number')->nullable();
            $table->date('date')->nullable();
            $table->foreignId('returned_by')->constrained('users')->onDelete('no action');
            $table->integer('sage_sync_status')->default(0);
            $table->enum('status', ['Return', 'Approved', 'Rejected', 'Partially Approved'])->default('Return');
            $table->string('notes')->nullable();
            $table->boolean('approval_flag')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('requisition_returns');
    }
};

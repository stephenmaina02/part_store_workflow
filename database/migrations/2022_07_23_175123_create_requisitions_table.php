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
        Schema::create('requisitions', function (Blueprint $table) {
            $table->id();
            $table->string('requistion_number')->nullable();
            $table->date('date');
            $table->foreignId('requested_by')->constrained('users')->onDelete('no action');
            $table->integer('sage_sync_status')->default(0);
            $table->enum('status', ['Request', 'Approved', 'Rejected', 'Partially Approved'])->default('Request');
            $table->string('notes')->nullable();
            $table->boolean('approval_flag')->default(0);
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
        Schema::dropIfExists('requisitions');
    }
};

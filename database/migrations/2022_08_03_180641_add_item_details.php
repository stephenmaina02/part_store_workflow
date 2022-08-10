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
        Schema::table('requisition_notifications', function (Blueprint $table) {
            $table->string('item_code')->nullable();
            $table->string('requested_qty')->nullable();
            $table->string('comment')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('requisition_notifications', function (Blueprint $table) {
            $table->dropColumn('item_code');
            $table->dropColumn('requested_qty');
            $table->dropColumn('comment');
        });
    }
};

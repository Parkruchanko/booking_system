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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('room_id'); 
            $table->date('booking_date')->default(DB::raw('CURRENT_DATE'));
            $table->string('name');
            $table->string('reason');
            $table->string('day');  // เปลี่ยนเป็นประเภทวันที่
            $table->integer('slot'); // คอลัมน์สำหรับคาบเรียน
            $table->timestamps();
    
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};

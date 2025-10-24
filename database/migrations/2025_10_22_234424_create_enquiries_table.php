<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('enquiries', function (Blueprint $table) {
            $table->id();
            $table->foreignId(column: 'contact_')->nullable();
            $table->string(column:'name');
            $table->string(column:'email');
            $table->string(column:'phone')->nullable();
            $table->string(column:'message');
            $table->string(column:'user_agent')->nullable();
            $table->string(column:'ip_address')->nullable();





            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enquiries');
    }
};

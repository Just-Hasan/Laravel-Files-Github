<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('workshop_instructors', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("avatar");
            $table->string("occupation");
            $table->softDeletes();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('workshop_instructors');
    }
};
<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('posts', static function (Blueprint $table) {
            $table->id();

            $table->string('title', 100);
            $table->string('excerpt', 200);
            $table->string('thumbnail', 100)->nullable();
            $table->string('img', 100)->nullable();
            $table->text('body');

            // FK
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();

            // IDx
            $table->index('user_id');

            // Мягкое удаление
            $table->softDeletes();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};

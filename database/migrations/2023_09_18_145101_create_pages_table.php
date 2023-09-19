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
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('theme_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('title')->nullable();
            $table->string('slug')->nullable();
            $table->string('top_line')->nullable();
            $table->string('bottom_line')->nullable();
            $table->string('page_type')->nullable();
            $table->string('reference')->nullable();
            $table->string('redirect_type')->nullable();
            $table->string('title_color')->nullable();
            $table->string('text_color')->nullable();
            $table->string('border_type')->nullable();
            $table->string('attachment_id')->nullable();
            $table->string('is_default')->nullable();
            $table->string('is_monitor')->nullable();
            $table->string('script')->nullable();
            $table->string('page_icon')->nullable();
            $table->enum('status',['Active','InActive'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};

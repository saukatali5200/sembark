<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('acls', function (Blueprint $table) {
            $table->id();
            $table->string('acl_id')->nullable();
            $table->string('name')->nullable();
            $table->string('icon')->nullable();
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('is_deleted')->default(false);
            $table->timestamp('created_at')->nullable()->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->nullable()->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acls');
    }
};
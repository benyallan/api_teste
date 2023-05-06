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
        Schema::create('expenses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('requester_id')->constrained('users');
            $table->foreignUuid('approver_id')->nullable()->constrained('users');
            $table->foreignUuid('company_id')->constrained('companies');
            $table->string('status');
            $table->string('description');
            $table->decimal('value', 10, 2);
            $table->date('date');
            $table->date('approval_date')->nullable();
            $table->string('reason_for_rejection')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};

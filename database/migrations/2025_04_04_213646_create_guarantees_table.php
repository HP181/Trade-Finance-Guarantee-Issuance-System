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
        Schema::create('guarantees', function (Blueprint $table) {
            $table->id();
            $table->string('corporate_reference_number')->unique();
            $table->enum('guarantee_type', ['Bank', 'Bid Bond', 'Insurance', 'Surety']);
            $table->decimal('nominal_amount', 15, 2);
            $table->string('nominal_amount_currency', 3);
            $table->date('expiry_date');
            $table->string('applicant_name');
            $table->text('applicant_address');
            $table->string('beneficiary_name');
            $table->text('beneficiary_address');
            $table->enum('status', ['Draft', 'Under Review', 'Applied', 'Issued', 'Expired', 'Rejected'])->default('Draft');
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guarantees');
    }
};
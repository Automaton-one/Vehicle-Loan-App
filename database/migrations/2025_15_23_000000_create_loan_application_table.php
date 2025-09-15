<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('loan_applications', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('email');
            $table->string('phone');
            $table->date('date_of_birth');
            $table->enum('vehicle_type', ['car', 'ute', 'truck', 'van', 'suv']);
            $table->string('vehicle_make');
            $table->string('vehicle_model');
            $table->decimal('purchase_price', 12, 2)->default(0);
            $table->decimal('deposit_amount', 12, 2)->default(0);
            $table->integer('term_months');
            $table->enum('status', ['submitted', 'in_review', 'approved', 'declined'])->default('submitted');
            $table->boolean('consent_to_credit_check')->default(true);
            $table->timestamp('submitted_at')->useCurrent();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('loan_applications');
    }
};

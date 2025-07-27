<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 100);
            $table->string('middle_name', 100)->nullable();
            $table->string('last_name', 100);
            $table->string('nickname', 100)->nullable();
            $table->date('date_of_birth');
            $table->enum('gender', ['Male', 'Female', 'Prefer not to say']);
            $table->string('contact_number', 20)->nullable();
            $table->string('address')->nullable();
            $table->enum('marital_status', ['Single', 'Married', 'Divorced', 'Widowed']);
            $table->date('anniversary_date')->nullable();
            $table->enum('membership_status', ['Pastor', 'Ministry Head', 'Department Staff', 'Member', 'Attender', 'Visitor', 'Guest',  'Volunteer', 'Former Member'])->nullable();
            $table->date('baptism_date')->nullable();
            $table->text('allergies_medical_notes')->nullable();
            $table->string('emergency_contact_name', 100)->nullable();
            $table->string('emergency_contact_phone', 20)->nullable();
            $table->string('profile_photo_url')->nullable();
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};

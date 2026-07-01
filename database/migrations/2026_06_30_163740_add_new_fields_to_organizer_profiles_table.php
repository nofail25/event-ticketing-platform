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
        Schema::table('organizer_profiles', function (Blueprint $table) {
            $table->string('pic_name')->nullable()->after('company_description');
            $table->string('phone_number', 20)->nullable()->after('pic_name');
            $table->text('address')->nullable()->after('phone_number');
            $table->string('website_url')->nullable()->after('address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('organizer_profiles', function (Blueprint $table) {
            $table->dropColumn(['pic_name', 'phone_number', 'address', 'website_url']);
        });
    }
};

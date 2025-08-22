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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama perusahaan
            $table->string('slug')->unique(); // Slug untuk URL
            $table->string('legal_name')->nullable(); // Nama legal perusahaan
            $table->text('description')->nullable(); // Deskripsi perusahaan
            $table->text('short_description')->nullable(); // Deskripsi singkat
            $table->string('logo')->nullable(); // Path logo perusahaan
            $table->string('website')->nullable(); // Website perusahaan
            $table->string('email')->nullable(); // Email kontak
            $table->string('phone')->nullable(); // Nomor telepon
            $table->text('address')->nullable(); // Alamat lengkap
            $table->string('city')->nullable(); // Kota
            $table->string('province')->nullable(); // Provinsi
            $table->string('postal_code')->nullable(); // Kode pos
            $table->string('country')->default('Indonesia'); // Negara
            $table->string('tax_number')->nullable(); // NPWP
            $table->string('business_license')->nullable(); // Nomor izin usaha
            $table->enum('company_type', ['PT', 'CV', 'UD', 'Koperasi', 'Yayasan', 'Lainnya'])->default('Lainnya');
            $table->date('established_date')->nullable(); // Tanggal berdiri
            $table->integer('employee_count')->nullable(); // Jumlah karyawan
            
            // Social Media Links
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('twitter')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('youtube')->nullable();
            
            // Company Profile
            $table->text('vision')->nullable();
            $table->text('mission')->nullable();
            $table->text('values')->nullable();
            
            // SEO Fields
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            
            $table->boolean('is_active')->default(true); // Status aktif
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};

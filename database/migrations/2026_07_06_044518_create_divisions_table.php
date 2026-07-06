<?php

use App\Models\UploadFile;
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
        Schema::create('divisions', function (Blueprint $table) {
            $table->uuid('id_division')->primary();
            $table->string('name')->unique();
            $table->string('description');
            $table->boolean('status');
            $table->timestamps();
            $table->softDeletes();
            $table->foreignIdFor(UploadFile::class, 'logo_url')->constrained('upload_files')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('divisions');
    }
};

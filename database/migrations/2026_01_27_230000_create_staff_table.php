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
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->char('uuid', 36)->unique();

            // Datos personales
            $table->string('employee_number', 20)->unique()->comment('Numero de empleado');
            $table->string('first_name', 100);
            $table->string('paternal_surname', 100);
            $table->string('maternal_surname', 100)->nullable();
            $table->date('birth_date')->nullable();
            $table->string('curp', 18)->unique()->nullable()->comment('CURP mexicano');
            $table->string('rfc', 13)->nullable()->comment('RFC mexicano');

            // Contacto
            $table->string('phone', 10)->nullable();
            $table->string('mobile', 10)->nullable();

            // Relacion con puesto
            $table->foreignId('position_id')->constrained('positions')->onDelete('restrict');

            // Fechas laborales
            $table->date('hire_date')->nullable();
            $table->date('termination_date')->nullable();

            // Estado y auditoria
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            // Indices para performance
            $table->index('is_active');
            $table->index('position_id');
            $table->index(['paternal_surname', 'maternal_surname', 'first_name']);
        });

        // Tabla separada para emails multiples
        Schema::create('staff_emails', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained('staff')->onDelete('cascade');
            $table->string('email', 100);
            $table->boolean('is_primary')->default(false);
            $table->enum('type', ['work', 'personal', 'other'])->default('work');
            $table->timestamps();

            // Unicidad de email global
            $table->unique('email');
            $table->index('staff_id');
            $table->index('is_primary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_emails');
        Schema::dropIfExists('staff');
    }
};

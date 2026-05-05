<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->string('cpf')->unique()->nullable();
            $table->string('password');
            $table->boolean('is_admin')->default(false);
            $table->unsignedBigInteger('role_id')->nullable();
            $table->string('telefone')->nullable();
            $table->date('data_nascimento')->nullable();
            $table->string('sexo')->nullable();
            $table->string('endereco')->nullable();
            $table->string('login')->unique()->nullable();
            $table->string('nome_responsavel')->nullable();
            $table->string('cpf_responsavel')->nullable();
            $table->string('telefone_responsavel')->nullable();
            $table->string('faixa')->nullable();
            $table->integer('grau')->default(0);
            $table->decimal('peso', 5, 2)->nullable();
            $table->string('vencimento_mensalidade')->nullable();
            $table->boolean('possui_lesao')->default(false);
            $table->boolean('medicamento_continuo')->default(false);
            $table->boolean('problema_cardiaco')->default(false);
            $table->text('outros')->nullable();
            $table->text('descricao_lesao')->nullable();
            $table->text('descricao_medicamento')->nullable();
            $table->text('descricao_problema_cardiaco')->nullable();
            $table->foreignId('plan_id')->nullable()->constrained('plans')->nullOnDelete();
            $table->date('start_date')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->text('notes')->nullable();
            $table->string('status')->default('active');
            $table->string('user_status')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

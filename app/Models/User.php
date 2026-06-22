<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\BelongsToTenant;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, SoftDeletes, BelongsToTenant;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'cpf',
        'password',
        'is_admin',
        'role_id',
        'telefone',
        'data_nascimento',
        'sexo',
        'endereco',
        'login',
        'nome_responsavel',
        'cpf_responsavel',
        'telefone_responsavel',
        'faixa',
        'grau',
        'peso',
        'vencimento_mensalidade',
        'possui_lesao',
        'medicamento_continuo',
        'problema_cardiaco',
        'outros',
        'descricao_lesao',
        'descricao_medicamento',
        'descricao_problema_cardiaco',
        'plan_id',
        'start_date',
        'emergency_contact_name',
        'emergency_contact_phone',
        'notes',
        'status',
        'user_status',
        'photo',
        'custom_price',
        'asaas_customer_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'data_nascimento' => 'date',
            'start_date' => 'date',
        ];
    }

    public function systemRole()
    {
        return $this->belongsTo(\Spatie\Permission\Models\Role::class, 'role_id');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class)->withTrashed();
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Compatibility Accessors
     */
    public function getBeltAttribute() { return $this->faixa; }
    public function getPhoneAttribute() { return $this->telefone; }
    public function getBirthDateAttribute() { return $this->data_nascimento; }
    public function getDueDayAttribute() { return $this->vencimento_mensalidade; }
}

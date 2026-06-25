<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        if (auth()->user()->hasRole('aluno')) {
            return redirect()->route('portal.dashboard');
        }
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
})->name('home');

// Webhook Asaas
Route::post('/webhooks/asaas', [\App\Http\Controllers\Webhooks\AsaasWebhookController::class, 'handle'])->name('webhooks.asaas');

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->middleware('throttle:5,1')->name('login.post');
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Password Reset Routes
Route::get('/forgot-password', [\App\Http\Controllers\PasswordResetController::class, 'create'])->middleware('guest')->name('password.request');
Route::post('/forgot-password', [\App\Http\Controllers\PasswordResetController::class, 'store'])->middleware('guest')->name('password.email');
Route::get('/reset-password/{token}', [\App\Http\Controllers\PasswordResetController::class, 'edit'])->middleware('guest')->name('password.reset');
Route::post('/reset-password', [\App\Http\Controllers\PasswordResetController::class, 'update'])->middleware('guest')->name('password.update');

// Portal do Aluno
Route::group(['prefix' => 'portal', 'as' => 'portal.'], function () {
    Route::get('/login', [\App\Http\Controllers\PortalLoginController::class, 'showLoginForm'])->middleware('guest')->name('login');
    Route::post('/login', [\App\Http\Controllers\PortalLoginController::class, 'login'])->middleware('guest', 'throttle:5,1')->name('login.post');

    // Recuperação de Senha do Aluno
    Route::get('/forgot-password', [\App\Http\Controllers\PortalPasswordResetController::class, 'create'])->middleware('guest')->name('password.request');
    Route::post('/forgot-password', [\App\Http\Controllers\PortalPasswordResetController::class, 'store'])->middleware('guest')->name('password.email');
    Route::get('/reset-password/{token}', [\App\Http\Controllers\PortalPasswordResetController::class, 'edit'])->middleware('guest')->name('password.reset');
    Route::post('/reset-password', [\App\Http\Controllers\PortalPasswordResetController::class, 'update'])->middleware('guest')->name('password.update');

    // Áreas protegidas
    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\PortalAlunoController::class, 'dashboard'])->name('dashboard');
        Route::post('/checkin', [\App\Http\Controllers\PortalAlunoController::class, 'checkIn'])->name('checkin');
        Route::get('/payments', [\App\Http\Controllers\PortalAlunoController::class, 'payments'])->name('payments.index');
        Route::post('/change-password', [\App\Http\Controllers\PortalAlunoController::class, 'changePassword'])->name('change-password');
        Route::post('/update-photo', [\App\Http\Controllers\PortalAlunoController::class, 'updatePhoto'])->name('update-photo');
    });
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('can:view-dashboard')->name('dashboard');

    // Users
    Route::middleware(['can:manage-users'])->group(function () {
        Route::get('/users/import', [\App\Http\Controllers\StudentImportController::class, 'show'])->name('users.import');
        Route::post('/users/import', [\App\Http\Controllers\StudentImportController::class, 'store'])->name('users.import.store');
        Route::resource('users', UserController::class);
    });

    // Payments
    Route::middleware(['can:manage-finance'])->group(function () {
        Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
        Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');
        Route::post('/payments/generate', [PaymentController::class, 'generateBilling'])->name('billing.generate');
        Route::get('/payments/open-by-user/{user}', [PaymentController::class, 'getOpenPaymentsByUser'])->name('payments.open-by-user');
        Route::get('/users/{user}/payments', [PaymentController::class, 'userHistory'])->name('payments.user-history');
        Route::delete('/payments/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy');
    });

    // Attendance
    Route::middleware(['can:manage-attendance'])->group(function () {
        Route::get('/attendances', [AttendanceController::class, 'index'])->name('attendances.index');
        Route::post('/attendances', [AttendanceController::class, 'store'])->name('attendances.store');
        Route::post('/attendances/bulk', [AttendanceController::class, 'bulkStore'])->name('attendances.bulk');
        Route::delete('/attendances', [AttendanceController::class, 'destroy'])->name('attendances.destroy');
    });

    // Plans
    Route::middleware(['can:manage-plans'])->group(function () {
        Route::get('/plans', [PlanController::class, 'index'])->name('plans.index');
        Route::post('/plans', [PlanController::class, 'store'])->name('plans.store');
        Route::put('/plans/{plan}', [PlanController::class, 'update'])->name('plans.update');
        Route::delete('/plans/{plan}', [PlanController::class, 'destroy'])->name('plans.destroy');
    });

    // Reports
    Route::middleware(['can:view-reports'])->group(function () {
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/monthly', [ReportController::class, 'monthly'])->name('reports.monthly');
        Route::get('/reports/delinquency', [ReportController::class, 'delinquency'])->name('reports.delinquency');
    });

    // Settings
    Route::middleware(['can:manage-settings'])->group(function () {
        Route::get('/settings', [\App\Http\Controllers\TenantSettingsController::class, 'index'])->name('settings.index');
        Route::put('/settings', [\App\Http\Controllers\TenantSettingsController::class, 'update'])->name('settings.update');
    });

    // System Kill Switch (Root Only)
    Route::post('/system/toggle-lock', [\App\Http\Controllers\Root\SystemController::class, 'toggleLock'])
        ->middleware('role:root')
        ->name('system.toggle-lock');

    // Root/Super-Admin Management
    Route::middleware(['role:root'])->prefix('root')->as('root.')->group(function () {
        Route::resource('tenants', \App\Http\Controllers\Root\TenantManagementController::class);
    });
});

// Rota temporária para depurar e corrigir o usuário Root e banco em produção (Railway)
Route::get('/debug-root-user', function () {
    try {
        // 1. Executa as migrations pendentes em produção
        \Artisan::call('migrate', ['--force' => true]);
        $migrationStatus = "Migrations executadas!";

        // 2. Limpa o cache de permissões do Spatie em produção
        \Artisan::call('permission:cache-reset');
        $cacheStatus = "Cache de permissões limpo!";

        // 3. Busca o usuário pelo CPF
        $user = \App\Models\User::withoutGlobalScope(\App\Scopes\TenantScope::class)
            ->where('cpf', '04314745169')
            ->first();

        if (!$user) {
            return response()->json([
                'error' => 'Usuário com o CPF 04314745169 não foi encontrado no banco de dados do Railway.',
                'migrations' => $migrationStatus,
                'cache' => $cacheStatus
            ]);
        }

        // 4. Garante que a role 'root' existe e atribui ao usuário
        $rootRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'root', 'guard_name' => 'web']);
        
        $rolesBefore = $user->getRoleNames()->toArray();
        $user->assignRole($rootRole);
        $user->load('roles');
        $rolesAfter = $user->getRoleNames()->toArray();

        // 5. Verifica se as colunas da tabela 'tenants' existem
        $columns = \Schema::getColumnListing('tenants');

        return response()->json([
            'message' => 'Processo de depuração concluído com sucesso!',
            'migrations' => $migrationStatus,
            'cache' => $cacheStatus,
            'tenants_table_columns' => $columns,
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'tenant_id' => $user->tenant_id,
                'roles_before' => $rolesBefore,
                'roles_after' => $rolesAfter,
                'has_root_role_now' => $user->hasRole('root') ? 'SIM' : 'NÃO',
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => array_slice(explode("\n", $e->getTraceAsString()), 0, 15)
        ], 500);
    }
});

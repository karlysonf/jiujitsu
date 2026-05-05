<?php

namespace App\Http\Controllers\Root;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SystemController extends Controller
{
    /**
     * Alterna o estado de bloqueio do sistema no Cache.
     * Somente usuários root têm permissão de acesso via middleware.
     */
    public function toggleLock()
    {
        abort_unless(auth()->user()->hasRole('root'), 403, 'Apenas o usuário root pode realizar esta ação.');

        $currentStatus = Cache::get('system_locked', false);
        $newStatus = !$currentStatus;

        Cache::forever('system_locked', $newStatus);

        $message = $newStatus 
            ? 'Sistema BLOQUEADO com sucesso! Usuários comuns não conseguirão acessar.' 
            : 'Sistema DESBLOQUEADO com sucesso! O acesso foi normalizado.';

        return back()->with($newStatus ? 'warning' : 'success', $message);
    }
}

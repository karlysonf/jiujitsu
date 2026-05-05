@extends('layouts.app')

@section('content')
<div style="margin-bottom: 2.5rem;">
    <h1 style="margin-bottom: 0.5rem;">Controle de Presença</h1>
    <p>Registre a presença dos alunos nos treinos de hoje: <strong>{{ now()->format('d/m/Y') }}</strong></p>
</div>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
        <h3 style="margin-bottom: 0;">Lista de Alunos Ativos</h3>
        <div style="font-size: 0.875rem; padding: 0.5rem 1rem; background: var(--primary-light); color: var(--primary); border-radius: 2rem; font-weight: 600;">
            <i class="fas fa-user-check"></i> {{ $attendances->count() }} presentes hoje
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.25rem;">
        @foreach($users as $user)
            @php
                $hasAttended = $attendances->where('user_id', $user->id)->isNotEmpty();
            @endphp
            <div class="card" style="padding: 1rem; border-color: {{ $hasAttended ? 'var(--secondary)' : '#E2E8F0' }}; display: flex; align-items: center; justify-content: space-between; transition: var(--transition-fast);">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div style="width: 40px; height: 40px; border-radius: 50%; background: {{ $hasAttended ? '#DCFCE7' : '#F1F5F9' }}; display: flex; align-items: center; justify-content: center; color: {{ $hasAttended ? '#166534' : '#94A3B8' }};">
                        <i class="fas fa-user"></i>
                    </div>
                    <div>
                        <div style="font-weight: 700; font-size: 0.9375rem; color: var(--text-main);">{{ $user->name }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $user->belt }}</div>
                    </div>
                </div>

                @if($hasAttended)
                    <div style="color: var(--secondary); font-size: 1.5rem;">
                        <i class="fas fa-check-circle"></i>
                    </div>
                @else
                    <form action="{{ route('attendances.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                        <input type="hidden" name="date" value="{{ $date }}">
                        <button type="submit" class="btn" style="height: 40px; width: 40px; padding: 0; background: var(--primary-light); color: var(--primary); border-radius: 50%;">
                            <i class="fas fa-plus"></i>
                        </button>
                    </form>
                @endif
            </div>
        @endforeach
    </div>
</div>
@endsection

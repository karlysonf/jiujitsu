@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto text-center mt-10">
    <h1 class="text-3xl font-bold mb-2">Bem-vindo, João Silva</h1>
    <p class="text-gray-600 mb-8">Faixa Azul</p>

    <div class="bg-white p-8 rounded-lg shadow-md border border-gray-200">
        <h2 class="text-xl font-semibold mb-4">Treino de Hoje</h2>
        <p class="mb-6 text-gray-500">Registre sua presença para a aula de hoje.</p>
        
        <form action="#" method="POST">
            <button type="button" class="bg-green-600 hover:bg-green-700 text-white font-bold py-4 px-8 rounded-full shadow-lg transform transition hover:scale-105 text-xl w-full">
                🥊 Fazer Check-in Agora
            </button>
        </form>
    </div>

    <div class="mt-8 text-left">
        <a href="#" class="text-blue-600 hover:underline inline-flex items-center">
            &rarr; Acessar meu histórico financeiro
        </a>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="w-full bg-white p-6 rounded-lg shadow-md border border-gray-200">
    <div class="flex justify-between items-center mb-6 border-b pb-2">
        <h2 class="text-2xl font-bold">Controle Financeiro</h2>
        <input type="month" class="border rounded p-2">
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white">
            <thead>
                <tr class="bg-gray-800 text-white uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">Aluno</th>
                    <th class="py-3 px-6 text-left">Telefone</th>
                    <th class="py-3 px-6 text-center">Mensalidade</th>
                    <th class="py-3 px-6 text-center">Status</th>
                    <th class="py-3 px-6 text-center">Ações</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-3 px-6 text-left font-bold">Pedro Alvarez</td>
                    <td class="py-3 px-6 text-left">(11) 99999-9999</td>
                    <td class="py-3 px-6 text-center">R$ 150,00</td>
                    <td class="py-3 px-6 text-center">
                        <span class="bg-red-200 text-red-600 py-1 px-3 rounded-full text-xs">Pendente</span>
                    </td>
                    <td class="py-3 px-6 text-center">
                        <button class="bg-blue-500 text-white px-3 py-1 rounded text-xs hover:bg-blue-600">Marcar como Pago</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
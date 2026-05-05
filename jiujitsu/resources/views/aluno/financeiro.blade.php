@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-6 rounded-lg shadow-md border border-gray-200">
    <h2 class="text-2xl font-bold mb-6">Meu Financeiro</h2>

    <table class="min-w-full bg-white border">
        <thead>
            <tr class="bg-gray-50 text-gray-600 uppercase text-sm leading-normal">
                <th class="py-3 px-6 text-left border-b">Mês Referência</th>
                <th class="py-3 px-6 text-left border-b">Valor</th>
                <th class="py-3 px-6 text-center border-b">Status</th>
            </tr>
        </thead>
        <tbody class="text-gray-600 text-sm font-light">
            <tr class="border-b border-gray-200 hover:bg-gray-100">
                <td class="py-3 px-6 text-left whitespace-nowrap font-medium">Outubro / 2023</td>
                <td class="py-3 px-6 text-left">R$ 150,00</td>
                <td class="py-3 px-6 text-center">
                    <span class="bg-green-200 text-green-600 py-1 px-3 rounded-full text-xs">Pago</span>
                </td>
            </tr>
            <tr class="border-b border-gray-200 hover:bg-gray-100">
                <td class="py-3 px-6 text-left whitespace-nowrap font-medium">Novembro / 2023</td>
                <td class="py-3 px-6 text-left">R$ 150,00</td>
                <td class="py-3 px-6 text-center">
                    <span class="bg-red-200 text-red-600 py-1 px-3 rounded-full text-xs">Pendente</span>
                </td>
            </tr>
        </tbody>
    </table>
</div>
@endsection
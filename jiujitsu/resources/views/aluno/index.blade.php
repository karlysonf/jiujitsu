@extends('layout')

@section('content')
<div class="w-full">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Gestão de Alunos</h1>
            <p class="text-gray-500 mt-1 text-sm">Gerencie os alunos matriculados e suas graduações</p>
        </div>
        <div class="text-sm text-gray-500 flex items-center space-x-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            <span>Início</span>
            <span>></span>
            <span>Cadastros</span>
            <span>></span>
            <span class="text-slate-800 font-semibold">Alunos</span>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        
        <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-100">
            <div>
                <h2 class="text-xl font-bold text-slate-800">Alunos Cadastrados</h2>
            </div>
            
            <div class="flex space-x-3">
                <a href="#" class="bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 px-4 py-2 rounded text-sm font-semibold transition-colors flex items-center shadow-sm">
                    <span class="mr-2">←</span> Voltar
                </a>
                <a href="#" class="bg-slate-900 hover:bg-slate-800 text-white px-4 py-2 rounded text-sm font-semibold transition-colors flex items-center shadow-sm">
                    <span class="mr-2">+</span> Novo Aluno
                </a>
            </div>
        </div>

        <div class="flex justify-between items-center mb-4 text-sm text-gray-600">
            <div class="flex items-center">
                <span>Exibir</span>
                <select class="mx-2 border border-gray-300 rounded px-2 py-1 focus:outline-none focus:border-slate-500 bg-white">
                    <option>10</option>
                    <option>25</option>
                    <option>50</option>
                </select>
                <span>resultados</span>
            </div>
            <div class="flex items-center relative">
                <input type="text" placeholder="Pesquisar aluno..." class="border border-gray-300 rounded-md pl-3 pr-10 py-1.5 focus:outline-none focus:border-slate-800 focus:ring-1 focus:ring-slate-800 w-64 text-sm">
                <svg class="w-4 h-4 text-gray-400 absolute right-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-900 text-white uppercase text-xs tracking-wider font-bold">
                        <th class="py-3 px-4 rounded-tl-sm">ALUNO</th>
                        <th class="py-3 px-4">FAIXA</th>
                        <th class="py-3 px-4">TELEFONE</th>
                        <th class="py-3 px-4">MENSALIDADE</th>
                        <th class="py-3 px-4 text-center">STATUS</th>
                        <th class="py-3 px-4 text-center rounded-tr-sm">AÇÕES</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700">
                    
                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                        <td class="py-3 px-4 font-semibold text-slate-800">Pedro Alvarez</td>
                        <td class="py-3 px-4">Branca</td>
                        <td class="py-3 px-4">(11) 99999-9999</td>
                        <td class="py-3 px-4">R$ 150,00</td>
                        <td class="py-3 px-4 text-center">
                            <span class="bg-red-100 text-red-600 px-3 py-1 rounded-full text-xs font-semibold">
                                Pendente
                            </span>
                        </td>
                        <td class="py-3 px-4 text-center">
                            <div class="flex justify-center space-x-2">
                                <button class="text-blue-600 hover:text-blue-800 p-1" title="Editar">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </button>
                                <button class="text-red-500 hover:text-red-700 p-1" title="Excluir">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                        <td class="py-3 px-4 font-semibold text-slate-800">João Silva</td>
                        <td class="py-3 px-4">Azul</td>
                        <td class="py-3 px-4">(11) 98888-8888</td>
                        <td class="py-3 px-4">R$ 150,00</td>
                        <td class="py-3 px-4 text-center">
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                                Ativo
                            </span>
                        </td>
                        <td class="py-3 px-4 text-center">
                            <div class="flex justify-center space-x-2">
                                <button class="text-blue-600 hover:text-blue-800 p-1" title="Editar">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </button>
                                <button class="text-red-500 hover:text-red-700 p-1" title="Excluir">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
        
        <div class="mt-4 flex items-center justify-between text-sm text-gray-500">
            <div>Mostrando 1 a 2 de 2 resultados</div>
            <div class="flex space-x-1">
                <button class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-50 cursor-not-allowed text-gray-400">Anterior</button>
                <button class="px-3 py-1 bg-slate-900 text-white rounded">1</button>
                <button class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-50">Próximo</button>
            </div>
        </div>

    </div>
</div>
@endsection
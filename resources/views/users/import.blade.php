@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-8">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 dark:text-slate-50 leading-tight">Importar Alunos via CSV</h1>
            <p class="text-sm text-slate-500 mt-1">Adicione vários alunos de uma vez importando uma planilha formatada.</p>
        </div>
        <a href="{{ route('users.index') }}" class="px-4 py-2 border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 rounded-xl text-sm font-semibold hover:bg-slate-50 dark:hover:bg-slate-800 transition-all">
            Voltar
        </a>
    </div>

    <!-- Instructions Card -->
    <div class="bg-white dark:bg-slate-900 shadow-md rounded-2xl p-6 border border-slate-200 dark:border-slate-800 mb-8">
        <h3 class="text-lg font-bold text-slate-900 dark:text-slate-50 mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary text-xl">info</span>
            Como formatar a planilha:
        </h3>
        <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">
            Sua planilha deve conter um cabeçalho identificando as colunas. As colunas suportadas são:
        </p>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-xs font-semibold text-slate-700 dark:text-slate-300 mb-6">
            <div class="bg-slate-50 dark:bg-slate-950 p-3 rounded-lg border border-slate-100 dark:border-slate-800">
                <span class="text-slate-400 block mb-1">Obrigatório</span>
                nome (ou name)
            </div>
            <div class="bg-slate-50 dark:bg-slate-950 p-3 rounded-lg border border-slate-100 dark:border-slate-800">
                <span class="text-slate-400 block mb-1">Opcional</span>
                email
            </div>
            <div class="bg-slate-50 dark:bg-slate-950 p-3 rounded-lg border border-slate-100 dark:border-slate-800">
                <span class="text-slate-400 block mb-1">Opcional</span>
                cpf
            </div>
            <div class="bg-slate-50 dark:bg-slate-950 p-3 rounded-lg border border-slate-100 dark:border-slate-800">
                <span class="text-slate-400 block mb-1">Opcional</span>
                telefone (ou phone)
            </div>
            <div class="bg-slate-50 dark:bg-slate-950 p-3 rounded-lg border border-slate-100 dark:border-slate-800">
                <span class="text-slate-400 block mb-1">Opcional</span>
                data_nascimento
            </div>
            <div class="bg-slate-50 dark:bg-slate-950 p-3 rounded-lg border border-slate-100 dark:border-slate-800">
                <span class="text-slate-400 block mb-1">Opcional (Padrão: Branca)</span>
                faixa (ou belt)
            </div>
            <div class="bg-slate-50 dark:bg-slate-950 p-3 rounded-lg border border-slate-100 dark:border-slate-800">
                <span class="text-slate-400 block mb-1">Opcional (Padrão: 0)</span>
                grau (ou grade)
            </div>
        </div>

        <div class="bg-blue-50 dark:bg-blue-950/30 border border-blue-200 dark:border-blue-900 rounded-xl p-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="material-symbols-outlined text-blue-600">download</span>
                <div>
                    <h4 class="text-sm font-bold text-blue-900 dark:text-blue-200">Modelo de Planilha</h4>
                    <p class="text-xs text-blue-700 dark:text-blue-300">Baixe um modelo pronto para preencher.</p>
                </div>
            </div>
            <a href="data:text/csv;charset=utf-8,nome,email,cpf,telefone,data_nascimento,faixa,grau%0AJão%20Silva,joao@email.com,12345678901,11999998888,1990-05-15,Branca,1" download="modelo_alunos.csv" class="bg-white dark:bg-slate-800 border border-blue-300 dark:border-blue-800 text-blue-700 dark:text-blue-300 px-4 py-2 rounded-xl text-xs font-bold shadow-sm hover:bg-blue-50 dark:hover:bg-slate-700 transition-all">
                Baixar CSV
            </a>
        </div>
    </div>

    <!-- Upload Form -->
    <form action="{{ route('users.import.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="bg-white dark:bg-slate-900 shadow-md rounded-2xl p-8 border border-slate-200 dark:border-slate-800">
            <!-- Drag and drop zone -->
            <div id="drop-zone" class="border-2 border-dashed border-slate-300 dark:border-slate-700 rounded-2xl p-8 text-center hover:border-primary transition-all cursor-pointer bg-slate-50/50 dark:bg-slate-950/20">
                <input type="file" name="file" id="file" accept=".csv,text/csv" class="hidden" required onchange="handleFileSelect(event)">
                
                <span class="material-symbols-outlined text-slate-400 text-5xl mb-4">upload_file</span>
                <h3 class="text-base font-bold text-slate-950 dark:text-slate-50 mb-1">Arraste seu arquivo CSV aqui</h3>
                <p class="text-xs text-slate-400 mb-4">ou clique para navegar no seu computador</p>
                
                <div id="file-info" class="hidden max-w-sm mx-auto bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-3 flex items-center justify-between shadow-sm animate-pulse">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">csv</span>
                        <span id="file-name" class="text-xs font-semibold text-slate-700 dark:text-slate-300 truncate"></span>
                    </div>
                    <button type="button" onclick="clearFile(event)" class="text-slate-400 hover:text-red-500">
                        <span class="material-symbols-outlined text-sm">close</span>
                    </button>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="mt-8 flex justify-end">
                <button type="submit" class="w-full bg-primary text-white py-3.5 rounded-xl font-semibold shadow-md hover:opacity-90 active:scale-95 transition-all text-sm flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-base">publish</span>
                    Iniciar Importação
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    const dropZone = document.getElementById('drop-zone');
    const fileInput = document.getElementById('file');
    const fileInfo = document.getElementById('file-info');
    const fileName = document.getElementById('file-name');

    dropZone.addEventListener('click', () => fileInput.click());

    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('border-primary', 'bg-slate-100/50');
    });

    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('border-primary', 'bg-slate-100/50');
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-primary', 'bg-slate-100/50');
        
        if (e.dataTransfer.files.length) {
            fileInput.files = e.dataTransfer.files;
            displayFileInfo(e.dataTransfer.files[0].name);
        }
    });

    function handleFileSelect(event) {
        if (event.target.files.length) {
            displayFileInfo(event.target.files[0].name);
        }
    }

    function displayFileInfo(name) {
        fileName.textContent = name;
        fileInfo.classList.remove('hidden');
    }

    function clearFile(event) {
        event.stopPropagation();
        fileInput.value = '';
        fileInfo.classList.add('hidden');
    }
</script>
@endsection

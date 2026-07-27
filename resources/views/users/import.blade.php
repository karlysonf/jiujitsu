@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-6">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 text-xs font-bold uppercase tracking-wider mb-2">
                <span class="w-2 h-2 rounded-full bg-cyan-400 animate-pulse"></span>
                Importação em Lote
            </div>
            <h1 class="font-['Outfit'] font-black text-2xl md:text-3xl text-white">Importar Alunos via CSV</h1>
            <p class="text-xs text-slate-400 mt-0.5">Adicione vários alunos de uma só vez com uma planilha formatada.</p>
        </div>
        <a href="{{ route('users.index') }}" class="px-4 py-2 border border-white/10 bg-[#182234] text-white rounded-xl text-xs font-bold hover:bg-white/10 transition-all">
            Voltar
        </a>
    </div>

    <!-- Instructions Card -->
    <div class="bg-[#111726] shadow-xl rounded-2xl p-6 border border-white/10 mb-6">
        <h3 class="text-base font-['Outfit'] font-bold text-white mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-cyan-400 text-xl">info</span>
            Como formatar a planilha:
        </h3>
        <p class="text-xs text-slate-400 mb-4">
            Sua planilha deve conter um cabeçalho identificando as colunas. As colunas suportadas são:
        </p>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-xs font-semibold text-slate-300 mb-6">
            <div class="bg-[#182234] p-3 rounded-xl border border-white/5">
                <span class="text-rose-400 block mb-1 text-[10px] uppercase font-bold">Obrigatorio</span>
                nome (ou name)
            </div>
            <div class="bg-[#182234] p-3 rounded-xl border border-white/5">
                <span class="text-slate-500 block mb-1 text-[10px] uppercase font-bold">Opcional</span>
                email
            </div>
            <div class="bg-[#182234] p-3 rounded-xl border border-white/5">
                <span class="text-slate-500 block mb-1 text-[10px] uppercase font-bold">Opcional</span>
                cpf
            </div>
            <div class="bg-[#182234] p-3 rounded-xl border border-white/5">
                <span class="text-slate-500 block mb-1 text-[10px] uppercase font-bold">Opcional</span>
                telefone
            </div>
            <div class="bg-[#182234] p-3 rounded-xl border border-white/5">
                <span class="text-slate-500 block mb-1 text-[10px] uppercase font-bold">Opcional</span>
                data_nascimento
            </div>
            <div class="bg-[#182234] p-3 rounded-xl border border-white/5">
                <span class="text-slate-500 block mb-1 text-[10px] uppercase font-bold">Padrão: Branca</span>
                faixa
            </div>
            <div class="bg-[#182234] p-3 rounded-xl border border-white/5">
                <span class="text-slate-500 block mb-1 text-[10px] uppercase font-bold">Padrão: 0</span>
                grau
            </div>
        </div>

        <div class="bg-cyan-500/10 border border-cyan-500/30 rounded-xl p-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="material-symbols-outlined text-cyan-400">download</span>
                <div>
                    <h4 class="text-xs font-bold text-white">Modelo de Planilha</h4>
                    <p class="text-[11px] text-cyan-300">Baixe um modelo pronto para preencher.</p>
                </div>
            </div>
            <a href="data:text/csv;charset=utf-8,nome,email,cpf,telefone,data_nascimento,faixa,grau%0AJão%20Silva,joao@email.com,12345678901,11999998888,1990-05-15,Branca,1" download="modelo_alunos.csv" class="bg-[#182234] border border-cyan-500/30 text-cyan-400 px-4 py-2 rounded-xl text-xs font-bold shadow-sm hover:bg-white/10 transition-all">
                Baixar CSV
            </a>
        </div>
    </div>

    <!-- Upload Form -->
    <form action="{{ route('users.import.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="bg-[#111726] shadow-xl rounded-2xl p-6 md:p-8 border border-white/10">
            <!-- Drag and drop zone -->
            <div id="drop-zone" class="border-2 border-dashed border-white/20 rounded-2xl p-8 text-center hover:border-rose-500 transition-all cursor-pointer bg-[#090d16]/50">
                <input type="file" name="file" id="file" accept=".csv,text/csv" class="hidden" required onchange="handleFileSelect(event)">
                
                <span class="material-symbols-outlined text-rose-500 text-5xl mb-3">upload_file</span>
                <h3 class="text-base font-['Outfit'] font-bold text-white mb-1">Arraste seu arquivo CSV aqui</h3>
                <p class="text-xs text-slate-400 mb-4">ou clique para navegar no seu computador</p>
                
                <div id="file-info" class="hidden max-w-sm mx-auto bg-[#182234] border border-white/10 rounded-xl p-3 flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-rose-400">csv</span>
                        <span id="file-name" class="text-xs font-semibold text-white truncate"></span>
                    </div>
                    <button type="button" onclick="clearFile(event)" class="text-slate-400 hover:text-rose-400">
                        <span class="material-symbols-outlined text-sm">close</span>
                    </button>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="mt-6 flex justify-end">
                <button type="submit" class="w-full bg-gradient-to-r from-rose-600 to-rose-700 text-white py-3.5 rounded-xl font-['Outfit'] font-bold shadow-lg hover:shadow-rose-600/30 active:scale-95 transition-all text-sm flex items-center justify-center gap-2">
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
        dropZone.classList.add('border-rose-500', 'bg-rose-500/5');
    });

    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('border-rose-500', 'bg-rose-500/5');
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-rose-500', 'bg-rose-500/5');
        
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

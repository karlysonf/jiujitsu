<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\FaceRecognitionService;
use Illuminate\Console\Command;

class GenerateFaceEmbeddings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'face:generate-embeddings {--force : Sobrescrever embeddings já existentes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Extrai e salva os embeddings faciais de todos os usuários cadastrados que possuem foto.';

    /**
     * Execute the console command.
     */
    public function handle(FaceRecognitionService $faceService): int
    {
        $force = $this->option('force');

        $query = User::whereNotNull('photo')->where('photo', '!=', '');
        if (!$force) {
            $query->whereNull('face_embedding');
        }

        $users = $query->get();

        if ($users->isEmpty()) {
            $this->info('Nenhum usuário necessitando de geração de embedding facial.');
            return Command::SUCCESS;
        }

        $this->info("Iniciando geração de embeddings faciais para {$users->count()} usuários...");
        $bar = $this->output->createProgressBar($users->count());
        $bar->start();

        $successCount = 0;
        $failCount = 0;

        foreach ($users as $user) {
            $success = $faceService->extractAndSaveEmbedding($user);
            if ($success) {
                $successCount++;
            } else {
                $failCount++;
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("✅ Concluído! Sucessos: {$successCount} | Falhas/Sem rosto: {$failCount}");

        return Command::SUCCESS;
    }
}

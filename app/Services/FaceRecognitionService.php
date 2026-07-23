<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FaceRecognitionService
{
    protected string $faceServiceUrl;

    public function __construct()
    {
        $this->faceServiceUrl = env('FACE_SERVICE_URL', 'http://127.0.0.1:8002');
    }

    /**
     * Extrai o embedding facial da foto de perfil de um usuário e salva no banco de dados.
     */
    public function extractAndSaveEmbedding(User $user): bool
    {
        if (!$user->photo) {
            return false;
        }

        $photoPath = storage_path('app/public/' . $user->photo);
        if (!file_exists($photoPath)) {
            Log::warning("Foto do usuário {$user->id} não encontrada em: {$photoPath}");
            return false;
        }

        try {
            $url = rtrim($this->faceServiceUrl, '/') . '/extract-embedding';
            
            $response = Http::timeout(30)->attach(
                'photo', file_get_contents($photoPath), basename($photoPath)
            )->post($url);

            if ($response->successful()) {
                $result = $response->json();
                if (!empty($result['success']) && !empty($result['embedding'])) {
                    $user->update([
                        'face_embedding' => $result['embedding']
                    ]);
                    Log::info("Embedding facial atualizado com sucesso para usuário #{$user->id}");
                    return true;
                } else {
                    Log::warning("Falha ao extrair embedding para usuário #{$user->id}: " . ($result['message'] ?? 'Desconhecido'));
                }
            } else {
                Log::error("Erro no microsserviço ao extrair embedding (Status {$response->status()}): " . $response->body());
            }
        } catch (\Exception $e) {
            Log::error("Exceção ao extrair embedding do usuário #{$user->id}: " . $e->getMessage());
        }

        return false;
    }

    /**
     * Processa a foto do grupo e identifica os alunos presentes.
     */
    public function recognizeGroupPhoto(UploadedFile $groupPhoto, $users): array
    {
        $referenceData = [];
        $hasReferenceData = false;

        foreach ($users as $user) {
            // 1. Usa o embedding pré-calculado se disponível
            if (!empty($user->face_embedding) && is_array($user->face_embedding)) {
                $referenceData[] = [
                    'id' => $user->id,
                    'embedding' => $user->face_embedding
                ];
                $hasReferenceData = true;
            } 
            // 2. Fallback: Foto base64 se ainda não tiver embedding extraído
            elseif ($user->photo) {
                $photoPath = storage_path('app/public/' . $user->photo);
                if (file_exists($photoPath)) {
                    $data = base64_encode(file_get_contents($photoPath));
                    $referenceData[] = [
                        'id' => $user->id,
                        'image_base64' => $data
                    ];
                    $hasReferenceData = true;
                }
            }
        }

        if (!$hasReferenceData) {
            return [
                'success' => false,
                'message' => 'Nenhum aluno ativo possui foto ou vetor facial de referência cadastrado.'
            ];
        }

        try {
            $url = rtrim($this->faceServiceUrl, '/') . '/recognize';

            $response = Http::timeout(60)->attach(
                'group_photo', file_get_contents($groupPhoto->getRealPath()), $groupPhoto->getClientOriginalName()
            )->attach(
                'reference_data', json_encode($referenceData), 'reference.json'
            )->post($url);

            if ($response->successful()) {
                $result = $response->json();
                $identifiedIds = $result['identified_ids'] ?? [];

                if (is_array($identifiedIds)) {
                    $validIds = $users->pluck('id')->toArray();
                    $identifiedIds = array_intersect($identifiedIds, $validIds);

                    return [
                        'success' => true,
                        'identified_ids' => array_values($identifiedIds),
                        'simulation' => false
                    ];
                }
            }

            Log::error('Resposta inválida do microsserviço de face: ' . $response->body());
            return [
                'success' => false,
                'message' => 'Erro na resposta do microsserviço de reconhecimento (Status ' . $response->status() . ').'
            ];
        } catch (\Exception $e) {
            Log::error('Exceção no reconhecimento facial: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Erro de comunicação com o microsserviço Python: ' . $e->getMessage()
            ];
        }
    }
}

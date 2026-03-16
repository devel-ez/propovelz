<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePropostaRequest;
use App\Http\Resources\PropostaResource;
use App\Models\Proposta;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PropostaController extends Controller
{
    /**
     * Lista todas as propostas, filtradas por tenant.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $tenantId = $request->user()->tenant_id ?? 1; // Fallback para MVP SaaS Auth

        // Carrega propostas com o cliente relacionado para evitar N+1 query.
        $propostas = Proposta::with('cliente')
            ->where('tenant_id', $tenantId)
            ->paginate(15);
            
        return PropostaResource::collection($propostas);
    }

    /**
     * Cria (store) uma nova proposta.
     */
    public function store(StorePropostaRequest $request): PropostaResource
    {
        // A autorização e validação já ocorreram utilizando a Request (FormRequest).
        $validated = $request->validated();
        
        // Garantindo que a proposta criada terá o tenant_id associado corretamente
        $validated['tenant_id'] = $request->user()->tenant_id ?? 1;
        
        // Define status default na camada de inserção se não foi enviado
        $validated['status'] = $validated['status'] ?? 'rascunho';

        $proposta = Proposta::create($validated);

        // Retorna a representação do Resource carregando o Cliente
        return new PropostaResource($proposta->load('cliente'));
    }
}

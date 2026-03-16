<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PropostaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tenant_id' => $this->tenant_id,
            'titulo' => $this->titulo,
            'status' => $this->status,
            'valor_total' => $this->valor_total,
            'data_validade' => $this->data_validade?->format('Y-m-d'),
            'dados_faturamento' => $this->dados_faturamento,
            // Previne N+1, carrega os dados do cliente condicionalmente
            'cliente' => new ClienteResource($this->whenLoaded('cliente')),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}

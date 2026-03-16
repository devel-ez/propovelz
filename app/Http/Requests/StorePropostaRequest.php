<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePropostaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cliente_id'             => 'required|integer|exists:clientes,id',
            'titulo'                 => 'required|string|max:255',
            'conteudo'               => 'nullable|string',
            'status'                 => 'nullable|in:rascunho,enviada,visualizada,aprovada,recusada,cancelada',
            'data_validade'          => 'nullable|date',
            'itens'                  => 'nullable|array',
            'itens.*.descricao'      => 'required_with:itens|string|max:1000',
            'itens.*.quantidade'     => 'required_with:itens|numeric|min:0',
            'itens.*.valor_unitario' => 'required_with:itens|numeric|min:0',
        ];
    }
}

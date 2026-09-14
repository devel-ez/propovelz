<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\ClienteCredencial;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $clientes = Cliente::query()
            ->withCount('propostas')
            ->when($search, fn($q) => $q->where('nome', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%"))
            ->orderBy('nome')
            ->paginate(15)
            ->withQueryString();

        return view('clientes.index', compact('clientes', 'search'));
    }

    public function create()
    {
        return view('clientes.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome'     => 'required|string|max:255',
            'email'    => 'nullable|email|max:255',
            'telefone' => 'nullable|string|max:50',
            'documento' => 'nullable|string|max:20',
            'credenciais'              => 'nullable|array',
            'credenciais.*.sistema'   => 'required_with:credenciais|string|max:255',
            'credenciais.*.login'     => 'nullable|string|max:255',
            'credenciais.*.senha'     => 'nullable|string|max:255',
            'credenciais.*.observacao' => 'nullable|string|max:1000',
        ]);

        $cliente = Cliente::create([
            'tenant_id' => 1, // MVP: tenant fixo
            'nome'      => $data['nome'],
            'email'     => $data['email'] ?? null,
            'telefone'  => $data['telefone'] ?? null,
            'documento' => $data['documento'] ?? null,
        ]);

        foreach ($data['credenciais'] ?? [] as $cred) {
            if (empty($cred['sistema'])) continue;
            $cliente->credenciais()->create([
                'sistema'    => $cred['sistema'],
                'login'      => $cred['login'] ?? null,
                'senha'      => $cred['senha'] ?? null,
                'observacao' => $cred['observacao'] ?? null,
            ]);
        }

        return redirect()->route('clientes.index')
            ->with('success', "Cliente \"{$cliente->nome}\" criado com sucesso!");
    }

    public function show(Cliente $cliente)
    {
        $cliente->load(['credenciais', 'propostas', 'projetos', 'faturas']);

        // Somado em memoria: as faturas ja estao carregadas, entao nao vale
        // disparar tres consultas agregadas para calcular isso.
        $totalPago    = $cliente->faturas->where('pago', true)->sum('valor');
        $totalAberto  = $cliente->faturas->where('pago', false)->sum('valor');
        $totalVencido = $cliente->faturas
            ->where('pago', false)
            ->filter(fn ($f) => $f->vencimento && $f->vencimento->isPast())
            ->sum('valor');

        return view('clientes.show', compact('cliente', 'totalPago', 'totalAberto', 'totalVencido'));
    }

    public function edit(Cliente $cliente)
    {
        $cliente->load('credenciais');
        return view('clientes.edit', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente)
    {
        $data = $request->validate([
            'nome'     => 'required|string|max:255',
            'email'    => 'nullable|email|max:255',
            'telefone' => 'nullable|string|max:50',
            'documento' => 'nullable|string|max:20',
            'credenciais'              => 'nullable|array',
            'credenciais.*.id'        => 'nullable|integer',
            'credenciais.*.sistema'   => 'required_with:credenciais|string|max:255',
            'credenciais.*.login'     => 'nullable|string|max:255',
            'credenciais.*.senha'     => 'nullable|string|max:255',
            'credenciais.*.observacao' => 'nullable|string|max:1000',
        ]);

        $cliente->update([
            'nome'      => $data['nome'],
            'email'     => $data['email'] ?? null,
            'telefone'  => $data['telefone'] ?? null,
            'documento' => $data['documento'] ?? null,
        ]);

        // Sync credentials: delete all and recreate
        $idsToKeep = collect($data['credenciais'] ?? [])
            ->pluck('id')
            ->filter()
            ->values();

        $cliente->credenciais()->whereNotIn('id', $idsToKeep)->delete();

        foreach ($data['credenciais'] ?? [] as $cred) {
            if (empty($cred['sistema'])) continue;

            if (!empty($cred['id'])) {
                $credencial = ClienteCredencial::find($cred['id']);
                if ($credencial && $credencial->cliente_id === $cliente->id) {
                    $updateData = [
                        'sistema'    => $cred['sistema'],
                        'login'      => $cred['login'] ?? null,
                        'observacao' => $cred['observacao'] ?? null,
                    ];
                    // Only update senha if a new one was provided
                    if (!empty($cred['senha'])) {
                        $updateData['senha'] = $cred['senha'];
                    }
                    $credencial->update($updateData);
                }
            } else {
                $cliente->credenciais()->create([
                    'sistema'    => $cred['sistema'],
                    'login'      => $cred['login'] ?? null,
                    'senha'      => $cred['senha'] ?? null,
                    'observacao' => $cred['observacao'] ?? null,
                ]);
            }
        }

        return redirect()->route('clientes.index')
            ->with('success', "Cliente \"{$cliente->nome}\" atualizado com sucesso!");
    }

    public function destroy(Cliente $cliente)
    {
        $nome = $cliente->nome;
        $cliente->delete();

        return redirect()->route('clientes.index')
            ->with('success', "Cliente \"{$nome}\" excluído com sucesso!");
    }
}

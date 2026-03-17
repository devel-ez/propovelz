<?php

namespace App\Http\Controllers;

use App\Models\Projeto;
use App\Models\Cliente;
use App\Models\Proposta;
use Illuminate\Http\Request;

class ProjetoController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->input('search');

        $projetos = Projeto::with(['cliente'])
            ->when($search, function ($q) use ($search) {
                $q->where('nome', 'like', "%{$search}%")
                  ->orWhereHas('cliente', function ($q2) use ($search) {
                      $q2->where('nome', 'like', "%{$search}%");
                  });
            })
            ->latest()
            ->paginate(15);

        return view('projetos.index', compact('projetos', 'search'));
    }

    public function create()
    {
        $clientes = Cliente::orderBy('nome')->get();
        $propostas = Proposta::orderBy('titulo')->get();
        return view('projetos.create', compact('clientes', 'propostas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'cliente_id' => 'required|exists:clientes,id',
            'hospedagem_tipo' => 'nullable|in:Hospedagem externa,Hospedagem Veltech',
            'hospedagem_vigencia' => 'nullable|date',
            'dominio' => 'nullable|string|max:255',
            'dominio_vigencia' => 'nullable|date',
            'status' => 'required|string|max:50',
            'propostas' => 'nullable|array',
            'propostas.*' => 'exists:propostas,id'
        ]);

        $projeto = Projeto::create($validated);

        if ($request->has('propostas')) {
            $projeto->propostas()->sync($request->propostas);
        }

        return redirect()->route('projetos.show', $projeto)->with('success', 'Projeto criado com sucesso.');
    }

    public function show(Projeto $projeto)
    {
        $projeto->load(['cliente', 'propostas']);
        $clientes = Cliente::orderBy('nome')->get();
        $propostas = Proposta::orderBy('titulo')->get();

        $tarefas = $projeto->tarefas()->orderBy('ordem')->get();

        $kanban = [
            'Backlog' => $tarefas->where('status', 'Backlog')->values(),
            'Doing' => $tarefas->where('status', 'Doing')->values(),
            'Review' => $tarefas->where('status', 'Review')->values(),
            'Done' => $tarefas->where('status', 'Done')->values(),
        ];

        return view('projetos.show', compact('projeto', 'clientes', 'propostas', 'kanban'));
    }

    public function edit(Projeto $projeto)
    {
        $clientes = Cliente::orderBy('nome')->get();
        $propostas = Proposta::orderBy('titulo')->get();
        return view('projetos.edit', compact('projeto', 'clientes', 'propostas'));
    }

    public function update(Request $request, Projeto $projeto)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'cliente_id' => 'required|exists:clientes,id',
            'hospedagem_tipo' => 'nullable|in:Hospedagem externa,Hospedagem Veltech',
            'hospedagem_vigencia' => 'nullable|date',
            'dominio' => 'nullable|string|max:255',
            'dominio_vigencia' => 'nullable|date',
            'status' => 'required|string|max:50',
            'propostas' => 'nullable|array',
            'propostas.*' => 'exists:propostas,id'
        ]);

        $projeto->update($validated);

        if ($request->has('propostas')) {
            $projeto->propostas()->sync($request->propostas);
        } else {
            $projeto->propostas()->detach();
        }

        // Se veio do show (salvar edições rápidas no kanban/painel)
        if ($request->input('_redirect_to_show')) {
            return redirect()->route('projetos.show', $projeto)->with('success', 'Projeto atualizado com sucesso.');
        }

        return redirect()->route('projetos.index')->with('success', 'Projeto atualizado com sucesso.');
    }

    public function destroy(Projeto $projeto)
    {
        $projeto->delete();
        return redirect()->route('projetos.index')->with('success', 'Projeto excluído com sucesso.');
    }
}

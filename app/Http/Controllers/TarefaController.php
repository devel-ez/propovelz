<?php

namespace App\Http\Controllers;

use App\Models\Tarefa;
use App\Models\Projeto;
use Illuminate\Http\Request;

class TarefaController extends Controller
{
    public function store(Request $request, Projeto $projeto)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'status' => 'required|string|in:Backlog,Doing,Review,Done',
        ]);

        $validated['projeto_id'] = $projeto->id;
        $validated['ordem'] = Tarefa::where('projeto_id', $projeto->id)
                                    ->where('status', $validated['status'])
                                    ->max('ordem') + 1;

        $tarefa = Tarefa::create($validated);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Tarefa criada', 'tarefa' => $tarefa]);
        }

        return redirect()->back()->with('success', 'Tarefa adicionada com sucesso.');
    }

    public function update(Request $request, Projeto $projeto, Tarefa $tarefa)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'status' => 'required|string|in:Backlog,Doing,Review,Done',
        ]);

        $tarefa->update($validated);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Tarefa atualizada', 'tarefa' => $tarefa]);
        }

        return redirect()->back()->with('success', 'Tarefa atualizada com sucesso.');
    }

    public function destroy(Projeto $projeto, Tarefa $tarefa)
    {
        $tarefa->delete();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Tarefa excluída']);
        }

        return redirect()->back()->with('success', 'Tarefa removida com sucesso.');
    }

    // Endpoint for Drag and Drop reordering
    public function reorder(Request $request, Projeto $projeto)
    {
        // Formato esperado do frontend: ['status' => 'Doing', 'items' => [id1, id2, id3]]
        $positions = $request->validate([
            'status' => 'required|string|in:Backlog,Doing,Review,Done',
            'items' => 'present|array',
            'items.*' => 'exists:tarefas,id',
        ]);

        $status = $positions['status'];
        foreach ($positions['items'] as $index => $id) {
            Tarefa::where('id', $id)->update([
                'status' => $status,
                'ordem' => $index
            ]);
        }

        return response()->json(['message' => 'Ordem atualizada com sucesso.']);
    }
}

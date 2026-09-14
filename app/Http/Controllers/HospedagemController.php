<?php

namespace App\Http\Controllers;

use App\Models\Projeto;
use App\Support\ConsultaWhois;
use App\Support\Vencimentos;
use Illuminate\Http\Request;

class HospedagemController extends Controller
{
    public function index(Request $request)
    {
        // Por padrão esconde clientes inativos: o painel serve para cuidar do
        // que está em manutenção agora. O filtro existe para quando você
        // precisar conferir o histórico de quem saiu.
        $incluirInativos = $request->boolean('inativos');

        $itens  = Vencimentos::itens($incluirInativos);
        $resumo = Vencimentos::resumo($itens);

        return view('hospedagens.index', compact('itens', 'resumo', 'incluirInativos'));
    }

    /**
     * Ajusta a vigência na mão.
     *
     * O domínio dá para buscar no whois, mas hospedagem não tem de onde
     * tirar: depende do comprovante de renovação, que só quem contratou tem.
     * Por isso o campo é digitado.
     *
     * Data vazia limpa o vencimento, para quando o serviço for cancelado.
     */
    public function salvarHospedagem(Request $request, Projeto $projeto)
    {
        $dados = $request->validate([
            'vigencia' => 'nullable|date',
        ], [], ['vigencia' => 'vencimento']);

        $projeto->hospedagem_vigencia = $dados['vigencia'] ?: null;
        $projeto->save();

        $msg = $dados['vigencia']
            ? 'Hospedagem: vencimento salvo para ' . \Illuminate\Support\Carbon::parse($dados['vigencia'])->format('d/m/Y') . '.'
            : 'Hospedagem: vencimento removido.';

        return back()->with('success', $msg);
    }

    /** Mesma coisa para o domínio, digitado à mão. */
    public function salvarDominio(Request $request, Projeto $projeto)
    {
        $dados = $request->validate([
            'vigencia' => 'nullable|date',
        ], [], ['vigencia' => 'vencimento']);

        $projeto->dominio_vigencia = $dados['vigencia'] ?: null;
        $projeto->save();

        $msg = $dados['vigencia']
            ? 'Domínio: vencimento salvo para ' . \Illuminate\Support\Carbon::parse($dados['vigencia'])->format('d/m/Y') . '.'
            : 'Domínio: vencimento removido.';

        return back()->with('success', $msg);
    }

    /** Busca no whois a data de expiração de um domínio só. */
    public function atualizarDominio(Projeto $projeto)
    {
        if (! $projeto->dominio) {
            return back()->with('error', 'Este projeto não tem domínio cadastrado.');
        }

        $data = ConsultaWhois::expiracao($projeto->dominio);

        if (! $data) {
            return back()->with('error', "Não consegui descobrir o vencimento de {$projeto->dominio}. Confira se o domínio está escrito corretamente ou informe a data à mão.");
        }

        $projeto->dominio_vigencia = $data->toDateString();
        $projeto->save();

        return back()->with('success', "{$projeto->dominio}: vencimento atualizado para " . $data->format('d/m/Y') . '.');
    }

    /**
     * Atualiza todos os domínios de uma vez.
     *
     * Cada domínio é salvo assim que responde, e não no fim. Se a lista for
     * longa e a requisição estourar o tempo do servidor, o que já respondeu
     * fica gravado - e você roda de novo para os que faltaram, em vez de
     * perder tudo.
     *
     * O tempo por consulta é curto de propósito: quem não responde rápido
     * fica para a próxima rodada.
     */
    public function atualizarTodosDominios()
    {
        $projetos = Projeto::query()
            ->whereNotNull('dominio')
            ->where('dominio', '!=', '')
            ->orderBy('id')
            ->get();

        if ($projetos->isEmpty()) {
            return back()->with('error', 'Nenhum projeto tem domínio cadastrado.');
        }

        $ok = 0;
        $falhas = [];

        foreach ($projetos as $p) {
            $data = ConsultaWhois::expiracao($p->dominio, 6);

            if ($data) {
                $p->dominio_vigencia = $data->toDateString();
                $p->save();
                $ok++;
            } else {
                $falhas[] = $p->dominio;
            }
        }

        $msg = $ok . ' domínio' . ($ok === 1 ? '' : 's') . ' atualizado' . ($ok === 1 ? '' : 's') . '.';

        if ($falhas) {
            $mostrar = array_slice($falhas, 0, 4);
            $msg .= ' Sem resposta: ' . implode(', ', $mostrar);
            if (count($falhas) > count($mostrar)) {
                $msg .= ' e mais ' . (count($falhas) - count($mostrar));
            }
            $msg .= '. Esses você pode tentar de novo ou preencher à mão.';
        }

        return back()->with($falhas ? 'error' : 'success', $msg);
    }
}

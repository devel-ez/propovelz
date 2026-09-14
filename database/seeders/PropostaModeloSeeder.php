<?php

namespace Database\Seeders;

use App\Models\PropostaModelo;
use Illuminate\Database\Seeder;

/**
 * Modelos de proposta prontos para inserir.
 *
 * Os trechos entre colchetes, como [NOME OU RAZÃO SOCIAL], são para localizar
 * e substituir ao usar o modelo. O restante costuma ser igual em todas as
 * propostas.
 *
 * Rodar de novo não duplica: usa updateOrCreate pelo título.
 * Rodar:  php artisan db:seed --class=PropostaModeloSeeder
 */
class PropostaModeloSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->modelos() as $modelo) {
            PropostaModelo::updateOrCreate(
                ['titulo' => $modelo['titulo']],
                ['conteudo' => $modelo['conteudo']]
            );
        }

        $this->command->info('Modelos de proposta prontos: ' . count($this->modelos()));
    }

    /**
     * @return array<int, array{titulo: string, conteudo: string}>
     */
    private function modelos(): array
    {
        return [
            [
                'titulo' => 'Contrato de Manutenção e Hospedagem - Plano Nós cuidamos',
                'conteudo' => $this->contratoManutencao(),
            ],
            [
                'titulo' => 'Escopo - Landing Page (plano Só o site)',
                'conteudo' => $this->escopoSite(),
            ],
        ];
    }

    /* ------------------------------------------------------------------ */

    private function contratoManutencao(): string
    {
        return <<<'HTML'
<p><strong>Contratada:</strong> Crie Sites Pro - criesitespro.com.br - contato@criesitespro.com.br - WhatsApp (21) 98064-9966</p>
<p><strong>Contratante:</strong> [NOME OU RAZÃO SOCIAL], [CPF/CNPJ], [ENDEREÇO]</p>
<p><strong>Site objeto deste contrato:</strong> [DOMÍNIO]</p>

<h2>1. Objeto</h2>
<p>Prestação de serviços de <strong>manutenção contínua, hospedagem e suporte</strong> do Site indicado acima, no plano comercialmente conhecido como <strong>Nós cuidamos</strong>.</p>
<p>O serviço é <strong>contínuo</strong>: não tem prazo de encerramento automático. Vigora enquanto as partes assim quiserem, respeitado o compromisso mínimo da cláusula 5.</p>

<h2>2. O que está incluso</h2>
<p><strong>Infraestrutura</strong></p>
<ul>
<li>Hospedagem do Site, em servidor contratado e mantido pela Contratada</li>
<li>Registro e renovação anual do domínio, pagos pela Contratada</li>
<li>Certificado SSL com renovação automática</li>
</ul>
<p><strong>Manutenção</strong></p>
<ul>
<li>Acompanhamento contínuo e correção de falhas antes que virem problema</li>
<li>Atualização de bibliotecas, dependências, temas e plugins</li>
<li>Backups periódicos do Site e do conteúdo</li>
</ul>
<p><strong>Segurança</strong></p>
<ul>
<li>Aplicação das correções de segurança assim que publicadas</li>
<li>Monitoramento de acessos suspeitos e da integridade do Site</li>
<li>Firewall e camada anti-malware ativos</li>
</ul>
<p><strong>Performance</strong></p>
<ul>
<li>Testes periódicos de velocidade e Core Web Vitals</li>
<li>Otimização contínua de imagens, cache e ajustes de carregamento</li>
</ul>
<p><strong>Conteúdo e suporte</strong></p>
<ul>
<li>Alteração de <strong>textos</strong>, <strong>imagens</strong> e <strong>vídeos</strong></li>
<li><strong>Ajustes simples de design</strong> - cores, espaçamentos e ordem de elementos</li>
<li>Suporte direto pelo WhatsApp (21) 98064-9966, com quem construiu o Site</li>
</ul>

<h2>3. O que não está incluso</h2>
<p>Não integram este contrato, sendo objeto de <strong>proposta à parte</strong>:</p>
<ul>
<li>Novas funcionalidades - áreas de cliente, cálculos, módulos</li>
<li>Mudanças complexas de design - redesenho de páginas ou do layout</li>
<li>Novas páginas e sistemas - ampliação do escopo original</li>
</ul>
<p>Nesses casos a Contratada avalia o esforço, envia proposta com escopo, prazo e valor por escrito, e <strong>nada é executado sem a aprovação da Contratante</strong>.</p>

<h2>4. Valores e forma de pagamento</h2>
<ul>
<li>Entrada, para início dos trabalhos: <strong>R$ 400,00</strong></li>
<li>Mensalidade, a partir do 2º mês: <strong>R$ 150,00/mês</strong></li>
<li>1º mês de mensalidade: <strong>por conta da Contratada</strong></li>
</ul>
<p>A mensalidade <strong>começa a contar 30 dias após o Site entrar no ar</strong>, não a partir da assinatura. O primeiro mês é gratuito.</p>
<p><strong>Forma de pagamento:</strong> Pix recorrente para a manutenção mensal, ou Pix à vista para os demais serviços. O pagamento deve ocorrer <strong>dentro da data de vencimento</strong>.</p>

<h2>5. Vigência e compromisso mínimo</h2>
<p>O compromisso mínimo é de <strong>5 (cinco) mensalidades</strong>.</p>
<p><strong>Depois de cumprido o mínimo</strong>, o contrato <strong>continua valendo automaticamente</strong> por prazo indeterminado - a mensalidade segue custeando hospedagem, domínio, segurança, backup e ajustes. A Contratante pode encerrar quando quiser, sem multa.</p>
<p><strong>Antes de cumprido o mínimo</strong>, a Contratante pode encerrar mediante o <strong>pagamento antecipado das mensalidades restantes</strong>, ficando liberada do vínculo e do Site.</p>

<h2>6. Propriedade</h2>
<p><strong>Pertencem à Contratante, desde o início:</strong></p>
<ul>
<li>O <strong>domínio</strong> do Site</li>
<li>Os <strong>textos</strong>, <strong>imagens</strong> e <strong>vídeos</strong> que ela forneceu ou aprovou</li>
<li>O <strong>conteúdo</strong> publicado no Site</li>
</ul>
<p><strong>Pertencem à Contratada</strong> os arquivos de código, o layout e os elementos de design desenvolvidos, <strong>enquanto não cumprido o compromisso mínimo</strong> da cláusula 5.</p>
<p>Cumprido o mínimo, ou havendo o pagamento antecipado, a Contratada <strong>transfere à Contratante os arquivos do Site</strong>, entregues em arquivo e com orientação de uso. A Contratante passa a poder hospedá-lo onde quiser.</p>

<h2>7. Hospedagem e domínio</h2>
<p>A hospedagem corre por conta da Contratada enquanto este contrato vigorar.</p>
<p>O <strong>domínio é registrado em nome da Contratante</strong> desde o início, cabendo à Contratada o pagamento do registro e das renovações anuais enquanto o contrato estiver vigente. Ao término, o domínio <strong>permanece com a Contratante</strong>, sem qualquer ônus ou transferência a fazer.</p>

<h2>8. Obrigações da Contratante</h2>
<ul>
<li>Fornecer, em tempo hábil, os <strong>textos, imagens, vídeos, logotipo e demais conteúdos</strong> necessários</li>
<li>Garantir que o material fornecido <strong>não viola direitos de terceiros</strong> (direito autoral, marca, imagem), respondendo por eventuais reclamações</li>
<li>Indicar <strong>um responsável</strong> pelas aprovações, para dar agilidade às decisões</li>
<li>Aprovar ou solicitar ajustes dentro do prazo combinado</li>
<li>Manter os dados de acesso ao Site em sigilo</li>
</ul>

<h2>9. Prazos e suporte</h2>
<p><strong>Resposta:</strong> a Contratada responde às solicitações em até <strong>1 (um) dia útil</strong>.</p>
<p><strong>Execução:</strong> o prazo de execução é definido <strong>conforme a complexidade e a necessidade</strong> de cada solicitação, e informado à Contratante no momento do pedido.</p>
<p>O prazo de <strong>10 a 15 dias</strong> informado na proposta refere-se à construção inicial do Site e conta <strong>a partir do recebimento de todo o conteúdo</strong> (textos, imagens e logotipo). Atrasos no envio do conteúdo deslocam o prazo na mesma proporção.</p>

<h2>10. Alterações de conteúdo</h2>
<p>Não há limite de quantidade para <strong>troca de textos, imagens e vídeos</strong>, observado o <strong>princípio da razoabilidade</strong> e a natureza deste contrato.</p>
<p>Este contrato tem por objeto o <strong>desenvolvimento e a manutenção de site</strong>, e <strong>não a produção de conteúdo</strong>. Não estão inclusas:</p>
<ul>
<li>Redação de artigos, posts ou materiais editoriais</li>
<li>Newsletters e campanhas de e-mail marketing</li>
<li>Gestão de redes sociais</li>
<li>Produção recorrente ou em volume de material de marketing</li>
</ul>
<p>Essas atividades são de <strong>responsabilidade da Contratante</strong>.</p>

<h2>11. Inadimplência</h2>
<p>Havendo atraso no pagamento da mensalidade, a Contratada <strong>enviará comunicação à Contratante</strong> informando o débito e solicitando a regularização.</p>
<p>Não havendo regularização, a Contratada poderá <strong>suspender o Site 30 (trinta) dias após o envio do aviso</strong>.</p>
<p>O restabelecimento ocorre em até <strong>3 (três) dias úteis</strong> após a confirmação do pagamento. A Contratada não responde por prejuízos decorrentes da suspensão motivada por inadimplência.</p>

<h2>12. Rescisão</h2>
<p><strong>Pela Contratante:</strong> a qualquer tempo, após o mínimo, sem multa.</p>
<p><strong>Pela Contratada:</strong> em caso de inadimplência não regularizada, uso ilícito do Site, ou solicitação de conteúdo que viole a lei ou direitos de terceiros.</p>
<p><strong>Em qualquer caso de rescisão:</strong> a Contratada entrega à Contratante os arquivos do Site e o conteúdo, conforme cláusula 6. Os dados são mantidos por <strong>30 (trinta) dias</strong> após o encerramento, para eventual retomada, e depois descartados.</p>

<h2>13. Conteúdo, dados pessoais e LGPD</h2>
<p><strong>13.1</strong> Todo o conteúdo publicado no Site - textos, imagens, vídeos, ofertas, informações comerciais, preços, condições e declarações - é de <strong>exclusiva responsabilidade da Contratante</strong>, que responde por sua veracidade, licitude e conformidade com a legislação.</p>
<p><strong>13.2</strong> A Contratante é a <strong>controladora</strong> dos dados pessoais tratados no Site, nos termos da Lei 13.709/2018 (LGPD), cabendo a ela definir as finalidades do tratamento, as bases legais aplicáveis, atender às solicitações dos titulares e manter registro das operações.</p>
<p><strong>13.3</strong> A Contratada atua como <strong>operadora</strong>, tratando os dados pessoais apenas para viabilizar o funcionamento e a manutenção do Site, seguindo as finalidades definidas pela Contratante e mantendo as medidas técnicas de segurança previstas neste contrato - backup, certificado SSL, firewall e monitoramento.</p>
<p><strong>13.4</strong> As partes mantêm sigilo sobre informações comerciais e técnicas a que tiverem acesso.</p>

<h2>14. Limitação de responsabilidade</h2>
<p>A Contratada mantém backups periódicos e monitoramento, mas <strong>não responde por</strong> lucros cessantes, perda de faturamento ou danos indiretos decorrentes de indisponibilidade, ataques, falhas de terceiros (provedor de hospedagem, registrador, serviços de e-mail) ou do conteúdo publicado pela Contratante.</p>
<p>A responsabilidade da Contratada, em qualquer hipótese, fica limitada ao valor total pago nos <strong>12 (doze) meses anteriores</strong> ao evento.</p>

<h2>15. Disposições gerais</h2>
<p>Alterações deste contrato só valem <strong>por escrito</strong>, com concordância das duas partes.</p>
<p>Este contrato é regido pelas leis brasileiras. Fica eleito o <strong>foro da Comarca de Brasília-DF</strong> para dirimir controvérsias, com renúncia a qualquer outro.</p>

<p><strong>Local e data:</strong> ______________________</p>
<p><strong>Contratada:</strong> Crie Sites Pro - ______________________<br><strong>Contratante:</strong> ______________________</p>
HTML;
    }

    /* ------------------------------------------------------------------ */

    private function escopoSite(): string
    {
        return <<<'HTML'
<p>Esta proposta descreve a criação de uma <strong>landing page</strong>, no plano comercialmente conhecido como <strong>Só o site</strong>.</p>

<h2>O que será entregue</h2>
<ul>
<li>1 página, com até 5 seções, mais cabeçalho e rodapé</li>
<li>Layout responsivo - celular, tablet e desktop</li>
<li>Formulário de contato e botão de WhatsApp</li>
<li>SEO básico e integrações de rastreamento</li>
<li>Publicação do site no ar</li>
</ul>

<h2>O que não está incluso</h2>
<ul>
<li>Hospedagem e domínio - contratados pela Contratante, por conta dela</li>
<li>Segurança, ajustes e suporte mensal</li>
<li>Mensalidade de qualquer espécie</li>
</ul>
<p>Os arquivos do site <strong>são da Contratante desde o primeiro dia</strong>.</p>

<h2>Prazo</h2>
<p>Entre <strong>10 e 15 dias</strong>, contando a partir do recebimento de todo o conteúdo: textos, imagens e logotipo.</p>

<h2>Investimento</h2>
<p><strong>R$ 900,00</strong> - metade para começar e metade na entrega, quando o site vai ao ar.</p>

<h2>Como seguimos</h2>
<ol>
<li>Você envia o conteúdo (textos, imagens e logotipo)</li>
<li>Construímos a página e você acompanha o progresso</li>
<li>Você revisa com calma e ajustamos o que precisar, dentro do escopo</li>
<li>Publicamos e configuramos as integrações</li>
</ol>
HTML;
    }
}

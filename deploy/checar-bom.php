<?php

/**
 * Procura a marca BOM de UTF-8 (EF BB BF) no começo dos arquivos do projeto.
 *
 * Por que isso importa: o PHP imprime qualquer coisa que venha ANTES do <?php
 * de um arquivo incluído. Um BOM em config/, routes/ ou em um provider sai na
 * frente de TODA resposta - e o resultado é silencioso e bizarro: a página
 * HTML funciona (o navegador ignora), mas imagem, PDF e download chegam
 * corrompidos com 3 bytes de lixo na frente, e o navegador mostra imagem
 * quebrada sem dizer por quê.
 *
 * Rodar antes de publicar:
 *     php deploy/checar-bom.php
 *
 * Sai com código 1 se achar algum, para poder entrar em verificação automática.
 */

$raiz = dirname(__DIR__);

$ignorar = [
    DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR,
    DIRECTORY_SEPARATOR . 'node_modules' . DIRECTORY_SEPARATOR,
    DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'framework' . DIRECTORY_SEPARATOR,
    DIRECTORY_SEPARATOR . '.git' . DIRECTORY_SEPARATOR,
];

$extensoes = ['php', 'js', 'css', 'json', 'html'];

$achados  = [];
$olhados  = 0;

$it = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($raiz, FilesystemIterator::SKIP_DOTS)
);

foreach ($it as $arquivo) {
    $caminho = $arquivo->getPathname();

    foreach ($ignorar as $p) {
        if (str_contains($caminho, $p)) { continue 2; }
    }
    if (! in_array(strtolower($arquivo->getExtension()), $extensoes, true)) { continue; }

    $olhados++;
    $h = @fopen($caminho, 'rb');
    if (! $h) { continue; }
    $inicio = fread($h, 3);
    fclose($h);

    if ($inicio === "\xEF\xBB\xBF") {
        $achados[] = str_replace($raiz . DIRECTORY_SEPARATOR, '', $caminho);
    }
}

echo "Arquivos conferidos: {$olhados}\n";

if (! $achados) {
    echo "Nenhum BOM encontrado.\n";
    exit(0);
}

echo "\nBOM de UTF-8 encontrado em " . count($achados) . " arquivo(s):\n";
foreach ($achados as $a) {
    // Marca os que carregam em toda requisição: são os piores
    $critico = preg_match('#^(routes|config|bootstrap)/#', $a) ? '  <-- CARREGA SEMPRE, CORRIGIR JÁ' : '';
    echo "  {$a}{$critico}\n";
}
echo "\nPara corrigir, remova os 3 primeiros bytes do arquivo, sem mexer no resto.\n";

exit(1);

<?php

/**
 * Ponto de entrada do painel administrativo.
 *
 * Endereço: https://criesitespro.com.br/painel
 *
 * A APLICAÇÃO LARAVEL FICA FORA DESTA PASTA, em /home/u355084043/propovelz.
 * Só este diretório é exposto pela web — por isso o .env, o vendor/ e o app/
 * nunca ficam alcançáveis por uma requisição HTTP.
 *
 * Se o painel mudar de lugar, ajuste apenas a variável $appPath abaixo.
 */

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

$appPath = '/home/u355084043/propovelz';

if (! is_file($appPath.'/vendor/autoload.php')) {
    http_response_code(500);
    exit('Aplicacao nao encontrada em '.$appPath.'. Verifique se o painel foi publicado.');
}

if (file_exists($maintenance = $appPath.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

require $appPath.'/vendor/autoload.php';

$app = require_once $appPath.'/bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);

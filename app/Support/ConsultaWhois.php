<?php

namespace App\Support;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Iodev\Whois\Factory;

/**
 * Consulta a data de expiração de um domínio.
 *
 * Ficou aqui, e não dentro do controller, porque duas telas precisam disso:
 * a consulta avulsa (botão "buscar no whois") e a atualização em lote de
 * todos os domínios. Se cada uma tivesse a sua cópia, uma poderia trazer a
 * data certa e a outra não, para o mesmo domínio.
 */
class ConsultaWhois
{
    /**
     * Domínios .br são consultados no RDAP do Registro.br, que é uma API
     * oficial e responde em JSON. Os demais vão pelo whois comum, que é mais
     * lento e nem sempre responde.
     */
    public const TIMEOUT_PADRAO = 10;

    /** Devolve a data de expiração, ou null quando não conseguiu descobrir. */
    public static function expiracao(string $dominio, int $timeout = self::TIMEOUT_PADRAO): ?Carbon
    {
        $dominio = self::limpar($dominio);
        if ($dominio === '') {
            return null;
        }

        try {
            return str_ends_with($dominio, '.br')
                ? self::peloRdap($dominio, $timeout)
                : self::peloWhois($dominio);
        } catch (\Throwable $e) {
            Log::warning("Whois falhou para {$dominio}: " . $e->getMessage());
            return null;
        }
    }

    /** Tira http, www e caminho: o whois quer só o domínio. */
    public static function limpar(?string $dominio): string
    {
        $d = trim((string) $dominio);
        $d = preg_replace('#^https?://#i', '', $d);
        $d = preg_replace('#^www\.#i', '', $d);
        $d = explode('/', $d)[0];
        $d = explode('?', $d)[0];

        return strtolower(trim($d));
    }

    private static function peloRdap(string $dominio, int $timeout): ?Carbon
    {
        // O certificado do rdap.registro.br é válido e o servidor valida sem
        // problema (conferido: HTTP 200 sem precisar desligar a verificação).
        // O código antigo usava withoutVerifying; não é necessário.
        $resp = Http::timeout($timeout)
            ->get("https://rdap.registro.br/domain/{$dominio}");

        if (! $resp->successful()) {
            return null;
        }

        foreach ($resp->json('events') ?? [] as $evento) {
            if (($evento['eventAction'] ?? null) === 'expiration' && ! empty($evento['eventDate'])) {
                return Carbon::parse($evento['eventDate'])->startOfDay();
            }
        }

        return null;
    }

    private static function peloWhois(string $dominio): ?Carbon
    {
        $info = Factory::get()->createWhois()->loadDomainInfo($dominio);

        if (! $info || ! $info->expirationDate) {
            return null;
        }

        return Carbon::createFromTimestamp($info->expirationDate)->startOfDay();
    }
}

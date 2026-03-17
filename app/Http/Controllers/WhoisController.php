<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Iodev\Whois\Factory;

class WhoisController extends Controller
{
    public function consultar(Request $request)
    {
        $domain = $request->input('domain');

        if (!$domain) {
            return response()->json(['error' => 'Domínio não informado.'], 400);
        }

        try {
            $domain = trim($domain);
            $domain = preg_replace('/^https?:\/\//', '', $domain);
            $domain = preg_replace('/^www\./', '', $domain);
            $domain = explode('/', $domain)[0];

            $expiresAt = null;

            if (str_ends_with($domain, '.br')) {
                // RDAP Registro.br
                $response = \Illuminate\Support\Facades\Http::withoutVerifying()->timeout(10)->get("https://rdap.registro.br/domain/{$domain}");
                if ($response->successful()) {
                    $data = $response->json();
                    if (isset($data['events'])) {
                        foreach ($data['events'] as $event) {
                            if ($event['eventAction'] === 'expiration') {
                                $expiresAt = \Carbon\Carbon::parse($event['eventDate'])->format('Y-m-d');
                                break;
                            }
                        }
                    }
                }
            } else {
                // Para outros, tenta usar o pacote, porém com timeout
                $whois = Factory::get()->createWhois();
                $info = $whois->loadDomainInfo($domain);
                if ($info) {
                    $expiresAt = date("Y-m-d", $info->expirationDate);
                }
            }

            if (!$expiresAt) {
                return response()->json(['error' => 'Não foi possível obter a data de expiração deste domínio.'], 404);
            }

            return response()->json([
                'domain' => $domain,
                'expires_at' => $expiresAt
            ]);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Whois error for $domain: " . $e->getMessage());
            return response()->json(['error' => 'Erro ao consultar domínio: ' . $e->getMessage()], 500);
        }
    }
}

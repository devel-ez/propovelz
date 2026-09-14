<?php

/**
 * Dados da empresa que emite as propostas.
 *
 * Ficam aqui, e não escritos em cada view, porque aparecem no PDF, na prévia
 * interna e na página pública de assinatura. Se o nome ou o cargo mudarem,
 * muda-se num lugar só — e as três telas acompanham.
 */
return [

    'nome'     => 'Crie Sites Pro',
    'site'     => 'criesitespro.com.br',
    'email'    => 'contato@criesitespro.com.br',
    'whatsapp' => '(21) 98064-9966',

    'responsavel' => [
        // Como aparece na linha de assinatura do PDF.
        'assinatura' => 'Felipe Velêz Rocha',

        // Identificação completa, embaixo da linha.
        'nome'       => 'Felipe Velêz Rocha',
        'cargo'      => 'Engenheiro de Software',
    ],

];

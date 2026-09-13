/* =============================================================================
   Verificador da landing — Crie Sites Pro
   -----------------------------------------------------------------------------
   Ferramenta INTERNA. Nao faz parte do site e nao deve ser enviada por FTP.

   Uso (na pasta landing):
       node _extras/verificar.js

   O que ele confere:
     - tags HTML abertas e fechadas corretamente
     - ancoras (#secao) que realmente existem
     - <img> sem atributo alt
     - arquivos referenciados que nao existem no disco
     - chaves { } do CSS balanceadas
     - caracteres suspeitos (largura total) que costumam ser erro de digitacao

   Rode depois de qualquer edicao de conteudo.
   ============================================================================= */

const fs = require('fs');
const path = require('path');

const RAIZ = path.join(__dirname, '..');
const rel = p => path.join(RAIZ, p);

const VOID = new Set(['meta','link','img','br','hr','input','source','area','base',
                      'col','embed','param','track','wbr','!doctype']);

function checkHtml(arquivo){
  const src = fs.readFileSync(arquivo, 'utf8');
  const problemas = [];
  const pilha = [];

  // Neutraliza comentarios mantendo a numeracao das linhas
  const limpo = src.replace(/<!--[\s\S]*?-->/g, m => m.replace(/[^\n]/g, ' '));
  const re = /<(\/?)([a-zA-Z!][a-zA-Z0-9-]*)((?:"[^"]*"|'[^']*'|[^>"'])*?)(\/?)>/g;

  let m, total = 0;
  while ((m = re.exec(limpo)) !== null){
    total++;
    const [, fechando, tagBruta, , autoFecha] = m;
    const tag = tagBruta.toLowerCase();
    if (VOID.has(tag) || autoFecha) continue;

    const linha = limpo.slice(0, m.index).split('\n').length;

    if (!fechando){
      pilha.push({ tag, linha });
    } else {
      const topo = pilha.pop();
      if (!topo) problemas.push(`linha ${linha}: </${tag}> sem abertura`);
      else if (topo.tag !== tag)
        problemas.push(`linha ${linha}: </${tag}> fecha <${topo.tag}> da linha ${topo.linha}`);
    }
  }
  pilha.forEach(t => problemas.push(`linha ${t.linha}: <${t.tag}> nunca fechado`));

  return { problemas, total };
}

function checkExtras(arquivo){
  const src = fs.readFileSync(arquivo, 'utf8');
  const problemas = [];

  const ids = new Set([...src.matchAll(/\sid="([^"]+)"/g)].map(m => m[1]));
  for (const h of new Set([...src.matchAll(/href="#([^"]+)"/g)].map(m => m[1]))){
    if (!ids.has(h)) problemas.push(`ancora #${h} nao tem destino (id ausente)`);
  }

  for (const m of src.matchAll(/<img\b[^>]*>/g)){
    if (!/\balt=/.test(m[0])) problemas.push(`<img> sem alt: ${m[0].slice(0, 60)}`);
  }

  const refs = [...src.matchAll(/(?:href|src)="((?!http|mailto:|tel:|#|\/\/)[^"]+)"/g)]
    .map(m => m[1]);
  for (const r of new Set(refs)){
    const p = path.join(path.dirname(arquivo), r.split('?')[0]);
    if (!fs.existsSync(p)) problemas.push(`referencia quebrada: ${r}`);
  }

  return problemas;
}

function checkCss(arquivo){
  const src = fs.readFileSync(arquivo, 'utf8');
  const problemas = [];
  const semComentario = src.replace(/\/\*[\s\S]*?\*\//g, '');

  let nivel = 0;
  for (let i = 0; i < semComentario.length; i++){
    if (semComentario[i] === '{') nivel++;
    else if (semComentario[i] === '}'){
      nivel--;
      if (nivel < 0){
        problemas.push(`linha ${semComentario.slice(0, i).split('\n').length}: } sem {`);
        nivel = 0;
      }
    }
  }
  if (nivel > 0) problemas.push(`${nivel} bloco(s) { nunca fechado(s)`);

  src.split('\n').forEach((l, i) => {
    if (/[\uFF00-\uFFEF\u3000\u2000-\u200F]/.test(l)){
      problemas.push(`linha ${i + 1}: caractere suspeito -> ${l.trim().slice(0, 70)}`);
    }
  });

  return problemas;
}

/* Valida os blocos JSON-LD: um JSON invalido quebra os resultados
   enriquecidos no Google sem dar nenhum sinal visivel na pagina. */
function checkJsonLd(arquivo){
  const src = fs.readFileSync(arquivo, 'utf8');
  const problemas = [];
  const blocos = [...src.matchAll(
    /<script[^>]*type=["']application\/ld\+json["'][^>]*>([\s\S]*?)<\/script>/gi
  )];

  if (blocos.length === 0){
    problemas.push('nenhum bloco application/ld+json encontrado');
    return problemas;
  }

  blocos.forEach((m, i) => {
    const linha = src.slice(0, m.index).split('\n').length;
    try {
      const obj = JSON.parse(m[1]);
      const tipos = [].concat(obj['@type'] || obj['@graph'] || [])
        .map(t => (typeof t === 'string' ? t : t && t['@type']))
        .filter(Boolean);
      console.log(`      bloco ${i + 1} (linha ${linha}): ${tipos.join(', ') || 'sem @type'}`);
      if (!obj['@context']) problemas.push(`bloco ${i + 1} (linha ${linha}): falta @context`);
    } catch (e) {
      problemas.push(`bloco ${i + 1} (linha ${linha}): JSON invalido -> ${e.message}`);
    }
  });

  return problemas;
}

/* -------------------------------------------------------------------------- */

let falhas = 0;

function relatar(rotulo, problemas, extra){
  if (problemas.length === 0){
    console.log(`OK    ${rotulo}${extra ? ' [' + extra + ']' : ''}`);
  } else {
    falhas += problemas.length;
    console.log(`FALHA ${rotulo}`);
    problemas.forEach(p => console.log(`         ${p}`));
  }
}

const alvos = [
  ['index.html',        checkHtml,    checkExtras],
  ['_extras/og-card.html', checkHtml, null],
];

for (const [arq, ...checks] of alvos){
  const full = rel(arq);
  if (!fs.existsSync(full)){ console.log(`AUSENTE ${arq}`); falhas++; continue; }
  const r = checks[0](full);
  relatar(arq, r.problemas, `${r.total} tags`);
  if (checks[1]) relatar(`${arq}  (ancoras, alt, referencias)`, checks[1](full));
}

const css = rel('assets/css/styles.css');
if (!fs.existsSync(css)){ console.log('AUSENTE assets/css/styles.css'); falhas++; }
else relatar('assets/css/styles.css', checkCss(css), 'chaves e caracteres');

console.log('      dados estruturados (JSON-LD):');
relatar('index.html  (JSON-LD)', checkJsonLd(rel('index.html')));

console.log('');
console.log(falhas === 0 ? '==> Tudo certo.' : `==> ${falhas} problema(s) encontrado(s).`);
process.exit(falhas === 0 ? 0 : 1);

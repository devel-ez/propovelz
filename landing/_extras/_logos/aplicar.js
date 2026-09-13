/* Substitui os badges de letra pelos logos oficiais inline.
   Roda uma vez; depois pode ser apagado. */

const fs = require('fs');
const path = require('path');

const DIR = 'D:/Programação/Propovelz/landing/_extras/_logos';
const HTML = 'D:/Programação/Propovelz/landing/index.html';

const read = f => fs.readFileSync(path.join(DIR, f), 'utf8');

/* ---------------- Google: 4 paths coloridos, viewBox 24x24 ---------------- */
const google = read('google.svg');
const gPaths = [...google.matchAll(/<path\s+d="([^"]+)"\s+fill="(#[0-9A-Fa-f]{6})"/g)]
  .map(m => `            <path fill="${m[2]}" d="${m[1]}"/>`)
  .join('\n');
if (!gPaths) throw new Error('Google: nenhum path colorido encontrado');

const googleSvg =
`<svg viewBox="0 0 24 24" width="24" height="24" xmlns="http://www.w3.org/2000/svg">
${gPaths}
          </svg>`;

/* ---------------- Meta: 3 paths do infinito, viewBox 0 0 288 191 --------- */
const meta = read('meta.svg');
const mAll = [...meta.matchAll(/<path[^>]*?\sd="([^"]+)"[^>]*>/g)].map(m => m[1]);
if (mAll.length < 3) throw new Error('Meta: esperava ao menos 3 paths, achei ' + mAll.length);

const metaDefs =
`<linearGradient id="metaGradA" x1="61" y1="117" x2="259" y2="127" gradientUnits="userSpaceOnUse">
              <stop stop-color="#0064e1" offset="0"/><stop stop-color="#0064e1" offset="0.4"/>
              <stop stop-color="#0073ee" offset="0.83"/><stop stop-color="#0082fb" offset="1"/>
            </linearGradient>
            <linearGradient id="metaGradB" x1="45" y1="139" x2="45" y2="66" gradientUnits="userSpaceOnUse">
              <stop stop-color="#0082fb" offset="0"/><stop stop-color="#0064e0" offset="1"/>
            </linearGradient>`;

const metaSvg =
`<svg viewBox="0 0 288 191" width="28" height="19" xmlns="http://www.w3.org/2000/svg">
          <defs>
            ${metaDefs}
          </defs>
          <path fill="#0081fb" d="${mAll[0]}"/>
          <path fill="url(#metaGradA)" d="${mAll[1]}"/>
          <path fill="url(#metaGradB)" d="${mAll[2]}"/>
        </svg>`;

/* ---------------- RD Station: 1 path, viewBox 0 0 252 300 ----------------- */
const rd = read('rd.svg');
const rdPath = rd.match(/<path[^>]*?\sd="([^"]+)"/);
if (!rdPath) throw new Error('RD Station: path do icone nao encontrado');

const rdSvg =
`<svg viewBox="0 0 252 300" width="21" height="25" xmlns="http://www.w3.org/2000/svg">
            <path fill="#002233" d="${rdPath[1]}"/>
          </svg>`;

/* ---------------- Substituicao no HTML ----------------------------------- */
let html = fs.readFileSync(HTML, 'utf8');
const antes = html.length;
const trocas = [];

function troca(marca, novo) {
  const velho = `<span class="intg-badge" aria-hidden="true">${marca}</span>`;
  if (!html.includes(velho)) throw new Error(`nao achei o badge "${marca}"`);
  html = html.replace(velho, `<span class="intg-badge" aria-hidden="true">\n          ${novo}\n        </span>`);
  trocas.push(marca);
}

troca('G',  googleSvg);
troca('M',  metaSvg);
troca('RD', rdSvg);

fs.writeFileSync(HTML, html, 'utf8');

console.log('badges substituidos: ' + trocas.join(', '));
console.log(`html: ${antes} -> ${html.length} chars (+${html.length - antes})`);
console.log(`tamanho: google=${googleSvg.length}  meta=${metaSvg.length}  rd=${rdSvg.length}`);

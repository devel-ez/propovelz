# Crie Sites Pro — Landing Page

Landing page estática em **HTML, CSS e JavaScript puro**. Sem framework, sem build,
sem dependências. Editar e subir por FTP.

---

## 1. O que vai para o servidor

Suba estes itens para dentro do `public_html` do `criesitespro.com.br`:

```
index.html
favicon.svg
robots.txt
sitemap.xml
site.webmanifest
assets/
  css/styles.css
  js/main.js
  img/            ← crie esta pasta e coloque og-image.jpg aqui
```

### ⚠️ NÃO suba

| Item | Motivo |
|---|---|
| `_extras/` | Pasta de apoio interno. Se subir, fica exposta na web. |
| `LEIA-ME-DEPLOY.md` | Documento interno. |

### 🚫 NUNCA sobrescreva nem apague

```
robo-compras.js
```

Esse arquivo pertence a **outro projeto** e é servido por este domínio. O deploy
da landing **não pode** tocar nele. Se usar um cliente FTP, confirme que ele não
está removendo "arquivos extras" no servidor — alguns clientes fazem isso por padrão.

---

## 2. Conteúdo confirmado (nada pendente)

Todos os dados do negócio já estão preenchidos e conferidos:

| Item | Valor no site |
|---|---|
| WhatsApp | +55 21 98064-9966 |
| E-mail | contato@criesitespro.com.br |
| Domínio | criesitespro.com.br |
| Plano Básico | R$ 900,00 na entrega do projeto |
| Mensalidade | R$ 150,00/mês — **opcional**, o cliente pode recusar |
| Prazo do Plano Básico | 10 a 15 dias, a partir do recebimento do conteúdo |
| Instagram | não publicado |
| CNPJ | não publicado (ainda não existe) |

Para conferir que não sobrou nenhum marcador de rascunho:

```powershell
Select-String -Path .\landing\index.html -Pattern 'AJUSTAR'
```

O comando não deve retornar nada.

---

## 3. Gerar a imagem de compartilhamento (og-image.jpg)

O `index.html` já aponta para `assets/img/og-image.jpg`, que **ainda não existe**.
Sem ela, o link não mostra imagem ao ser compartilhado no WhatsApp.

1. Abra `_extras/og-card.html` no Chrome.
2. `F12` → `Ctrl+Shift+M` → defina **1200 × 630**.
3. Capture a área exata e salve como `assets/img/og-image.jpg`.
4. Suba a pasta `assets/img/` junto com o resto.

---

## 4. Publicar na Hostinger (FTP)

**Antes de tudo**, no hPanel: verifique em **Sites** se já existe um site para
`criesitespro.com.br`.

- **Se NÃO existir:** crie com a opção **PHP/HTML personalizado**. Ela gera um
  `public_html` limpo, sem CMS. Não escolha WordPress (ele instalaria arquivos e
  regras de rewrite na mesma raiz do `robo-compras.js`).
- **Se JÁ existir:** não crie outro site. Apenas entre no `public_html` dele.

Depois, por FTP:

1. Conecte com os dados do hPanel (Arquivos → Contas FTP).
2. Entre em `public_html/`.
3. Confirme que `robo-compras.js` está lá — **antes** de subir qualquer coisa.
4. Envie os arquivos e pastas listados na seção 1.
5. Se o Hostinger tiver deixado um `index.html` ou `index.php` de exemplo
   ("Site criado com sucesso"), **apague**.

### Verificar depois de subir

| Verificação | Como |
|---|---|
| Site no ar | Abrir `https://criesitespro.com.br` |
| HTTPS ativo | O cadeado aparece; se não, ative o SSL grátis no hPanel |
| Script do outro projeto intacto | Abrir `https://criesitespro.com.br/robo-compras.js` — deve **baixar o arquivo**, não dar 404 |
| Botão de WhatsApp | Clicar e conferir se abre com a mensagem pronta |
| Celular | Abrir no telefone e testar o menu hambúrguer |
| Velocidade | `https://pagespeed.web.dev` — meta: acima de 90 no celular |

---

## 5. Trocar o tema (cores e fontes)

Todo o visual é controlado por variáveis CSS no topo de `assets/css/styles.css`,
no bloco `:root`. Para mudar a identidade do site inteiro, altere só isto:

```css
:root{
  --ink:     #0A0F1C;   /* fundo escuro (hero, rodapé, CTA) */
  --paper:   #FFFFFF;   /* fundo claro                    */
  --brand:   #2563EB;   /* cor de ação / botões / links   */
  --brand-2: #06B6D4;   /* tom do gradiente               */
  --brand-3: #7C3AED;   /* tom do gradiente               */
  --wa:      #25D366;   /* verde do WhatsApp              */
}
```

---

## 6. Estrutura da página

Cabeçalho + 9 seções + rodapé, na ordem:

| # | Seção | Âncora |
|---|---|---|
| 1 | Hero | `#hero` |
| 2 | Serviços | `#servicos` |
| 3 | Integrações | `#integracoes` |
| 4 | Preços | `#precos` |
| 5 | Domínio e Hospedagem | `#dominio-hospedagem` |
| 6 | Mensalidade | `#mensalidade` |
| 7 | Como funciona | `#processo` |
| 8 | FAQ | `#faq` |
| 9 | CTA final | `#contato` |

> O **Plano Básico vendido é de 5 seções**. Esta página tem 9 porque é a vitrine
> do serviço. O escopo do plano está tabelado na seção de Preços para não gerar
> ruído na negociação.

---

## 7. Observações técnicas

- **Fontes:** Plus Jakarta Sans via Google Fonts (mesma do painel admin), com
  fallback de fonte do sistema.
- **Sem `.htaccess` obrigatório.** O `_extras/htaccess-opcional.txt` traz cache,
  HTTPS e compressão — leia o aviso no topo antes de usar. Ele é seguro para o
  `robo-compras.js` porque libera arquivos que existem no disco.
- **Sem `js`:** o conteúdo aparece normalmente. A animação de entrada só é
  aplicada quando o JavaScript está ativo.
- **Acessibilidade:** link de pular conteúdo, foco visível, `aria-*` no menu,
  contraste conferido e suporte a `prefers-reduced-motion`.
- **Sem cookie, sem rastreador, sem chamada externa** além das fontes.

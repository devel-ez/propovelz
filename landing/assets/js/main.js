/* ==========================================================================
   Crie Sites Pro — script principal
   Vanilla JS, sem dependências. ~2 KB.
   ========================================================================== */

(function () {
  'use strict';

  /* --- Ano dinâmico no rodapé ------------------------------------------ */
  var anoEl = document.getElementById('ano');
  if (anoEl) anoEl.textContent = String(new Date().getFullYear());

  /* --- Cabeçalho: sombra ao rolar -------------------------------------- */
  var header = document.getElementById('siteHeader');
  if (header) {
    var onScroll = function () {
      header.classList.toggle('is-stuck', window.scrollY > 12);
    };
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
  }

  /* --- Menu mobile ------------------------------------------------------ */
  var toggle = document.getElementById('navToggle');
  var nav = document.getElementById('nav');

  if (toggle && nav) {
    var closeNav = function () {
      nav.classList.remove('is-open');
      toggle.setAttribute('aria-expanded', 'false');
      toggle.setAttribute('aria-label', 'Abrir menu');
    };

    toggle.addEventListener('click', function () {
      var aberto = nav.classList.toggle('is-open');
      toggle.setAttribute('aria-expanded', aberto ? 'true' : 'false');
      toggle.setAttribute('aria-label', aberto ? 'Fechar menu' : 'Abrir menu');
    });

    /* Fecha ao clicar em um link do menu */
    nav.addEventListener('click', function (e) {
      if (e.target.closest('a')) closeNav();
    });

    /* Fecha com Esc */
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && nav.classList.contains('is-open')) {
        closeNav();
        toggle.focus();
      }
    });

    /* Fecha ao clicar fora */
    document.addEventListener('click', function (e) {
      if (!nav.classList.contains('is-open')) return;
      if (nav.contains(e.target) || toggle.contains(e.target)) return;
      closeNav();
    });

    /* Volta ao estado desktop se a janela crescer */
    var mq = window.matchMedia('(min-width: 901px)');
    var onMq = function (ev) { if (ev.matches) closeNav(); };
    if (mq.addEventListener) mq.addEventListener('change', onMq);
    else if (mq.addListener) mq.addListener(onMq);
  }

  /* --- Animação de entrada (revela ao entrar na tela) ------------------- */
  var reveals = document.querySelectorAll('.reveal');

  var mostrarTudo = function () {
    for (var i = 0; i < reveals.length; i++) {
      reveals[i].classList.add('is-visible');
    }
  };

  var semAnimacao =
    window.matchMedia &&
    window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  if (!('IntersectionObserver' in window) || semAnimacao) {
    mostrarTudo();
  } else {
    var io = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (entry) {
          if (!entry.isIntersecting) return;
          entry.target.classList.add('is-visible');
          io.unobserve(entry.target);
        });
      },
      { rootMargin: '0px 0px -8% 0px', threshold: 0.08 }
    );

    for (var j = 0; j < reveals.length; j++) io.observe(reveals[j]);

    /* Rede de segurança: se algo falhar, libera tudo depois de 2,5 s */
    window.setTimeout(mostrarTudo, 2500);
  }

  /* --- FAQ: acordeão (um aberto por vez) ------------------------------- */
  var faq = document.getElementById('faqList');
  if (faq) {
    var itens = faq.querySelectorAll('details.faq-item');
    for (var k = 0; k < itens.length; k++) {
      itens[k].addEventListener('toggle', function () {
        if (!this.open) return;
        for (var m = 0; m < itens.length; m++) {
          if (itens[m] !== this) itens[m].open = false;
        }
      });
    }
  }
})();

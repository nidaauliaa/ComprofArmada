/* Fitur situs: dropdown bahasa, pencarian (saran langsung), carousel sosial media */
(function () {
  'use strict';
  var $ = function (s, c) { return (c || document).querySelector(s); };

  // dropdown bahasa
  var lw = $('[data-lang-wrap]');
  if (lw) {
    var btn = $('[data-lang-btn]', lw);
    btn.addEventListener('click', function (e) {
      e.stopPropagation();
      var open = lw.classList.toggle('open');
      btn.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
    document.addEventListener('click', function () { lw.classList.remove('open'); });
  }

  // pencarian
  var pop = document.getElementById('searchPop');
  if (pop) {
    var inp = document.getElementById('searchInput'), sug = document.getElementById('sugList'), timer;
    var open = function () { pop.hidden = false; setTimeout(function () { inp.focus(); }, 40); };
    var close = function () { pop.hidden = true; };
    [].slice.call(document.querySelectorAll('[data-search-open]')).forEach(function (b) { b.addEventListener('click', open); });
    pop.addEventListener('click', function (e) { if (e.target === pop) close(); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') close(); });
    inp.addEventListener('input', function () {
      clearTimeout(timer);
      var q = inp.value.trim();
      if (q.length < 2) { sug.innerHTML = ''; return; }
      timer = setTimeout(function () {
        fetch(pop.getAttribute('data-suggest') + '?q=' + encodeURIComponent(q), { headers: { Accept: 'application/json' } })
          .then(function (r) { return r.json(); })
          .then(function (list) {
            sug.innerHTML = '';
            list.forEach(function (it) {
              var li = document.createElement('li'), a = document.createElement('a');
              a.href = it.url; a.innerHTML = '<span></span><small></small>';
              a.firstChild.textContent = it.title; a.lastChild.textContent = it.type;
              li.appendChild(a); sug.appendChild(li);
            });
          }).catch(function () {});
      }, 220);
    });
  }

  // rail sosial media
  var rail = document.getElementById('socRail');
  if (rail) {
    var step = function () { var c = rail.querySelector('.soc-card'); return (c ? c.offsetWidth : 340) + 20; };
    var p = document.getElementById('socPrev'), n = document.getElementById('socNext');
    if (p) p.addEventListener('click', function () { rail.scrollBy({ left: -step(), behavior: 'smooth' }); });
    if (n) n.addEventListener('click', function () { rail.scrollBy({ left: step(), behavior: 'smooth' }); });
  }
})();

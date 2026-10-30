/* Admin Berita & Event: drawer tambah/ubah, penyusun isi artikel, saklar tayang, hapus 2 langkah. */
(function () {
  var C = window.NEWS_CFG, LG = ['id', 'en', 'zh'], cur = 'id', MAXMB = 20;
  var $ = function (id) { return document.getElementById(id); };
  var drawer = $('nDr'), ov = $('nOv'), form = $('nForm'), blocks = $('nBlocks');
  var UP = '<svg class="i"><use href="#i-up"/></svg>';
  var ico = function (n) { return '<svg class="i"><use href="#i-' + n + '"/></svg>'; };
  var esc = function (s) { return String(s == null ? '' : s).replace(/[&<>"]/g, function (c) { return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' }[c]; }); };

  function toast(msg) {
    var t = $('tst'); t.textContent = msg; t.hidden = false;
    clearTimeout(toast.t); toast.t = setTimeout(function () { t.hidden = true; }, 3200);
  }

  /* ---------- bahasa: semua isian ID/EN/中文 ikut terkirim, tampilan hanya satu ---------- */
  function applyLang() {
    drawer.querySelectorAll('[data-lg]').forEach(function (el) { el.hidden = el.dataset.lg !== cur; });
    drawer.querySelectorAll('.seg button').forEach(function (b) { b.classList.toggle('on', b.dataset.lang === cur); });
  }

  /* ---------- thumbnail ---------- */
  function setThumb(url) {
    $('nThumbImg').hidden = !url;
    if (url) $('nThumbImg').src = url;
    $('nThumbHint').hidden = !!url;
  }
  function checkSize(input) {
    var f = input.files && input.files[0];
    if (f && f.size > MAXMB * 1024 * 1024) { input.value = ''; toast('Ukuran gambar maksimal ' + MAXMB + ' MB.'); return null; }
    return f || null;
  }

  /* ---------- blok isi artikel ---------- */
  var CTRL = '<button type="button" class="b o s" data-act="up" title="Naikkan">' + ico('chu') + '</button>' +
             '<button type="button" class="b o s" data-act="down" title="Turunkan">' + ico('chd') + '</button>' +
             '<button type="button" class="b o s" data-act="del" title="Hapus blok">' + ico('trash') + '</button>';

  function block(type, d) {
    d = d || {};
    var el = document.createElement('div');
    el.className = 'blk'; el.dataset.type = type;
    var h = '<input type="hidden" name="blocks[0][type]" value="' + type + '">' +
            '<div class="bh"><b class="bn"></b><span>' + CTRL + '</span></div>';
    if (type === 'i') {
      var cap = d.caption || {};
      h += '<input type="hidden" name="blocks[0][existing_image]" value="' + esc(d.image || '') + '">' +
           '<label class="drop"><img alt=""' + (d.image_url ? ' src="' + esc(d.image_url) + '"' : ' hidden') + '>' +
           '<span class="bh2"' + (d.image_url ? ' hidden' : '') + '>' + UP + '<b>Unggah gambar</b></span>' +
           '<input type="file" name="blocks[0][image]" accept="image/jpeg,image/png,image/webp" hidden></label>';
      LG.forEach(function (l) {
        h += '<div data-lg="' + l + '"' + (l === cur ? '' : ' hidden') + '><input name="blocks[0][caption][' + l + ']" maxlength="255" placeholder="Keterangan gambar / indeks (opsional) — ' + l.toUpperCase() + '" value="' + esc(cap[l] || '') + '"></div>';
      });
    } else {
      var tx = d.text || {};
      LG.forEach(function (l) {
        h += '<div data-lg="' + l + '"' + (l === cur ? '' : ' hidden') + '><textarea name="blocks[0][text][' + l + ']" rows="5" placeholder="Tulis paragraf — ' + l.toUpperCase() + '">' + esc(tx[l] || '') + '</textarea></div>';
      });
    }
    el.innerHTML = h;
    return el;
  }
  function renumber() {
    [].forEach.call(blocks.children, function (el, i) {
      el.querySelector('.bn').innerHTML = ico(el.dataset.type === 'i' ? 'image' : 'text') + ' ' + (el.dataset.type === 'i' ? 'Gambar ' : 'Paragraf ') + (i + 1);
      el.querySelectorAll('[name]').forEach(function (f) { f.name = f.name.replace(/^blocks\[\d+\]/, 'blocks[' + i + ']'); });
    });
  }
  function addBlock(type, d) { blocks.appendChild(block(type, d)); renumber(); }

  blocks.addEventListener('click', function (e) {
    var b = e.target.closest('[data-act]'); if (!b) return;
    var el = b.closest('.blk'), a = b.dataset.act;
    if (a === 'up' && el.previousElementSibling) blocks.insertBefore(el, el.previousElementSibling);
    if (a === 'down' && el.nextElementSibling) blocks.insertBefore(el.nextElementSibling, el);
    if (a === 'del') el.remove();
    renumber();
  });
  blocks.addEventListener('change', function (e) {
    var inp = e.target; if (inp.type !== 'file') return;
    var f = checkSize(inp), box = inp.closest('.drop'), img = box.querySelector('img'), hint = box.querySelector('.bh2');
    if (f) { img.src = URL.createObjectURL(f); img.hidden = false; hint.hidden = true; }
  });
  $('nAddP').addEventListener('click', function () { addBlock('p'); });
  $('nAddI').addEventListener('click', function () { addBlock('i'); });

  /* ---------- buka / tutup drawer ---------- */
  function open(item) {
    var edit = !!item;
    form.reset(); blocks.innerHTML = ''; cur = 'id'; applyLang();
    $('nTitle').textContent = edit ? 'Ubah berita' : 'Tambah berita';
    form.action = edit ? item.update_url : C.store;
    $('nMethod').disabled = !edit;
    LG.forEach(function (l) { form.elements['title[' + l + ']'].value = edit ? (item.title[l] || '') : ''; });
    form.elements.category.value = edit ? item.category : 'Event';
    form.elements.published_at.value = edit ? item.published_at : C.today;
    form.elements.is_active.checked = edit ? !!item.is_active : true;
    setThumb(edit ? item.thumbnail_url : null);
    $('nRmWrap').hidden = !(edit && item.thumbnail_url);
    var bl = edit && item.blocks.length ? item.blocks : [{ type: 'p' }];
    bl.forEach(function (b) { addBlock(b.type, b); });
    $('nSave').disabled = false;
    drawer.classList.add('on'); ov.hidden = false;
  }
  function close() { drawer.classList.remove('on'); ov.hidden = true; }

  $('newsAdd').addEventListener('click', function () { open(null); });
  document.querySelectorAll('.js-edit').forEach(function (b) {
    b.addEventListener('click', function () { open(C.data[b.dataset.id]); });
  });
  ov.addEventListener('click', close);
  drawer.querySelectorAll('[data-close]').forEach(function (b) { b.addEventListener('click', close); });
  document.addEventListener('keydown', function (e) { if (e.key === 'Escape') close(); });
  drawer.querySelector('.seg').addEventListener('click', function (e) {
    var b = e.target.closest('button'); if (!b) return; cur = b.dataset.lang; applyLang();
  });
  $('nThumb').addEventListener('change', function () {
    var f = checkSize(this);
    if (!f) return;
    var url = URL.createObjectURL(f);
    setThumb(url);
    var im = new Image();
    im.onload = function () {
        toast('Foto ' + im.naturalWidth + ' × ' + im.naturalHeight + ' px. Di website akan dipotong tengah menjadi 880 × 400 px.');
    };
    im.src = url;
});

  form.addEventListener('submit', function (e) {
    renumber();
    if (!form.elements['title[id]'].value.trim()) { e.preventDefault(); cur = 'id'; applyLang(); toast('Isi judul (ID) dulu ya.'); return; }
    if (!form.elements.published_at.value) { e.preventDefault(); toast('Isi tanggal dulu ya.'); return; }
    $('nSave').disabled = true;   // cegah klik ganda
  });

  /* ---------- saklar tayang ---------- */
  var token = document.querySelector('meta[name="csrf-token"]').content;
  document.querySelectorAll('.js-toggle').forEach(function (sw) {
    sw.addEventListener('change', function () {
      fetch(sw.dataset.url, { method: 'PATCH', headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json' } })
        .then(function (r) { if (!r.ok) throw 0; return r.json(); })
        .then(function (j) { toast(j.is_active ? 'Ditayangkan di website.' : 'Disembunyikan dari website.'); })
        .catch(function () { sw.checked = !sw.checked; toast('Gagal mengubah status. Coba lagi.'); });
    });
  });

  /* ---------- hapus: klik pertama jadi "Yakin?", klik kedua menghapus ---------- */
  document.querySelectorAll('.js-del-btn').forEach(function (b) {
    var html = b.innerHTML, armed = false, tm;
    b.addEventListener('click', function () {
      if (!armed) { armed = true; b.textContent = 'Yakin?'; tm = setTimeout(function () { armed = false; b.innerHTML = html; }, 3000); return; }
      clearTimeout(tm); b.closest('form').submit();
    });
  });

  if (C.openAdd) open(null);   // dari tombol "Tulis berita" di Beranda (?tambah=1)
})();

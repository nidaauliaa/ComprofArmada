/* Admin Lokasi Plant: drawer tambah/ubah, banyak nomor telepon, tempel koordinat, saklar tayang, hapus 2 langkah. */
(function () {
  var C = window.PLANT_CFG;
  var $ = function (id) { return document.getElementById(id); };
  var drawer = $('pDr'), ov = $('pOv'), form = $('pForm'), phBox = $('pPhones');

  function toast(msg) {
    var t = $('tst'); t.textContent = msg; t.hidden = false;
    clearTimeout(toast.t); toast.t = setTimeout(function () { t.hidden = true; }, 3200);
  }

  /* ---------- nomor telepon (bisa lebih dari satu) ---------- */
  function phoneRow(v, nm) {
    var d = document.createElement('div');
    d.className = 'phr';
    d.innerHTML = '<input name="phone_names[]" maxlength="100" autocomplete="off" placeholder="Label / nama (opsional)" style="max-width:38%">' +
                  '<input name="phones[]" maxlength="50" inputmode="tel" autocomplete="off" placeholder="Contoh: 0857 8787 8787">' +
                  '<button type="button" class="b o s js-ph-del" title="Hapus nomor" aria-label="Hapus nomor"><svg class="i"><use href="#i-trash"/></svg></button>';
    var ins = d.querySelectorAll('input');
    ins[0].value = nm || ''; ins[1].value = v || '';
    return d;
  }
  function setPhones(list, names) {
    phBox.innerHTML = '';
    (list && list.length ? list : ['']).forEach(function (v, i) { phBox.appendChild(phoneRow(v, names && names[i])); });
  }
  $('pPhoneAdd').addEventListener('click', function () {
    var r = phoneRow('', ''); phBox.appendChild(r); r.querySelector('input').focus();
  });
  phBox.addEventListener('click', function (e) {
    var b = e.target.closest('.js-ph-del'); if (!b) return;
    var row = b.closest('.phr');
    if (phBox.children.length > 1) row.remove(); else row.querySelectorAll('input').forEach(function (i) { i.value = ''; });
  });

  /* ---------- buka / tutup drawer ---------- */
  function open(item) {
    var edit = !!item;
    form.reset();
    $('pTitle').textContent = edit ? 'Ubah lokasi' : 'Tambah lokasi';
    form.action = edit ? item.update_url : C.store;
    $('pMethod').disabled = !edit;
    form.elements.name.value       = edit ? (item.name || '') : '';
    form.elements.address.value    = edit ? (item.address || '') : '';
    form.elements.name_en.value    = edit ? (item.name_en || '') : '';
    form.elements.name_zh.value    = edit ? (item.name_zh || '') : '';
    form.elements.address_en.value = edit ? (item.address_en || '') : '';
    form.elements.address_zh.value = edit ? (item.address_zh || '') : '';
    form.elements.working_hours.value    = edit ? (item.working_hours || '') : '';
    form.elements.working_hours_en.value = edit ? (item.working_hours_en || '') : '';
    form.elements.working_hours_zh.value = edit ? (item.working_hours_zh || '') : '';
    form.dispatchEvent(new Event('input', { bubbles: true }));
    setPhones(edit ? item.phones : [], edit ? item.phone_names : []);
    form.elements.email.value      = edit ? (item.email || '') : '';
    form.elements.maps_url.value   = edit ? (item.maps_url || '') : '';
    form.elements.lat.value        = edit ? item.lat : '';
    form.elements.lng.value        = edit ? item.lng : '';
    form.elements.sort_order.value = edit ? item.sort_order : C.next;
    form.elements.is_active.checked = edit ? !!item.is_active : true;
    form.elements.is_hq.checked     = edit ? !!item.is_hq : false;
    $('pSave').disabled = false;
    drawer.classList.add('on'); ov.hidden = false;
  }
  function close() { drawer.classList.remove('on'); ov.hidden = true; }

  $('plAdd').addEventListener('click', function () { open(null); });
  document.querySelectorAll('.js-edit').forEach(function (b) {
    b.addEventListener('click', function () { open(C.data[b.dataset.id]); });
  });
  ov.addEventListener('click', close);
  drawer.querySelectorAll('[data-close]').forEach(function (b) { b.addEventListener('click', close); });
  document.addEventListener('keydown', function (e) { if (e.key === 'Escape') close(); });

  /* ---------- tempel "-7.4950, 110.2200" dari Google Maps: isi dua kolom sekaligus ---------- */
  var PAIR = /^\s*(-?\d+(?:[.,]\d+)?)\s*[,;\s]\s*(-?\d+(?:[.,]\d+)?)\s*$/;
  function splitPair(text) {
    var m = PAIR.exec(text || '');
    if (!m) return false;
    form.elements.lat.value = m[1].replace(',', '.');
    form.elements.lng.value = m[2].replace(',', '.');
    return true;
  }
  $('pLat').addEventListener('paste', function (e) {
    var txt = (e.clipboardData || window.clipboardData).getData('text');
    if (splitPair(txt)) e.preventDefault();
  });
  $('pLat').addEventListener('change', function () { splitPair(this.value); });

  /* ---------- validasi singkat sebelum kirim ---------- */
  form.addEventListener('submit', function (e) {
    var f = form.elements;
    var num = function (v) { return isFinite(parseFloat(String(v).replace(',', '.'))); };
    if (!f.name.value.trim())    { e.preventDefault(); toast('Isi nama plant dulu ya.'); return; }
    if (!f.address.value.trim()) { e.preventDefault(); toast('Isi alamat dulu ya.'); return; }
    if (!num(f.lat.value) || !num(f.lng.value)) { e.preventDefault(); toast('Isi latitude dan longitude dulu ya.'); return; }
    $('pSave').disabled = true;   // cegah klik ganda
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
})();

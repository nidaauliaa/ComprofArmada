@extends('layouts.admin')

@section('title', 'Instagram')
@section('page_icon', 'ig')
@section('page_title', 'Instagram')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-gustam.css') }}?v={{ @filemtime(public_path('css/admin-gustam.css')) }}">
@endpush

@section('content')
    <div class="ph">
        <div>
            <h1>Media Sosial</h1>
            <p class="mu">Kelola konten media sosial yang ditampilkan di halaman utama website.</p>
        </div>
        <x-admin-view-page :href="url('/').'#sosmed'" page="Media Sosial" />
    </div>

    @if (session('error'))
        <div class="alert-error">{{ session('error') }}</div>
    @endif
    @error('link')
        <div class="alert-error">{{ $message }}</div>
    @enderror

    <form class="card sp-add" method="POST" action="{{ route('admin.social.store') }}">
        @csrf

        <h3 class="cardh">Tambah Instagram Reel</h3>

        <label for="link">Link Instagram Reel</label>

        <textarea
            id="link"
            name="link"
            rows="2"
            class="sp-input"
            spellcheck="false"
            autocomplete="off"
            required
            placeholder="Tempel link Instagram Reel di sini, contoh: https://www.instagram.com/reel/XXXX/"
        >{{ old('link') }}</textarea>

        <p class="sp-hint">
            Hanya link atau kode embed Instagram Reel publik yang diterima. Story, postingan biasa, profil, dan platform lain akan ditolak.
        </p>

        <div class="sp-row2">
            <button type="submit" class="b">
                <svg class="i"><use href="#i-plus"/></svg>
                Tambahkan Reel
            </button>
        </div>

        <div class="sp-note">
            <svg class="i"><use href="#i-info"/></svg>
            <span>
                Pastikan Reel dapat dilihat secara publik agar thumbnail dan embed-nya bisa diambil.
            </span>
        </div>

        <details class="sp-help">
            <summary>Bagaimana cara mendapatkan link?</summary>

            <ul>
                <li><b>Instagram:</b> Buka Reel → Bagikan → Salin tautan.</li>
                <li>Format link yang valid: <code>instagram.com/reel/KODE/</code>.</li>
            </ul>
        </details>
    </form>

    <div class="sp-head">
        <b>Konten di Website</b>
        <span class="bdg">{{ $posts->where('is_active', true)->count() }} aktif</span>
        <span class="sp-hint">Tarik ikon ⋮⋮ untuk mengubah urutan. Perubahan urutan langsung disimpan.</span>
    </div>

    <div class="sp-sort-list" id="spSortList">
    @forelse ($posts as $p)
        <div class="sp-sort-item" data-sort-id="{{ $p->id }}" draggable="true">
        <div class="row">
            <span class="sp-drag" title="Tarik untuk mengurutkan" aria-hidden="true">⋮⋮</span>
            <div class="ri sp-thumb">
                @if ($p->thumbnail_url)
                    <img src="{{ $p->thumbnail_url }}" alt="Thumbnail {{ $p->title ?: 'Instagram Reel' }}"
                         loading="lazy" referrerpolicy="no-referrer"
                         onerror="this.style.display='none'">
                @endif
            </div>
            <div class="rt">
                <b>{{ $p->title ?: ucfirst($p->platform).' '.ucfirst($p->kind ?: 'reel') }}</b>
                <small>{{ ucfirst($p->platform) }} {{ ucfirst($p->kind ?: 'reel') }}{{ $p->author ? ' · '.$p->author : '' }}</small>
            </div>

            <form method="POST" action="{{ route('admin.social.toggle', $p) }}">
                @csrf @method('PATCH')
                <label class="sw" title="Tampilkan / sembunyikan" style="margin:0">
                    <input type="checkbox" onchange="this.form.submit()" {{ $p->is_active ? 'checked' : '' }}>
                    <i></i>
                </label>
            </form>

            <details class="dd">
                <summary class="b o s" aria-label="Menu lainnya">⋯</summary>
                <div class="dd-menu">
                    <button type="button" data-json="json-{{ $p->id }}">Edit JSON</button>
                    <button type="button" data-preview="{{ $p->safeEmbedUrl() }}" data-w="{{ $p->embedWidth() }}" data-h="{{ $p->embedHeight() }}">Pratinjau</button>
                    <a href="{{ $p->source_url }}" target="_blank" rel="noopener">Buka link asli ↗</a>
                    <hr>
                    <form method="POST" action="{{ route('admin.social.destroy', $p) }}" onsubmit="return confirm('Hapus konten ini dari web?')">@csrf @method('DELETE')<button type="submit" class="danger">Hapus</button></form>
                </div>
            </details>
        </div>

        <dialog class="sp-dialog" id="json-{{ $p->id }}" @if (session('open_json') == $p->id) data-autoopen @endif>
            <form method="POST" action="{{ route('admin.social.update', $p) }}" data-json-form>
                @csrf @method('PUT')
                <div class="dh"><span>Edit JSON — {{ $p->title ?: ucfirst($p->platform) }}</span><button type="button" class="b o s" data-close>Tutup</button></div>
                <p class="sp-hint" style="margin:10px 20px 0">
                    Yang menentukan tampilan: <code>embed_url</code>, <code>width</code>, <code>height</code>, <code>title</code>, <code>author</code>, <code>source_url</code>.
                    <code>embed_url</code> hanya boleh berupa embed Instagram Reel.
                </p>
                <textarea name="json" class="sp-json" spellcheck="false" rows="18">{{ session('open_json') == $p->id ? old('json', $p->editableJsonText()) : $p->editableJsonText() }}</textarea>
                <div class="sp-jfoot">
                    <span class="sp-jstate" data-jstate></span>
                    <button type="button" class="b o s" data-format>Rapikan</button>
                    <button type="submit" class="b s">Simpan JSON</button>
                </div>
            </form>
        </dialog>
        </div>
    @empty
        <div class="card sp-empty">
            <div class="sp-empty-icon">
                <svg class="i"><use href="#i-image"/></svg>
            </div>
            <b>Belum ada konten</b>
            <p class="mu">Konten yang kamu tambahkan nantinya akan ditampilkan di halaman utama website.</p>
        </div>
    @endforelse
    </div>

    <dialog class="sp-dialog" id="sp-preview">
        <div class="dh"><span>Pratinjau</span><button type="button" class="b o s" data-close>Tutup</button></div>
        <div class="sp-pv"></div>
    </dialog>

    <p class="mu" style="font-size:13px">JSON publik: <a href="{{ url('/api/social') }}" target="_blank" style="color:var(--pr);font-weight:600">{{ url('/api/social') }}</a></p>
@endsection

@push('scripts')
<script>
(function () {
  var CSRF = document.querySelector('meta[name="csrf-token"]').content;
  var REORDER = "{{ route('admin.social.reorder') }}";
  var sortList = document.getElementById('spSortList');

  if (sortList) {
    var dragging = null;
    var dragArmed = false;
    var initialOrder = '';
    var items = function () {
      return [].slice.call(sortList.querySelectorAll('.sp-sort-item'));
    };
    var order = function () {
      return items().map(function (item) { return item.dataset.sortId; });
    };

    sortList.addEventListener('pointerdown', function (event) {
      dragArmed = Boolean(event.target.closest('.sp-drag'));
    });
    sortList.addEventListener('pointerup', function () {
      dragArmed = false;
    });

    sortList.addEventListener('dragstart', function (event) {
      var item = event.target.closest('.sp-sort-item');
      if (!item || !dragArmed) {
        event.preventDefault();
        return;
      }

      dragArmed = false;
      dragging = item;
      initialOrder = order().join(',');
      item.classList.add('is-dragging');
      event.dataTransfer.effectAllowed = 'move';
      event.dataTransfer.setData('text/plain', item.dataset.sortId);
    });

    sortList.addEventListener('dragover', function (event) {
      if (!dragging) return;

      event.preventDefault();
      var target = event.target.closest('.sp-sort-item');
      if (!target || target === dragging) return;

      [].slice.call(sortList.querySelectorAll('.is-drop-target')).forEach(function (item) {
        item.classList.remove('is-drop-target');
      });
      target.classList.add('is-drop-target');

      var rect = target.getBoundingClientRect();
      sortList.insertBefore(dragging, event.clientY < rect.top + (rect.height / 2) ? target : target.nextSibling);
    });

    sortList.addEventListener('drop', function (event) {
      if (dragging) event.preventDefault();
    });

    sortList.addEventListener('dragend', function () {
      if (!dragging) return;

      dragging.classList.remove('is-dragging');
      [].slice.call(sortList.querySelectorAll('.is-drop-target')).forEach(function (item) {
        item.classList.remove('is-drop-target');
      });
      dragging = null;

      var ids = order();
      if (ids.join(',') === initialOrder) return;

      fetch(REORDER, {
        method: 'PATCH',
        headers: {
          'X-CSRF-TOKEN': CSRF,
          'Accept': 'application/json',
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ ids: ids })
      }).then(function (response) {
        if (!response.ok) throw new Error();
      }).catch(function () {
        alert('Gagal menyimpan urutan. Halaman akan dimuat ulang.');
        window.location.reload();
      });
    });
  }

  function closeMenus(except) {
    [].slice.call(document.querySelectorAll('details.dd[open]')).forEach(function (m) { if (!except || !m.contains(except)) m.removeAttribute('open'); });
  }
  document.addEventListener('click', function (e) {
    var j = e.target.closest('[data-json]');
    if (j) { var d = document.getElementById(j.getAttribute('data-json')); if (d && d.showModal) d.showModal(); closeMenus(); return; }

    var pv = e.target.closest('[data-preview]');
    if (pv) {
      var box = document.getElementById('sp-preview'), holder = box.querySelector('.sp-pv'), src = pv.getAttribute('data-preview');
      holder.innerHTML = '';
      if (src) {
        var f = document.createElement('iframe');
        f.src = src; f.width = pv.getAttribute('data-w'); f.height = pv.getAttribute('data-h');
        f.setAttribute('scrolling', 'no'); f.setAttribute('allowfullscreen', ''); f.style.border = '0'; f.style.maxWidth = '100%';
        holder.appendChild(f);
      } else { holder.textContent = 'Alamat embed tidak valid.'; }
      box.showModal(); closeMenus(); return;
    }

    if (e.target.closest('[data-close]')) { var dd = e.target.closest('dialog'); if (dd) { dd.close(); var h = dd.querySelector('.sp-pv'); if (h) h.innerHTML = ''; } }
    if (e.target.tagName === 'DIALOG') { e.target.close(); var h2 = e.target.querySelector('.sp-pv'); if (h2) h2.innerHTML = ''; }
    closeMenus(e.target);
  });

  // editor JSON: cek validitas langsung + tombol Rapikan
  [].slice.call(document.querySelectorAll('[data-json-form]')).forEach(function (form) {
    var ta = form.querySelector('textarea'), st = form.querySelector('[data-jstate]');
    function check() {
      try { JSON.parse(ta.value); st.textContent = '✓ JSON valid'; st.className = 'sp-jstate ok'; return true; }
      catch (e) { st.textContent = '✗ ' + e.message; st.className = 'sp-jstate bad'; return false; }
    }
    ta.addEventListener('input', check); check();
    form.querySelector('[data-format]').addEventListener('click', function () {
      try { ta.value = JSON.stringify(JSON.parse(ta.value), null, 2); } catch (e) {}
      check();
    });
    form.addEventListener('submit', function (e) { if (!check()) { e.preventDefault(); ta.focus(); } });
  });

  var auto = document.querySelector('dialog[data-autoopen]');
  if (auto && auto.showModal) auto.showModal();
})();
</script>
@endpush

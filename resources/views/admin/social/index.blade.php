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
            <h1>Instagram</h1>
            <p class="mu">Tempel link atau kode embed dari Instagram, Facebook, TikTok, atau YouTube. Konten langsung tampil di Home.</p>
        </div>
    </div>

    @if (session('error'))
        <div class="alert-error">{{ session('error') }}</div>
    @endif
    @error('link')
        <div class="alert-error">{{ $message }}</div>
    @enderror

    <form class="card sp-add" method="POST" action="{{ route('admin.social.store') }}">
        @csrf
        <label for="link">Link share atau kode embed (oEmbed)
            <small>Bisa salah satu: link share · kode <b>&lt;iframe …&gt;</b> dari tombol Embed · JSON oEmbed</small>
        </label>
        <textarea id="link" name="link" rows="4" class="sp-input" spellcheck="false" autocomplete="off" required
            placeholder="https://www.instagram.com/reel/…   atau   &lt;iframe src=&quot;https://www.facebook.com/plugins/video.php?…&quot; …&gt;&lt;/iframe&gt;">{{ old('link') }}</textarea>
        <div class="sp-row2">
            <button type="submit" class="b r"><svg class="i"><use href="#i-plus"/></svg> Tampilkan di web</button>
            <span class="sp-hint">Akun/konten harus <b>publik</b>. Tidak ada token yang disimpan.</span>
        </div>
        <details class="sp-help">
            <summary>Dapat dari mana?</summary>
            <ul>
                <li><b>Instagram</b> — postingan/reel → ⋯ → Bagikan → Salin link <i>(atau</i> ⋯ → Sematkan → Salin kode<i>)</i>.</li>
                <li><b>Facebook</b> — postingan/video/reel → Bagikan → Salin link. Bila link share gagal, buka ⋯ → <b>Embed / Sematkan</b>, salin kode <code>&lt;iframe …&gt;</code>, lalu tempel di sini.</li>
                <li><b>TikTok</b> — video → Bagikan → Salin link <i>(atau</i> Embed → Copy code<i>)</i>.</li>
                <li><b>YouTube</b> — Bagikan → Salin link, atau Sematkan → salin kode iframe.</li>
            </ul>
            <p class="sp-hint">Demi keamanan, hanya alamat embed dari domain resmi platform yang diterima. Script dan HTML lain pada kode embed dibuang otomatis.</p>
        </details>
    </form>

    <div class="sp-head"><b>Tampil di web</b> <span class="bdg">{{ $posts->where('is_active', true)->count() }} aktif</span></div>

    @forelse ($posts as $p)
        <div class="row">
            <div class="ri sp-ic sp-{{ $p->platform }}">{{ strtoupper(substr($p->platform, 0, 1)) }}</div>
            <div class="rt">
                <b>{{ $p->title ?: ucfirst($p->platform).' · '.($p->kind ?: 'konten') }}</b>
                <small>{{ ucfirst($p->platform) }} · {{ $p->kind }}{{ $p->author ? ' · '.$p->author : '' }}</small>
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
                    <form method="POST" action="{{ route('admin.social.move', [$p, 'up']) }}">@csrf @method('PATCH')<button type="submit">↑ Naikkan</button></form>
                    <form method="POST" action="{{ route('admin.social.move', [$p, 'down']) }}">@csrf @method('PATCH')<button type="submit">↓ Turunkan</button></form>
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
                    <code>embed_url</code> hanya boleh dari Instagram / Facebook / TikTok / YouTube / LinkedIn.
                </p>
                <textarea name="json" class="sp-json" spellcheck="false" rows="18">{{ session('open_json') == $p->id ? old('json', $p->editableJsonText()) : $p->editableJsonText() }}</textarea>
                <div class="sp-jfoot">
                    <span class="sp-jstate" data-jstate></span>
                    <button type="button" class="b o s" data-format>Rapikan</button>
                    <button type="submit" class="b r s">Simpan JSON</button>
                </div>
            </form>
        </dialog>
    @empty
        <div class="card mu">Belum ada konten. Tempel link atau kode embed di atas untuk memulai.</div>
    @endforelse

    <dialog class="sp-dialog" id="sp-preview">
        <div class="dh"><span>Pratinjau</span><button type="button" class="b o s" data-close>Tutup</button></div>
        <div class="sp-pv"></div>
    </dialog>

    <p class="mu" style="font-size:13px">JSON publik: <a href="{{ url('/api/social') }}" target="_blank" style="color:var(--pr);font-weight:600">{{ url('/api/social') }}</a></p>
@endsection

@push('scripts')
<script>
(function () {
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

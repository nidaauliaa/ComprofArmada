@extends('layouts.admin')

@section('title', 'Tentang Kami')
@section('page_icon', 'text')
@section('page_title', 'Tentang Kami')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-gustam.css') }}?v={{ @filemtime(public_path('css/admin-gustam.css')) }}">
    <style>
        .ab-ok{background:#dcfce7;color:#166534;padding:10px 14px;border-radius:8px;margin-bottom:16px;font-weight:600}
        .ab-in,.lt-pane input,.lt-pane textarea,select.ab-in{width:100%;box-sizing:border-box;border:1px solid #d1d5db;border-radius:8px;padding:9px 12px;font:inherit;background:#fff}
        .ab-lbl {
            display: flex;
            flex-direction: column;
            gap: 6px;
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 0; /* Hilangkan margin bawah agar grid sejajar pas */
        }
        .ab-lbl small {
            font-weight: 400;
            color: #64748b;
        }
        .ab-lbl-inline {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            width: 100%;
        }
        .ab-yr{max-width:190px}
        .ab-grid2{display:grid;grid-template-columns:1fr 1fr;gap:16px}
        .ab-hint{margin:0 0 16px;color:#64748b;font-size:14px}
        .ab-photo{display:block;max-width:220px;width:100%;border-radius:12px;margin:10px 0;border:1px solid #e5e7eb}
        .rp-row{border:1px solid #e5e7eb;border-radius:12px;padding:16px;margin-bottom:14px;background:#fafbfc}
        .rp-body{width:100%}
        .rp-cols{display:grid;grid-template-columns:1fr 1fr;gap:16px}
        .rp-years {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            align-items: end; /* Membuat elemen di dalam grid sejajar bagian bawahnya */
            margin-bottom: 8px;
        }
        .ab-add{margin-top:4px}

        /* Navigasi Tab Seksi Admin */
        .admin-section-tabs {
            display: flex;
            gap: 8px;
            background: #e2e8f0;
            padding: 6px;
            border-radius: 12px;
            margin-bottom: 24px;
            overflow-x: auto;
        }
        .ast-btn {
            background: transparent;
            border: none;
            padding: 10px 18px;
            font-weight: 600;
            font-size: 14px;
            color: #475569;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
            white-space: nowrap;
        }
        .ast-btn:hover { color: #0f172a; }
        .ast-btn.active {
            background: #fff;
            color: #2563eb;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }
        .form-section-pane { display: none; }
        .form-section-pane.active { display: block; }
        .form-navigation-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }

        @media(max-width:800px){.ab-grid2,.rp-cols{grid-template-columns:1fr}}
    </style>
@endpush

@section('content')
    @php
        $d = $about->data();
        $currentYear = (int) date('Y');

        // Default 4 kartu statistik jika kosong
        $statRows = old('stats', $d['stats'] ?? [
            ['value' => [], 'since' => 1962, 'label' => []],
            ['value' => [], 'since' => null, 'label' => []],
            ['value' => [], 'since' => null, 'label' => []],
            ['value' => [], 'since' => null, 'label' => []],
        ]);
        $statRows = is_array($statRows) ? array_values($statRows) : [];

        // Default 5 poin filosofi jika kosong
        $philRows = old('philosophy', $d['philosophy'] ?? [[], [], [], [], []]);
        $philRows = is_array($philRows) ? array_values($philRows) : [];
        $philRows['__I__'] = [];

        $histRows = old('history', $d['history'] ?? []);
        $histRows = is_array($histRows) ? $histRows : [];
        $histRows['__I__'] = ['from' => null, 'to' => null, 'text' => []];
    @endphp

    <div class="ph">
        <div>
            <h1>Tentang Kami</h1>
            <p class="mu">Pilih bagian yang ingin dikelola melalui tab di bawah ini agar pengisian data lebih fokus dan terstruktur.</p>
        </div>
    </div>

    @if (session('success'))
        <div class="ab-ok">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert-error">
            <b>Belum tersimpan:</b>
            <ul>@foreach (collect($errors->all())->unique()->take(6) as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.about.update') }}" enctype="multipart/form-data" id="mainAboutForm">
        @csrf
        @method('PUT')

        {{-- TAB NAVIGASI UTAMA --}}
        <div class="admin-section-tabs">
            <button type="button" class="ast-btn active" data-target="sec-hero">1. Hero & Statistik</button>
            <button type="button" class="ast-btn" data-target="sec-profile">2. Profil & Direktur</button>
            <button type="button" class="ast-btn" data-target="sec-visimisi">3. Visi & Misi</button>
            <button type="button" class="ast-btn" data-target="sec-philosophy">4. Filosofi</button>
            <button type="button" class="ast-btn" data-target="sec-history">5. Sejarah</button>
        </div>

        {{-- 1. Bagian atas (Hero & Statistik) --}}
        <div class="form-section-pane active" id="sec-hero">
            <div class="card">
                <h3 class="cardh">Bagian Atas (Hero)</h3>
                <x-lang-tabs name="hero_badge" label="Label kecil di atas judul" :values="$d['hero_badge'] ?? []" />
                <x-lang-tabs name="hero_title" label="Judul" :values="$d['hero_title'] ?? []" />
                <x-lang-tabs name="hero_accent" label="Lanjutan judul (tampil berwarna)" :values="$d['hero_accent'] ?? []" />
                <x-lang-tabs name="hero_lead" label="Kalimat pengantar" type="textarea" :rows="3" :values="$d['hero_lead'] ?? []" />
            </div>

            <div class="card">
                <h3 class="cardh">Angka Statistik</h3>
                <p class="ab-hint">Kartu pertama otomatis menghitung selisih tahun pengalaman sejak tahun berdiri. Kartu lainnya cukup diisi angka manual.</p>

                <div>
                    @foreach ($statRows as $i => $r)
                        <div class="rp-row">
                            <div class="rp-body">
                                <div class="rp-cols">
                                    @if ($i === 0)
                                        <label class="ab-lbl">Tahun berdiri (hitung otomatis pengalaman)
                                            <input class="ab-in" type="number" min="1800" max="{{ $currentYear }}" name="stats[{{ $i }}][since]" value="{{ $r['since'] ?? 1962 }}" placeholder="mis. 1962">
                                        </label>
                                    @else
                                        <x-lang-tabs name="stats[{{ $i }}][value]" label="Angka (mis. 25 Ha, 1.500+)" :values="$r['value'] ?? []" />
                                    @endif

                                    <x-lang-tabs name="stats[{{ $i }}][label]" label="Keterangan" :values="$r['label'] ?? []" />
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- 2. Direktur & ringkasan perusahaan --}}
        <div class="form-section-pane" id="sec-profile">
            <div class="card">
                <h3 class="cardh">Direktur & Ringkasan Perusahaan</h3>

                <div style="margin-bottom:14px">
                    <b>Foto direktur</b>
                    <small class="mu">JPG/PNG/WEBP, maks. 4 MB. Disarankan potret tegak (rasio 4:5).</small>
                    @if ($about->photoUrl())
                        <img class="ab-photo" src="{{ $about->photoUrl() }}" alt="Foto direktur">
                        <label style="font-weight:500;margin:8px 0;display:block">
                            <input type="checkbox" name="remove_director_photo" value="1"> Hapus foto
                        </label>
                    @endif
                    <input type="file" name="director_photo" accept="image/jpeg,image/png,image/webp">
                </div>

                <div class="ab-grid2">
                    <label class="ab-lbl">Nama direktur
                        <input class="ab-in" type="text" name="director_name" maxlength="150" value="{{ old('director_name', $d['director_name'] ?? '') }}">
                    </label>
                    <x-lang-tabs name="director_title" label="Jabatan" :values="$d['director_title'] ?? []" />
                </div>

                <x-lang-tabs name="overview_title" label="Judul ringkasan" :values="$d['overview_title'] ?? []" />
                <x-lang-tabs name="overview_body" label="Isi ringkasan" type="textarea" :rows="10" :values="$d['overview_body'] ?? []"
                             help="Pisahkan paragraf dengan satu baris kosong. **teks** untuk tebal, *teks* untuk miring." />
            </div>
        </div>

        {{-- 3. Visi & Misi --}}
        <div class="form-section-pane" id="sec-visimisi">
            <div class="card">
                <h3 class="cardh">Visi & Misi</h3>
                <x-lang-tabs name="vision_heading" label="Judul Visi" :values="$d['vision_heading'] ?? []" />
                <x-lang-tabs name="vision" label="Isi Visi" type="textarea" :rows="3" :values="$d['vision'] ?? []"
                             help="**teks** untuk tebal, *teks* untuk miring." />
                <x-lang-tabs name="mission_heading" label="Judul Misi" :values="$d['mission_heading'] ?? []" />
                <x-lang-tabs name="mission" label="Isi Misi" type="textarea" :rows="5" :values="$d['mission'] ?? []"
                             help="Satu baris = satu poin misi." />
            </div>
        </div>

        {{-- 4. Filosofi --}}
        <div class="form-section-pane" id="sec-philosophy">
            <div class="card">
                <h3 class="cardh">Filosofi Perusahaan</h3>
                <x-lang-tabs name="philosophy_title" label="Judul bagian" :values="$d['philosophy_title'] ?? []" />
                <x-lang-tabs name="philosophy_sub" label="Kalimat di bawah judul" :values="$d['philosophy_sub'] ?? []" />

                <b style="display:block;margin:16px 0 10px">Poin filosofi</b>
                <div data-repeat="philosophy">
                    @foreach ($philRows as $i => $r)
                        @if ($i === '__I__')<template id="tpl-philosophy">@endif
                        <div class="rp-row">
                            <div class="rp-body">
                                <x-lang-tabs name="philosophy[{{ $i }}]" label="Teks poin" :values="is_array($r) ? $r : []" />
                            </div>
                        </div>
                        @if ($i === '__I__')</template>@endif
                    @endforeach
                </div>
                <button type="button" class="b o s ab-add" data-add="philosophy"><svg class="i"><use href="#i-plus"/></svg> Tambah poin</button>
            </div>
        </div>

        {{-- 5. Sejarah --}}
        <div class="form-section-pane" id="sec-history">
            <div class="card">
                <h3 class="cardh">Sejarah & Perjalanan</h3>
                <x-lang-tabs name="history_title" label="Judul bagian" :values="$d['history_title'] ?? []" />
                <x-lang-tabs name="history_sub" label="Kalimat di bawah judul" :values="$d['history_sub'] ?? []" />

                <b style="display:block;margin:16px 0 10px">Tahapan sejarah</b>
                <div data-repeat="history">
                    @foreach ($histRows as $i => $r)
                        @if ($i === '__I__')<template id="tpl-history">@endif
                        <div class="rp-row">
                            <div class="rp-body">
                                <div class="rp-years">
                                    <label class="ab-lbl">
                                        Tahun awal
                                        <select class="ab-in" name="history[{{ $i }}][from]">
                                            <option value="">-- Pilih Tahun --</option>
                                            @for ($y = $currentYear; $y >= 1950; $y--)
                                                <option value="{{ $y }}" {{ (string)($r['from'] ?? '') === (string)$y ? 'selected' : '' }}>{{ $y }}</option>
                                            @endfor
                                        </select>
                                    </label>

                                    <label class="ab-lbl">
                                        <div class="ab-lbl-inline">
                                            <span>Tahun akhir</span>
                                            <small>(kosongkan jika hanya 1 tahun)</small>
                                        </div>
                                        <select class="ab-in" name="history[{{ $i }}][to]">
                                            <option value="">-- Kosongkan --</option>
                                            @for ($y = $currentYear; $y >= 1950; $y--)
                                                <option value="{{ $y }}" {{ (string)($r['to'] ?? '') === (string)$y ? 'selected' : '' }}>{{ $y }}</option>
                                            @endfor
                                        </select>
                                    </label>
                                </div>
                                <x-lang-tabs name="history[{{ $i }}][text]" label="Keterangan" type="textarea" :rows="3" :values="$r['text'] ?? []"
                                             help="**teks** untuk tebal, *teks* untuk miring." />
                            </div>
                        </div>
                        @if ($i === '__I__')</template>@endif
                    @endforeach
                </div>
                <button type="button" class="b o s ab-add" data-add="history"><svg class="i"><use href="#i-plus"/></svg> Tambah tahapan</button>
            </div>
        </div>

        {{-- TOMBOL SIMPAN UTAMA DI BAWAH --}}
        <div class="form-navigation-footer">
            <span class="mu">Pastikan semua data sudah terisi dengan benar sebelum menyimpan.</span>
            <button type="submit" class="b r">Simpan Perubahan</button>
        </div>
    </form>
@endsection

@push('scripts')
<script>
(function () {
  var counter = 0;

  // Script Tab Navigasi Seksi Admin
  var sectionBtns = document.querySelectorAll('.ast-btn');
  var sectionPanes = document.querySelectorAll('.form-section-pane');

  sectionBtns.forEach(function(btn) {
    btn.addEventListener('click', function() {
      var targetId = btn.getAttribute('data-target');

      sectionBtns.forEach(function(b) { b.classList.remove('active'); });
      sectionPanes.forEach(function(p) { p.classList.remove('active'); });

      btn.classList.add('active');
      document.getElementById(targetId).classList.add('active');
      window.scrollTo({ top: 120, behavior: 'smooth' });
    });
  });

  // Tab bahasa untuk baris yang baru ditambahkan
  function initLt(box) {
    var tabs = [].slice.call(box.querySelectorAll('[data-lt-tab]'));
    var panes = [].slice.call(box.querySelectorAll('[data-lt-pane]'));
    function mark() {
      panes.forEach(function (p) {
        var code = p.getAttribute('data-lt-pane'), inp = p.querySelector('[data-lt-input]');
        var tab = tabs.filter(function (t) { return t.getAttribute('data-lt-tab') === code; })[0];
        if (tab) tab.querySelector('.lt-dot').hidden = !!(inp && inp.value.trim() !== '');
      });
    }
    tabs.forEach(function (t) {
      t.addEventListener('click', function () {
        var c = t.getAttribute('data-lt-tab');
        tabs.forEach(function (x) { x.classList.toggle('on', x === t); });
        panes.forEach(function (p) { p.classList.toggle('on', p.getAttribute('data-lt-pane') === c); });
      });
    });
    box.addEventListener('input', mark);
    mark();
  }

  [].slice.call(document.querySelectorAll('[data-add]')).forEach(function (btn) {
    btn.addEventListener('click', function () {
      var key = btn.getAttribute('data-add');
      var tpl = document.getElementById('tpl-' + key);
      var host = document.querySelector('[data-repeat="' + key + '"]');
      if (!tpl || !host) return;
      var id = 'n' + (++counter);
      var wrap = document.createElement('div');
      wrap.innerHTML = tpl.innerHTML.replace(/__I__/g, id).trim();
      var row = wrap.firstElementChild;
      host.appendChild(row);
      [].slice.call(row.querySelectorAll('[data-lt]')).forEach(initLt);
      var f = row.querySelector('select, input, textarea');
      if (f) f.focus();
    });
  });
})();
</script>
@endpush

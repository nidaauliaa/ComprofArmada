@extends('layouts.admin')

@section('title', 'Kontak')
@section('page_icon', 'phone')
@section('page_title', 'Kontak')

@push('styles')
{{-- memakai gaya yang sama dengan halaman Berita / Lokasi Plant --}}
<link rel="stylesheet" href="{{ asset('css/admin-news.css') }}?v={{ @filemtime(public_path('css/admin-news.css')) }}">
<style>
    .sc-ok{background:#dcfce7;color:#166534;padding:10px 14px;border-radius:8px;margin-bottom:16px}
    .sc-add{display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end;padding:18px;margin-bottom:20px;border:1px solid #e5e7eb;border-radius:12px;background:#fff}
    .sc-add label{display:flex;flex-direction:column;gap:6px;font-weight:600;font-size:14px;flex:1;min-width:180px}
    .sc-add label.sc-sm{flex:0 0 100px;min-width:100px}
    .sc-in,.sc-add input,.sc-f input{border:1px solid #d1d5db;border-radius:8px;padding:9px 12px;font:inherit;background:#fff;margin:0}
    .sc-f{display:flex;gap:8px;flex:1;flex-wrap:wrap;min-width:0}
    .sc-f input{flex:1;min-width:140px}
    .sc-f input.sc-n{flex:0 0 80px;min-width:80px}
    .sc-st{font-size:13px;padding:3px 10px;border-radius:999px;white-space:nowrap}
    .sc-st.on{background:#dcfce7;color:#166534}
    .sc-st.off{background:#e5e7eb;color:#4b5563}
    .sc-inline{margin:0}
    .sc-texts{padding:20px;margin-top:28px;border:1px solid #e5e7eb;border-radius:12px;background:#fff}
    .sc-texts h3{margin:0 0 4px;font-size:16px}
    .sc-texts .sc-hint{margin:0 0 16px;color:#64748b;font-size:14px}
    .sc-hqphones{display:grid;gap:8px;margin-bottom:14px}
    .sc-prow{display:flex;gap:8px}
    .sc-prow input{flex:1}
    .sc-prow input.sc-pn{flex:0 0 36%}
    .sc-hqphones input,.sc-hql input{width:100%;box-sizing:border-box;border:1px solid #d1d5db;border-radius:8px;padding:9px 12px;font:inherit;background:#fff}
    .sc-hql{display:flex;flex-direction:column;gap:6px;font-weight:600;font-size:14px;margin-bottom:12px}
    .sc-texts .lt-label{font-size:14px}
    .sc-texts .lt-pane input,.sc-texts .lt-pane textarea{width:100%;box-sizing:border-box;border:1px solid #d1d5db;border-radius:8px;padding:9px 12px;font:inherit;background:#fff}
    .sc-texts .lt-pane textarea{resize:vertical}
</style>
@endpush

@section('content')
    @if (session('status'))
        <div class="sc-ok">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert-error">
            <b>Belum tersimpan:</b>
            <ul>@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="ph">
        <div>
            <h1>Kontak</h1>
            <p class="mu">Nomor di sini <b>sama persis</b> dengan nomor Plant HQ di menu Lokasi Plant. Diubah dari sini atau dari sana hasilnya sama, dan tampil di halaman Kontak, peta, serta popup lokasi. Nomor boleh diawali 0812… atau +62 812…, link WhatsApp dibuat otomatis.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.sales.hq') }}" class="sc-texts sc-hq" style="margin-top:0">
        @csrf @method('PUT')
        <h3>Nomor &amp; info Kantor Pusat (HQ)@if ($hq) · {{ $hq->name }}@endif</h3>

        @if ($errors->has('hq'))
            <div class="alert-error">{{ $errors->first('hq') }}</div>
        @elseif (! $hq)
            <div class="emp">Belum ada Plant yang ditandai HQ. Centang "Kantor pusat (HQ)" di menu Lokasi Plant.</div>
        @else
            @php
                $oldPhones = old('phones');
                $rows = $oldPhones !== null
                    ? collect($oldPhones)->map(fn ($v, $i) => ['name' => old('phone_names.'.$i, ''), 'phone' => $v])->all()
                    : $hq->phoneEntries();
                if (! $rows) { $rows = [['name' => '', 'phone' => '']]; }
            @endphp

            <div class="sc-hqphones" id="scPhones">
                <b>Nomor telepon / WhatsApp <small class="mu">label (mis. nama sales) boleh dikosongkan</small></b>
                @foreach ($rows as $r)
                    <div class="sc-prow">
                        <input class="sc-pn" name="phone_names[]" value="{{ $r['name'] }}" maxlength="100" placeholder="Label / nama sales">
                        <input type="tel" name="phones[]" value="{{ $r['phone'] }}" maxlength="50" placeholder="0857 8787 8787 atau +62 857 …">
                        <button type="button" class="b o s js-sc-del" aria-label="Hapus nomor"><svg class="i"><use href="#i-trash"/></svg></button>
                    </div>
                @endforeach
            </div>
            <button type="button" class="b o s" id="scAdd" style="margin-bottom:16px"><svg class="i"><use href="#i-plus"/></svg> Tambah nomor</button>

            <label class="sc-hql">Email
                <input type="email" name="email" maxlength="150" value="{{ old('email', $hq->email) }}" placeholder="marketing@newarmada.co.id">
            </label>
            <label class="sc-hql">Jam kerja (ID)
                <input name="working_hours" maxlength="255" value="{{ old('working_hours', $hq->working_hours) }}">
            </label>
            <label class="sc-hql">Jam kerja (EN)
                <input name="working_hours_en" maxlength="255" value="{{ old('working_hours_en', $hq->working_hours_en) }}">
            </label>
            <label class="sc-hql">Jam kerja (中文)
                <input name="working_hours_zh" maxlength="255" value="{{ old('working_hours_zh', $hq->working_hours_zh) }}">
            </label>
            <button type="submit" class="b">Simpan</button>
        @endif
    </form>

    {{-- Teks halaman Kontak: terjemahan diisi manual (ID | EN | 中文) --}}
    <form method="POST" action="{{ route('admin.sales.texts') }}" class="sc-texts">
        @csrf
        <h3>Teks halaman Kontak</h3>
        <p class="sc-hint">Terjemahan diisi manual lewat tab ID, EN, dan 中文. Titik oranye berarti terjemahannya belum diisi; website memakai Bahasa Indonesia untuk bagian itu.</p>

        <x-lang-tabs name="contact_title" label="Judul halaman"
                     :values="$texts['contact_title']" />
        <x-lang-tabs name="contact_lead" label="Kalimat pembuka (cetak tebal)"
                     :values="$texts['contact_lead']" />
        <x-lang-tabs name="contact_intro" label="Paragraf pengantar" type="textarea" :rows="3"
                     :values="$texts['contact_intro']" />
        <x-lang-tabs name="contact_sales_heading" label="Judul daftar sales"
                     :values="$texts['contact_sales_heading']" />
        <x-lang-tabs name="contact_wa_greeting" label="Awal pesan WhatsApp"
                     help="Nama produk ditambahkan di belakang teks ini saat pengunjung datang dari halaman produk."
                     :values="$texts['contact_wa_greeting']" />

        <button type="submit" class="b">Simpan teks</button>
    </form>
@endsection

@push('scripts')
<script>
(function () {
  var box = document.getElementById('scPhones'); if (!box) return;
  function rowHtml() {
    return '<input class="sc-pn" name="phone_names[]" maxlength="100" placeholder="Label / nama sales">' +
           '<input type="tel" name="phones[]" maxlength="50" placeholder="0857 8787 8787 atau +62 857 …">' +
           '<button type="button" class="b o s js-sc-del" aria-label="Hapus nomor"><svg class="i"><use href="#i-trash"/></svg></button>';
  }
  document.getElementById('scAdd').addEventListener('click', function () {
    var d = document.createElement('div'); d.className = 'sc-prow'; d.innerHTML = rowHtml();
    box.appendChild(d); d.querySelectorAll('input')[1].focus();
  });
  box.addEventListener('click', function (e) {
    var b = e.target.closest('.js-sc-del'); if (!b) return;
    var rows = box.querySelectorAll('.sc-prow');
    if (rows.length > 1) b.closest('.sc-prow').remove();
    else b.closest('.sc-prow').querySelectorAll('input').forEach(function (i) { i.value = ''; });
  });
})();
</script>
@endpush

{{--
  Komponen tab bahasa ID | EN | 中文 untuk form admin.

  Hanya EN & 中文 (kolom Indonesia sudah ada di atas): :only="['en','zh']" ; beri id elemen dengan id-base="cName" -> cNameEn, cNameZh.

  Pakai:
    <x-lang-tabs name="title" label="Judul" :values="['id'=>'..','en'=>'..','zh'=>'..']" />            -> name="title[id]" title[en] title[zh]
    <x-lang-tabs name="title" label="Judul" mode="suffix" :values="[...]" />                          -> name="title" title_en title_zh (gaya kolom banner)
    <x-lang-tabs name="description" label="Deskripsi" type="textarea" :rows="4" :values="[...]" />

  Tab yang masih kosong diberi titik oranye supaya marketing tahu terjemahan belum lengkap.
  Terjemahan diisi manual; bila kosong, website memakai Bahasa Indonesia.
--}}
@props(['name', 'label' => '', 'type' => 'text', 'values' => [], 'mode' => 'array', 'rows' => 3, 'help' => null, 'only' => null, 'idBase' => null])
@php
    $locales = config('site.locales');
    if (is_array($only)) { $locales = array_intersect_key($locales, array_flip($only)); }
    $elId = fn ($c) => $idBase ? ($c === 'id' ? $idBase : $idBase.ucfirst($c)) : null;
    $fieldName = fn ($c) => $mode === 'suffix' ? ($c === 'id' ? $name : $name.'_'.$c) : $name.'['.$c.']';
    $oldKey = fn ($c) => $mode === 'suffix' ? ($c === 'id' ? $name : $name.'_'.$c) : $name.'.'.$c;
    $short = fn ($c) => $c === 'zh' ? '中文' : strtoupper($c);
@endphp
<div class="lt" data-lt>
    @if ($label)<div class="lt-label">{{ $label }}</div>@endif
    <div class="lt-bar" role="tablist">
        @foreach ($locales as $code => $n)
            <button type="button" role="tab" class="lt-tab {{ $loop->first ? 'on' : '' }}" data-lt-tab="{{ $code }}">{{ $short($code) }}<i class="lt-dot" hidden></i></button>
        @endforeach
    </div>
    @foreach ($locales as $code => $n)
        @php $val = old($oldKey($code), $values[$code] ?? ''); @endphp
        <div class="lt-pane {{ $loop->first ? 'on' : '' }}" data-lt-pane="{{ $code }}">
            @if ($type === 'textarea')
                <textarea name="{{ $fieldName($code) }}" @if ($elId($code)) id="{{ $elId($code) }}" @endif rows="{{ $rows }}" data-lt-input>{{ $val }}</textarea>
            @else
                <input type="text" name="{{ $fieldName($code) }}" @if ($elId($code)) id="{{ $elId($code) }}" @endif value="{{ $val }}" data-lt-input>
            @endif
        </div>
    @endforeach
    @if ($help)<small class="lt-help">{{ $help }}</small>@endif
</div>

@once
<style>
.lt{margin-bottom:14px}
.lt-label{font-weight:600;margin-bottom:6px}
.lt-bar{display:inline-flex;border:1px solid var(--ln,#e2e8f0);border-radius:99px;padding:3px;margin-bottom:8px;background:#fff}
.lt-tab{position:relative;border:0;background:none;color:var(--tx,#0f172a);padding:5px 16px;border-radius:99px;font-weight:600;cursor:pointer;font-family:inherit}
.lt-tab.on{background:var(--pr,#3b82f6);color:#fff}
.lt-dot{position:absolute;top:4px;right:6px;width:7px;height:7px;border-radius:50%;background:#f59e0b}
.lt-pane{display:none}.lt-pane.on{display:block}
.lt-pane input,.lt-pane textarea{margin-top:0!important}
.lt-help{display:block;color:var(--mu,#64748b);margin-top:4px;font-size:13px}
</style>
<script>
document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('[data-lt]').forEach(function (box) {
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
  });
});
</script>
@endonce

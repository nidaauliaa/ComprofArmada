{{-- Footer bersama (data dari Pengaturan Situs di admin) --}}
@php
    $footCats = collect();
    try { $footCats = \App\Models\Category::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get(); } catch (\Throwable $e) {}
    $socials = ['instagram' => 'Instagram', 'facebook' => 'Facebook', 'youtube' => 'YouTube', 'tiktok' => 'TikTok', 'linkedin' => 'LinkedIn'];
    $shown = 0;
@endphp
<footer>
  <div class="wrap">
    <div class="footer-grid">
      <div>
        <a class="logo" href="{{ url('/') }}" style="color:#fff">
          @if (!empty($site['logo']))<img src="{{ asset('storage/'.$site['logo']) }}" alt="{{ $site['site_name'] ?? 'New Armada' }}" style="height:34px;width:auto;display:block">@else<span class="logo-icon"></span>NEW ARMADA @endif
        </a>
        <p style="max-width:32ch;margin-top:12px;font-size:15px">{{ __('site.footer_tagline') }}</p>
        @if (!empty($site['address']))<p style="max-width:34ch;font-size:14px;margin:6px 0">{{ $site['address'] }}</p>@endif
        @if (!empty($site['phone']))<p style="font-size:14px;margin:4px 0">{{ $site['phone'] }}</p>@endif
        @if (!empty($site['email_marketing']))<p style="font-size:14px;margin:4px 0"><a href="mailto:{{ $site['email_marketing'] }}">{{ $site['email_marketing'] }}</a></p>@endif
      </div>
      <div><h4>{{ __('site.products') }}</h4><ul>
        @foreach ($footCats as $fc)
          <li><a href="{{ route('product.index', ['kategori' => $fc->slug]) }}">{{ $fc->t('name') }}</a></li>
        @endforeach
      </ul></div>
      <div><h4>{{ __('site.company') }}</h4><ul>
        <li><a href="{{ url('/') }}#unit">{{ __('site.business_unit') }}</a></li>
        <li><a href="{{ url('/') }}#plant">{{ __('site.plant_locations') }}</a></li>
        <li><a href="{{ route('news.index') }}">{{ __('site.news') }}</a></li>
        <li><a href="{{ url('/') }}#sosmed">{{ __('site.social_media') }}</a></li>
        <li><a href="{{ url('/kontak') }}">{{ __('site.contact_us') }}</a></li>
      </ul></div>
      <div><h4>{{ __('site.follow_us') }}</h4><ul>
        @foreach ($socials as $key => $label)
          @if (!empty($site['social_'.$key]))
            @php $shown++; @endphp
            <li><a href="{{ $site['social_'.$key] }}" target="_blank" rel="noopener">{{ $label }}</a></li>
          @endif
        @endforeach
        @if ($shown === 0)<li><a href="https://instagram.com/newarmada.official" target="_blank" rel="noopener">Instagram</a></li>@endif
      </ul></div>
    </div>
    <div class="footer-bottom"><span>© {{ date('Y') }} {{ $site['site_name'] ?? 'New Armada' }}. {{ __('site.rights') }}</span><span>Company Profile Redesign</span></div>
  </div>
</footer>

<div class="search-pop" id="searchPop" data-suggest="{{ route('search.suggest') }}" hidden>
  <div class="search-box">
    <form action="{{ route('search') }}" method="get" role="search">
      <input type="search" name="q" id="searchInput" placeholder="{{ __('site.search_placeholder') }}" autocomplete="off" minlength="2" required>
      <button class="btn" type="submit">{{ __('site.search') }}</button>
    </form>
    <ul class="sug" id="sugList"></ul>
  </div>
</div>

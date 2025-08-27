<div class="nav-align-top">
  <ul class="nav nav-pills flex-column flex-md-row flex-wrap mb-6 row-gap-2">
    @foreach ($navItems as $item)
      <li class="nav-item">
        <a class="nav-link {{ request()->url() == $item['href'] ? 'active' : '' }}" href="{{ $item['href'] }}">
          <i class="{{ $item['icon'] }}"></i> {{ $item['label'] }}
        </a>
      </li>
    @endforeach
  </ul>
</div>

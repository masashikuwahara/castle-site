@props([
  'items' => [],
])

<nav class="text-xs text-slate-600">
  <ol class="flex flex-wrap items-center gap-2">
    @foreach($items as $i => $item)
      @if($i > 0)
        <li class="opacity-60">/</li>
      @endif

      <li>
        @if(!empty($item['url']) && $i !== count($items) - 1)
          <a href="{{ $item['url'] }}" class="hover:text-[#233d5d] hover:underline">
            {{ $item['label'] }}
          </a>
        @else
          <span class="text-slate-900 font-medium">{{ $item['label'] }}</span>
        @endif
      </li>
    @endforeach
  </ol>
</nav>

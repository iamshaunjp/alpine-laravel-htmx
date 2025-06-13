@extends('layouts.app')

@section('content')
  <h1 class="page-title">Codex</h1>

  @fragment('codex-entry-list')
  <div 
    class="codex-list content" 
    id="codex-list"
  >
  <div class="flex justify-between items-center mb-8">
    <h2 class="text-xl ml-2 font-bold">Codex Entries</h2>
    <a href="{{ route('outline.codex.create') }}" class="btn inline-block"
      hx-get="{{ route('outline.codex.create') }}"
      hx-target="#modal"
      hx-swap="innerHTML"
    >
      Add a New Codex Entry
    </a>
  </div>

  @php
    $types = ['character', 'item', 'location'];
  @endphp

  @foreach ($types as $type)
    @if(isset($codexEntries[$type]) && $codexEntries[$type]->count())
      <div 
        class="codex-group codex-group-{{ $type }} mb-6"
      >
        <h2 class="text-lg font-semibold capitalize">{{ $type }}s</h2>
        <ul class="ml-4">
          @foreach ($codexEntries[$type] as $entry)
            <li class="codex-entry" id="codex-entry-{{ $entry->id }}">
              <a href="{{ route('outline.codex.show', $entry) }}"
                @if($isHtmx)
                  hx-get="{{ route('outline.codex.show', $entry) }}"
                  hx-target="#modal"
                  hx-swap="innerHTML"
                @endif
              >
                {{ $entry->name }}
              </a>
            </li>
          @endforeach
        </ul>
      </div>
    @endif
  @endforeach

  @if ($codexEntries->isEmpty())
    <p class="empty ml-2">No codex entries yet. Add your first character, item, or location!</p>
  @endif
  </div>
  @endfragment

  @fragment('modal')
  <div class="modal-content" id="modal" hx-swap-oob="true"></div>
  @endfragment
@endsection

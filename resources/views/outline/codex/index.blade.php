@extends('layouts.app')

@section('content')
  <h1 class="page-title">Codex</h1>

  @fragment('codex-entry-list')
  <div class="codex-list content" id="codex-list" x-init="console.log('Im initing')">
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

    <!-- Filter Buttons -->
    @if($isHtmx)
      <div class="filters">
        <p>Filter by:</p>
        <button 
          x-on:click="$store.codex.filter = 'all'"
          :class="{ 'active': $store.codex.filter === 'all' }"
        >All</button>
        <button 
          x-on:click="$store.codex.filter = 'character'" 
          :class="{ 'active': $store.codex.filter === 'character' }"
        >Characters</button>
        <button 
          x-on:click="$store.codex.filter = 'item'"
          :class="{ 'active': $store.codex.filter === 'item' }"
        >Items</button>
        <button 
          x-on:click="$store.codex.filter = 'location'"
          :class="{ 'active': $store.codex.filter === 'location' }"
        >Locations</button>
      </div>

      <div class="mb-6">
        <input 
          type="text"
          placeholder="Search codex entries..."
          x-model="$store.codex.search"
          class="p-2 border rounded w-full"
        />
      </div>
    @endif

    @php
      $types = ['character', 'item', 'location'];
    @endphp

    @foreach ($types as $type)
      @if(isset($codexEntries[$type]) && $codexEntries[$type]->count())
        <div 
          class="codex-group codex-group-{{ $type }} mb-6"
          x-show="$store.codex.filter === 'all' || $store.codex.filter === '{{ $type }}'"
        >
          <h2 class="text-lg font-semibold capitalize">{{ $type }}s</h2>
          <ul class="ml-4">
            @foreach ($codexEntries[$type] as $entry)
              <li class="codex-entry" id="codex-entry-{{ $entry->id }}" 
                x-show="$store.codex.matches('{{ $entry->name }}')"
              >
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

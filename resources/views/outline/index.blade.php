@extends('layouts.app')

@section('content')
  <div class="outline-wrapper">

    <div class="dashboard">
      <div 
        class="col-span-3 codex-nav"
        hx-get="{{ route('outline.codex.index') }}"
        hx-trigger="load"
        hx-swap="innerHTML"
      ></div>
      <div
        class="col-span-4"
        hx-get="{{ route('outline.chapters.index') }}"
        hx-trigger="load"
        hx-swap="innerHTML"
      ></div>
      <div class="info">
        <div class="modal-content" id="modal"></div>
      </div>
    </div>

    <noscript>
      <h1 class="page-title">Outline Home</h1>
      <div class="content">
        <h2 class="text-bold text-center text-4xl my-12">Start Outlining Your Novel!</h2>
        <p class="text-center my-12 max-w-1/2 mx-auto">
          JavaScript is recommended for a better experience.
        </p>
        <div class="flex justify-center my-8 gap-8 max-w-1/2 mx-auto">
          <a href="{{ route('outline.chapters.index') }}" class="btn">Chapter Timeline</a>
          <a href="{{ route('outline.codex.index') }}" class="btn">Codex Entries</a>
        </div>
      </div>
    </noscript>
  </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>

<script>
  htmx.onLoad(function(content) {
    if (content.id === "chapter-list") {
      const sortable = document.querySelector('.sortable')

      const sortableInstance = new Sortable(sortable, {
        animation: 150,
        ghostClass: 'sorting',
        handle: '.handle',
        onEnd: function() {
          // grab id's of each chapter in order in array
          const order = Array.from(sortable.children).map(
            child => child.getAttribute('data-id')
          )

          // convert array into object
          let values = {}
          order.forEach((val, idx) => {
            values[`order[${idx}]`] = val
          })

          // send ajax rq using htmx
          htmx.ajax('POST', '/outline/chapters/reorder', {
            values,
            target: '#chapter-list',
            swap: 'outerHTML'
          })
        }
      })
    }
  })
</script>
@endpush
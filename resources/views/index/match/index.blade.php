@foreach ($matches as $match)
    <h1>{{ $match->title }}</h1>
    <span class="expired_at">{{ $match->expired_at }}</span>
    <span class="status">{{ $match->status }}</span>
@endforeach

{{ $matches->links() }}

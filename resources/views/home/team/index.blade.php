@foreach ($posts as $post)
    <h1>{{ $post->title }}</h1>
    <span class="created_at">{{ $post->created_at }}</span>
@endforeach

{{ $posts->links() }}

<div class="modal-content">
    <div class="modal-header">
        @if (empty($no_button))
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        @endif
        <h4 class="modal-title">{{ $post->title }}</h4>
        <span class="post-date">{{ $post->created_at }}</span>
    </div>
    <div class="modal-body">
        <div class="post-content">{!! $post->content !!}</div>
    </div>
</div>
@foreach ($comments as $index => $comment)
    <div class="module module-comment" data-id="{{ $comment->id }}">
        <a class="author-image" href="/{{ $comment->user->username }}">
            <img class="author-image-small" src="{{ $comment->user->artwork_url }}">
        </a>
        <div class="comment-details">
            <a class="author-name" href="/{{ $comment->user->username }}">{{ $comment->user->name }}</a>
            <p class="comment-message">{{ $comment->content }}</p>
        </div>
        <div class="comment-footer">
            <a class="comment-reply-link respond-cta small" data-comment-id="{{ $comment->id }}" data-comment-count="{{ $comment->reply_count }}">
                <span>@if( ! $comment->reply_count) {{ __('web.REPLY') }} @else {{ __('web.REPLIES') }} ({{ $comment->reply_count }}) @endif</span>
            </a>
            <span aria-hidden="true">&nbsp;·&nbsp;</span>
            @if( auth()->check() && auth()->user()->id == $comment->user->id && \App\Models\Role::getValue('comment_delete') || (isset($mod) && $mod))
                <a class="comment-delete small" data-comment-id="{{ $comment->id }}">
                    <span data-translate-text="DELETE">Delete</span>
                </a>
                <span aria-hidden="true">&nbsp;·&nbsp;</span>
            @endif
            <span class="comment-time">{{ timeElapsedShortString($comment->created_at) }}</span>
        </div>
        <div class="dotted-extension"></div>
        <div class="comment-response-container hide" data-comment-id="{{ $comment->id }}">
            <div class="comment-responses" data-comment-id="{{ $comment->id }}"></div>
            <form id="comment-reply-form" class="module-item-respond response-row" method="post" action="{{ route('frontend.comments.reply') }}" novalidate>
                <img class="response-author-image author-image-small" src="{{ $comment->user->artwork_url }}" width="30" height="30">
                <input class="reply-input" placeholder="Post a Response..." name="content" type="text" required>
                <input name="parent_id" value="{{ $comment->id }}" type="hidden">
                <a class="insert-emoji">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                        <path d="M0 0h24v24H0z" fill="none"/>
                        <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 8 15.5 8 14 8.67 14 9.5s.67 1.5 1.5 1.5zm-7 0c.83 0 1.5-.67 1.5-1.5S9.33 8 8.5 8 7 8.67 7 9.5 7.67 11 8.5 11zm3.5 6.5c2.33 0 4.31-1.46 5.11-3.5H6.89c.8 2.04 2.78 3.5 5.11 3.5z"/>
                    </svg>
                </a>
            </form>
        </div>
    </div>
@endforeach
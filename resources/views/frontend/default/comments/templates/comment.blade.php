<script type="text/x-tmpl" id="tmpl-comment">
<div class="module module-comment" data-id="{%=o.id%}">
    <a class="author-image">
        <img class="author-image-small" width="30" height="30" src="{%=o.user.artwork_url%}" alt="{%=o.user.name%}">
    </a>
    <div class="comment-details {% if(o.content.replace(/(<([^>]+)>)/gi, '').length < 20) { %} short-text {% } %}">
        <a class="author-name">{%=o.user.name%}</a>
        <p class="comment-message">{%#$.engineComments.isOnlyEmoji(o.content)%}</p>
        <a class="comment-options comment-option-left-trigger" data-user-id="{%=o.user.id%}" data-id="{%=o.id%}">
            <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 60 60" xml:space="preserve"><path d="M8,22c-4.411,0-8,3.589-8,8s3.589,8,8,8s8-3.589,8-8S12.411,22,8,22z"/><path d="M52,22c-4.411,0-8,3.589-8,8s3.589,8,8,8s8-3.589,8-8S56.411,22,52,22z"/><path d="M30,22c-4.411,0-8,3.589-8,8s3.589,8,8,8s8-3.589,8-8S34.411,22,30,22z"/></svg>
        </a>
        <div class="comment-reactions-overview comment-reactions {% if(o.reactions === null || ! o.reactions.length) { %} hide {% } %} " data-id="{%=o.id%}">
            <div class="comment-reactions-emoji">
            {% var total = 0;  if(o.reactions !== null && o.reactions.length) { %}
                {% for (var i=0; i<o.reactions.length; i++) { %}
                    <img src="{%=route.route('frontend.homepage')%}common/reactions/{%=o.reactions[i].type%}.svg" data-type="{%=o.reactions[i].type%}" data-count="{%=o.reactions[i].count%}">
                {% total = total + o.reactions[i].count } %}
            {% } %}
            </div>
            <span class="comment-reactions-count">{%=total%}</span>
        </div>
    </div>
    <div class="comment-footer">
        <label for="like" class="comment-like-link label-reactions" {% if (o.reacted !== null && o.reacted) { %} data-reacted="true" data-reaction-type="{%=o.reacted.type%}" {% } %} data-translate-text="LIKE" data-reaction-type="like" data-reaction-able-type="App\Models\Comment" data-reaction-able-id="{%=o.id%}">
            {% if (o.reacted !== null && o.reacted) { %}
            <span class="react-text-label">{%=o.reacted.type%}</span>
            {%  } else { %}
            <span class="react-text-label">Like</span>
            {% } %}
            <div class="reactions-box">
                <div class="reactions-toolbox"></div>
                @foreach(explode(',', config('settings.reactions', 'like,love,haha,vow,sad,angry')) as $reaction)
                    <button class="reaction-{{ $reaction }}" data-reaction-type="{{ $reaction }}" data-reaction-able-type="App\Models\Comment" data-reaction-able-id="{%=o.id%}">
                        <img alt="{{ $reaction }}" src="{{ asset('common/reactions/' . $reaction . '.svg') }}">
                        <span class="legend-reaction">{{ $reaction }}</span>
                    </button>
                @endforeach
            </div>
        </label>
        <span aria-hidden="true">&nbsp;·&nbsp;</span>
        <a class="comment-reply-link respond-cta"><span data-translate-text="REPLY">{{ __('web.REPLY') }}</span></a>
        <span aria-hidden="true">&nbsp;·&nbsp;</span>
        <span class="comment-time">{%=o.time_elapsed%}</span>
        {% if (o.edited) { %}
            <span aria-hidden="true" class="comment-edited-dot">&nbsp;·&nbsp;</span>
            <span class="comment-edited-label">{{ __('web.EDITED') }}</span>
        {% } %}
    </div>
    <div class="comment-response-container">
        <div class="comment-responses {% if(! o.replies) { %} hide {% } %}" data-comment-id="{%=o.id%}">
            {% if(o.replies !== null && o.replies.data && o.replies.data.length) { %}
                {% for (var i=0; i<o.replies.data.length; i++) { %}
                    {%#$.engineComments.renderReplies([o.replies.data[i]])%}
                {% } %}
            {%  } %}
        </div>
        {% if(o.replies !== null && o.replies.data && o.replies.data.length) { %}
        <div class="comment-response-more {% if(!o.replies.next_page_url) { %} hide {% } %}"
             data-next-page-url="{%=o.replies.next_page_url%}"
             data-last-page-url="{%=o.replies.last_page_url%}"
             data-per-page="{%=o.replies.per_page%}"
             data-last-page="{%=o.replies.last_page%}"
             data-current-page="{%=o.replies.current_page%}"
             data-total="{%=o.replies.total%}"
             data-id="{%=o.id%}"
        >
            <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 500" xml:space="preserve"><path d="M0,56.93c0-25.15,20.32-45.48,45.45-45.48s45.45,20.33,45.45,45.48v90.96c0,21.92,27.36,90.96,90.91,90.96h163l-81.5-81.55c-17.77-17.78-17.77-46.53,0-64.31s46.5-17.78,64.27,0l159.09,159.18c17.77,17.78,17.77,46.53,0,64.31L327.58,475.67c-8.86,8.87-20.5,13.33-32.14,13.33s-23.27-4.46-32.14-13.33c-17.77-17.78-17.77-46.53,0-64.31l81.5-81.55h-163C60.73,329.82,0,213.2,0,147.89L0,56.93z"/></svg>
            <span class="view-more-text">{%=Language.text.VIEW_MORE_REPLY.replace(':count', o.replies.total - o.replies.to)%}</span>
            <div class="loading"></div>
        </div>
        {%  } %}
        <form class="module-item-respond response-row hide" method="post" action="{{ route('frontend.comments.reply') }}" novalidate>
            <img class="response-author-image author-image-small" width="30" height="30">
            <div class="comment-feed-msg" contenteditable="true" placeholder="Post a Response..."></div>
            <input name="parent_id" type="hidden" value={%=o.id%}>
            <a class="insert-emoji">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                    <path d="M0 0h24v24H0z" fill="none"/>
                    <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 8 15.5 8 14 8.67 14 9.5s.67 1.5 1.5 1.5zm-7 0c.83 0 1.5-.67 1.5-1.5S9.33 8 8.5 8 7 8.67 7 9.5 7.67 11 8.5 11zm3.5 6.5c2.33 0 4.31-1.46 5.11-3.5H6.89c.8 2.04 2.78 3.5 5.11 3.5z"/>
                </svg>
            </a>
            <input type="submit" class="hide">
        </form>
    </div>
</div>
</script>


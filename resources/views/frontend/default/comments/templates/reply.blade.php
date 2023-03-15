<script type="text/x-tmpl" id="tmpl-comment-reply">
<div id="response-row-item" class="response-row">
    <a class="response-author-image">
        <img class="author-image-small" width="30" height="30" src="{%=o.user.artwork_url%}" alt="{%=o.user.name%}">
    </a>
    <div class="response-content {% if(o.content.replace(/(<([^>]+)>)/gi, '').length < 20) { %} short-text {% } %}">
        <a class="response-author-name">{%=o.user.name%}</a>
        <p class="response-message">{%#$.engineComments.isOnlyEmoji(o.content)%}</p>
        <a class="response-options comment-option-left-trigger" data-user-id="{%=o.user.id%}" data-id="{%=o.id%}">
            <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 60 60" xml:space="preserve"><path d="M8,22c-4.411,0-8,3.589-8,8s3.589,8,8,8s8-3.589,8-8S12.411,22,8,22z"/><path d="M52,22c-4.411,0-8,3.589-8,8s3.589,8,8,8s8-3.589,8-8S56.411,22,52,22z"/><path d="M30,22c-4.411,0-8,3.589-8,8s3.589,8,8,8s8-3.589,8-8S34.411,22,30,22z"/></svg>
        </a>
        <div class="reply-reactions-overview comment-reactions {% if(o.reactions === null || ! o.reactions.length) { %} hide {% } %} " data-id="{%=o.id%}">
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
        <label for="like" class="comment-like-link label-reactions" {% if (o.reacted !== null && o.reacted) { %} data-reacted="true" data-reaction-type="{%=o.reacted.type%}" {% } else { %} data-reaction-type="like" {% } %}data-translate-text="LIKE"  data-reaction-able-type="App\Models\Comment" data-reaction-able-id="{%=o.id%}">
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
        <a class="comment-reply-link respond-cta small">
            <span>{{ __('web.REPLY') }}</span>
        </a>
        <span aria-hidden="true">&nbsp;·&nbsp;</span>
        <span class="comment-time">{%=o.time_elapsed%}</span>
        {% if (o.edited) { %}
            <span aria-hidden="true" class="comment-edited-dot">&nbsp;·&nbsp;</span>
            <span class="comment-edited-label">{{ __('web.EDITED') }}</span>
        {% } %}
    </div>
</div>
</script>
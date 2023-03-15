@if(isset($tags))
    <div  class="widget widget-categories">
        <h5 class="widget-title"><span>Tags</span></h5>
        <div class="widget-tag-list">
            @foreach($tags as $tag)
                <a href="{{ route('frontend.blog.tags', ['tag' => $tag->tag]) }}" class="btn btn-tag">{{ $tag->tag }}</a>
            @endforeach
        </div>
    </div>
@endif

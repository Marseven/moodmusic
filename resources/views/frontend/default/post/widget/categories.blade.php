<div  class="widget widget-categories">
    <h5 class="widget-title"><span>Categories</span></h5>
    <ul>
        @if(isset($categories))
            @foreach($categories as $index => $category)
                <li>
                    <a href="{{ route('frontend.blog.category', ['category' => $category->alt_name]) }}">{{ $category->name }}</a>
                    <span>({{ $category->news_num }})</span>
                </li>
            @endforeach
        @endif
    </ul>
</div>
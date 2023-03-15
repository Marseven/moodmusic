<div  class="widget widget-posts">
    <h5 class="widget-title"><span>Popular Posts</span></h5>
    <ul class="posts">
        @foreach($posts as $index => $post)
            <li class="post">
                <a href="{{ $post->url }}" class="image-link">
                    <img width="87" height="67" src="https://cheerup.theme-sphere.com/miranda/wp-content/uploads/sites/4/2016/05/shutterstock_444744415-87x67.jpg" alt="{{ $post->title }}" title="{{ $post->title }}">
                </a>
                <div class="inner">
                    <a href="{{ $post->url }}" class="post-title" title="{{ $post->title }}">{{ $post->title }}</a>
                    <div class="inner-meta">
                        <a class="post-cat" href="">Lifestyle</a>
                        <a href="" class="post-date">{{ \Carbon\Carbon::parse($post->created_at)->format('F j Y') }}</a>
                    </div>
                </div>
            </li>
        @endforeach
    </ul>
</div>
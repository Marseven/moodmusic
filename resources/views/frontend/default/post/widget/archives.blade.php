<div  class="widget widget-categories">
    <h5 class="widget-title"><span>Archives</span></h5>
    <ul>
        @if(isset($archives))
            @foreach($archives as $archive)
                <li>
                    <a href="{{ route('frontend.blog.browse.by.month', ['year' =>  \Carbon\Carbon::parse($archive->created_at)->format('Y'), 'month' => \Carbon\Carbon::parse($archive->created_at)->format('m')]) }}">{{ \Carbon\Carbon::parse($archive->created_at)->format('F Y') }} ({{ $archive->count }})</a>
                    <span>({{ $archive->count }})</span>
                </li>
            @endforeach
        @endif
    </ul>
</div>
@foreach ($events as $index => $event)
    <div class="module module-row event tall" data-index="{{ $index }}">
        <div class="date">
            <span class="month">{{ \Carbon\Carbon::parse($event->started_at)->format('M') }}</span>
            <span class="day">{{ \Carbon\Carbon::parse($event->started_at)->format('d') }}</span>
        </div>
        <div class="metadata event">
            <a href="{{ $event->link }}" target="_blank" class="title event-link">{{ $event->title }}</a>
            <div class="meta-inner">{{ $event->location }}</div>
        </div>
    </div>
@endforeach

<h1>{{ $event->name }}</h1>
<h2 class="h4 text-muted">
    {{ $event->start->format('D j M Y') }} {{ isset($event->end) ? 'to ' . $event->end->format('D j M Y') : null }}
</h2>
<p>{{ $event->short_desc }}</p>

<hr>

<p>{!! $event->long_desc !!}</p>

<hr>

<h2 class="h3 mb-3">{{ $event->isRace() ? 'Enter Now' : 'Get Tickets' }}</h2>

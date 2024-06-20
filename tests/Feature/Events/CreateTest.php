<?php

use App\Models\Event;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;

uses(WithFaker::class);

test('can create an event', function ()
{
    $org = Organisation::factory()->create();
    $event = Event::factory()->for($org, 'organisation')->make();

    // Try creating the event
    login(User::factory()->create()->assignRole('event organiser'))
        ->post('/events', [
            'name'              => $event->name,
            'start'             => $event->start,
            'end'               => $event->end,
            'type'              => $event->type,
            'special_requests'  => $event->special_requests,
            'short_desc'        => $event->short_desc,
            'long_desc'         => $event->long_desc,
            'org'               => $org->fresh()->id,
    ])->assertSessionHas(['status' => 'Event created successfully.']);

    // Check the event is saved
    $this->assertDatabaseHas('events', [
          'name' => $event->name,
    ]);
});

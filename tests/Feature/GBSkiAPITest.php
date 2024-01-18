<?php

namespace Tests\Feature;

use Tests\TestCase;

class GBSkiAPITest extends TestCase
{
    public function __construct(string $name)
    {
        parent::__construct($name);


    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetAllCompetitors()
    {
        $response = $this->get('/api/active-registrations');

        dd($response->exception);
    }
}

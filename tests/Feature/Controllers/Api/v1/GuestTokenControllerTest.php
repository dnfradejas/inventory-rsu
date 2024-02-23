<?php

namespace Tests\Feature\Controllers\Api\v1;

use Tests\TestCase;

class GuestTokenControllerTest extends TestCase
{

    public function testGetGuestToken()
    {   
        $response = $this->json('GET', route('api.v1.guest.token'));
        $this->assertArrayHasKey('token', $response->original);
    }

}
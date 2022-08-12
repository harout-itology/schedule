<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ScheduleRequestTest extends TestCase
{
    use RefreshDatabase;
    public array $header;

    public function setUp(): void
    {
        parent::setUp();
        $this->header = ['Accept' => 'application/json', 'Content-Type' => 'application/json'];
    }

    public function testSlotsUnprocessableContent()
    {
        $request = new \stdClass();
        $request->scheduleId = 4711;
        $payload = [$request];
        $response = $this->postJson('api/schedule/slots', $payload, $this->header);

        $response->assertStatus(422);
        $this->assertDatabaseMissing('slots', ['slot' => '2020-01-01 10:00 - 10:15 John Doe']);
        $this->assertDatabaseCount('slots', 0);
    }

    public function testSlotsSuccess()
    {
        $payload = json_decode(file_get_contents(storage_path() . "/test/files/schedule.json"));

        $response = $this->postJson('api/schedule/slots', $payload, $this->header);

        $this->assertDatabaseHas('slots', [
            'slot' => '2020-01-01 10:00 - 10:15 John Doe'
        ]);
        $this->assertDatabaseCount('slots', 12);

        $response->assertStatus(200);
        $response->assertJson(json_decode(file_get_contents(storage_path() . "/test/files/slots.json")));
    }
}

<?php

namespace Tests\Feature;

use Tests\TestCase;

class ScheduleRequestTest extends TestCase
{
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
    }

    public function testSlotsSuccess()
    {
        $request = new \stdClass();
        $request->scheduleId = 4711;
        $request->startDate = "2020-01-01";
        $request->startTime = "10:00:00";
        $request->endDate = "2020-01-01";
        $request->endTime = "13:00:00";
        $request->startBreak = "11:00:00";
        $request->endBreak = "12:00:00";
        $request->startBreak2 = "00:00:00";
        $request->endBreak2 = "00:00:00";
        $request->startBreak3 = "00:00:00";
        $request->endBreak3 = "00:00:00";
        $request->startBreak4 = "00:00:00";
        $request->endBreak4 = "00:00:00";
        $request->employeeId = 4711;
        $request->employeeName = "John Doe";

        $payload = [$request];
        $response = $this->postJson('api/schedule/slots', $payload, $this->header);

        $response->assertStatus(200);
        $response->assertJson([
                "2020-01-01 10:00 - 10:15 John Doe",
                "2020-01-01 10:15 - 10:30 John Doe",
                "2020-01-01 10:30 - 10:45 John Doe",
                "2020-01-01 10:45 - 11:00 John Doe",
                "2020-01-01 12:00 - 12:15 John Doe",
                "2020-01-01 12:15 - 12:30 John Doe",
                "2020-01-01 12:30 - 12:45 John Doe",
                "2020-01-01 12:45 - 13:00 John Doe"
            ]);
    }
}

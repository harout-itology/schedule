<?php

namespace App\Http\Controllers;

use App\Http\Requests\ScheduleRequest;
use App\Http\Lib\Slots;
use Illuminate\Http\JsonResponse;

class ScheduleController extends Controller
{
    public function slots(ScheduleRequest $request, Slots $slots): JsonResponse
    {
        $response = $slots->handler($request->toArray());
        return response()->json($response);
    }
}

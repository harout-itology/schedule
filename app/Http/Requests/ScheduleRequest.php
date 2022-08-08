<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScheduleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            '*.scheduleId' => 'required|numeric',
            '*.startDate' => 'required|date_format:Y-m-d',
            '*.startTime' => 'required|date_format:H:i:s',
            '*.endDate' => 'required|date_format:Y-m-d',
            '*.endTime' => 'required|date_format:H:i:s',
            '*.employeeId' => 'required|numeric',
            '*.employeeName' => 'required|string',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class AttendanceDetailRequest extends FormRequest
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
            'check_in' => ['required', 'date_format:H:i','before:check_out'],
            'check_out' => ['required', 'date_format:H:i'],
            'break_start.*' => ['nullable', 'date_format:H:i'],
            'break_end.*' => ['nullable','date_format:H:i'],
            'remarks' => ['required'],
        ];
    }

    public function messages()
    {
        return[
            'check_in.before' => '出勤時間もしくは退勤時間が不適切な値です',
            'remarks.required' => '備考を記入してください',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            foreach ($this->break_start ?? [] as $index => $start) {

                $end = $this->break_end[$index] ?? null;

                if (!$start || !$end) continue;

                $checkIn = Carbon::createFromFormat('H:i', $this->check_in);
                $checkOut = Carbon::createFromFormat('H:i', $this->check_out);
                $startTime = Carbon::createFromFormat('H:i', $start);
                $endTime = Carbon::createFromFormat('H:i', $end);

                // 休憩開始 < 出勤
                if ($startTime->lt($checkIn)) {
                    $validator->errors()->add(
                        "break_start.$index",
                        '休憩時間が不適切な値です'
                    );
                }

                // 休憩終了 > 退勤
                if ($endTime->gt($checkOut)) {
                    $validator->errors()->add(
                        "break_end.$index",
                        '休憩時間もしくは退勤時間が不適切な値です'
                    );
                }

                // 休憩開始 >= 終了
                if ($startTime->gte($endTime)) {
                    $validator->errors()->add(
                        "break_start.$index",
                        '休憩時間が不適切な値です'
                    );
                }
            }
        });
    }
    
    
}


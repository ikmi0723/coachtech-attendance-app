<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceCorrectionUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'clock_in' => ['required'],
            'clock_out' => ['required'],
            'note' => ['required'],
            'breaks.*.break_start_at' => ['nullable'],
            'breaks.*.break_end_at' => ['nullable'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $clockIn = $this->input('clock_in');
            $clockOut = $this->input('clock_out');

            if ($clockIn && $clockOut && $clockIn >= $clockOut) {
                $validator->errors()->add('clock_in', '出勤時間もしくは退勤時間が不適切な値です');
            }

            $breaks = $this->input('breaks', []);

            foreach ($breaks as $index => $break) {
                $breakStart = $break['break_start_at'] ?? null;
                $breakEnd = $break['break_end_at'] ?? null;

                if (!$breakStart && !$breakEnd) {
                    continue;
                }

                if (($breakStart && !$breakEnd) || (!$breakStart && $breakEnd)) {
                    $validator->errors()->add("breaks.$index.break_start_at", '休憩時間が不適切な値です');
                    continue;
                }

                if ($breakStart >= $breakEnd) {
                    $validator->errors()->add("breaks.$index.break_start_at", '休憩時間が不適切な値です');
                    continue;
                }

                if ($clockIn && $breakStart < $clockIn) {
                    $validator->errors()->add("breaks.$index.break_start_at", '休憩時間が不適切な値です');
                }

                if ($clockOut && $breakEnd > $clockOut) {
                    $validator->errors()->add("breaks.$index.break_end_at", '休憩時間もしくは退勤時間が不適切な値です');
                }

                $validatedBreaks[] = [
                    'index' => $index,
                    'start' => $breakStart,
                    'end' => $breakEnd,
                ];
            }

            $count = count($validatedBreaks);

            for ($i = 0; $i < $count; $i++) {
                for ($j = $i + 1; $j < $count; $j++) {
                    $first = $validatedBreaks[$i];
                    $second = $validatedBreaks[$j];

                    $isOverlapping = $first['start'] < $second['end'] && $second['start'] < $first['end'];

                    if ($isOverlapping) {
                        $validator->errors()->add(
                            "breaks.{$second['index']}.break_start_at",
                            '休憩時間が重複しています'
                        );
                    }
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'clock_in.required' => '出勤時間を入力してください',
            'clock_out.required' => '退勤時間を入力してください',
            'note.required' => '備考を記入してください',
        ];
    }
}

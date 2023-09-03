<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Shift>
 */
class ShiftFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $dtstart = Carbon::createFromTimeString('08:00:00')->addMinutes(rand(0,59))->addSeconds(rand(0,59));
        $dtend = Carbon::createFromTimeString($dtstart)->addHours(rand(7,9))->addMinutes(rand(0,59))->addSeconds(rand(0,59));
        return [
            'time_in' => $dtstart,
            'time_out' => $dtend,
        ];
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Snooze>
 */
class SnoozeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $dtstart = Carbon::createFromTimeString('12:45:59')->addMinutes(rand(0,14))->addSeconds(rand(0,59));
        $dtend = (clone $dtstart)->addMinutes(rand(0,60));
        return [
            'snooze_on' => $dtstart->format('H:i:s'),
            'snooze_off' => $dtend->format('H:i:s'),
        ];
    }
}

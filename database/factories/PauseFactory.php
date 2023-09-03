<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pause>
 */
class PauseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $dtstart = Carbon::createFromTimeString('11:30:00')->addMinutes(rand(0,30))->addSeconds(rand(0,59));
        $dtend = Carbon::createFromTimeString($dtstart)->addMinutes(rand(0,44))->addSeconds(rand(0,59));
        return [
            'pause_on' => $dtstart,
            'pause_off' => $dtend,
        ];
    }
}

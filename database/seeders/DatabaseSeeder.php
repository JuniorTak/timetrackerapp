<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\{ Team, User, Shift };
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(3)->withPersonalTeam()->create();

        $arraydt = array();
        $ids = [1, 2, 3];
        for ($i = 1; $i <= 31; $i++)
        {
            array_push($arraydt, Carbon::createFromDate(2023, 8, $i));
        }
        foreach($arraydt as $dt)
        {
            foreach($ids as $id)
            {
                Shift::factory()->create(['the_date' => $dt, 'user_id' => $id]);
            }
        }

        $j = 1;
        for ($i = 1; $i <= 93; $i++)
        {
            // Create a pause for the corresponding shift
            Pause::factory()->create(['shift_id' => $i]);

            // Randomly create a snooze or not for the corresponding shift
            if (rand(0,1) == 1) Snooze::factory()->create(['shift_id' => $i]);

            // Create extra pauses on a basis of specific series
            if ($j == $i)
            {
                Pause::factory()->create([
                    'pause_on' => '14:00:59',
                    'pause_off' => Carbon::createFromTimeString('14:00:59')->addMinutes(rand(0,44))->addSeconds(rand(0,59)),
                    'shift_id' => $i
                ]);

                if (($i % 3) != 0) $j += 4;
                else $j++;
            }
        }
    }
}

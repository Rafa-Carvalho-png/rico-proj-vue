<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Call;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(3)->create();

        $userIds = User::pluck('id')->toArray();
        Call::factory(10)->create()->each(function ($call) use ($userIds) {
            $fromUserId = $userIds[array_rand($userIds)];
            $toUserIds = array_diff($userIds, [$fromUserId]);
            $toUserId = $toUserIds[array_rand($toUserIds)];

            $call->from_user = $fromUserId;
            $call->to_user = $toUserId;
            $call->save();
        });
    }
}

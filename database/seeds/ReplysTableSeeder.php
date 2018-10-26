<?php

use Illuminate\Database\Seeder;
use App\Models\Reply;
use App\Models\User;
use App\Models\Topic;
class ReplysTableSeeder extends Seeder
{
    public function run()
    {
        // 从用户数组中拿出一个 ID
        $user_ids = User::all()->pluck('id')->toArray();

        // 从话题数组中拿出一个 ID
        $topic_ids = Topic::all()->pluck('id')->toArray();

        // 实例化 faker
        $faker = app(Faker\Generator::class);

        $replies = factory(Reply::class)
            ->times(10)
            ->make()
            ->each(function ($reply, $index) use ($topic_ids, $user_ids, $faker) {
                $reply->user_id = $faker->randomElement($user_ids);
                $reply->topic_id = $faker->randomElement($topic_ids);
            });

        Reply::insert($replies->toArray());
    }
}
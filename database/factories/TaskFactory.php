<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public static ?string $task_list_id;

    public function definition(): array
    {
        return [
            'title' => $this->faker->word(),
            'description' => $this->faker->text(),
            'status' => $this->faker->randomElement(['pending', 'completed']),
            'due_date' => $this->faker->boolean() ? $this->faker->date() : null,
            'task_list_id' => self::$task_list_id,
            'user_id' => User::first()->id,
            'deleted_at' => $this->faker->boolean() ? $this->faker->date() : null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}

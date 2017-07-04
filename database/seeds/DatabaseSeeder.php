<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Model\User::class, 1)->create(['profile'=>'AGENT'])->each(function ($user) {
            $user->request()->save(factory(App\Model\Request::class)->make());
        });

        factory(App\Model\User::class, 1)->create(['profile'=>'USER'])->each(function ($user) {
            $user->request()->save(factory(App\Model\Request::class)->make());
        });
    }
}

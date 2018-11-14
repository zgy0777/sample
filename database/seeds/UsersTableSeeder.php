<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $users = factory(User::class)->times(50)->make();
        User::insert($users->makeVisible(['password','remember_token'])->toArray());

        $user = User::find(1);
        $user->name = 'Xzibit';
        $user->email = 'admin@qq.com';
        $user->password = bcrypt('123123');
        $user->is_admin = true;
        $user->activated = true;
        $user->save();


        //需要依赖注入User模型
        /*$user->truncate();
        $faker = \Faker\Factory::create('zh_CN');
        for($i = 0; $i<100; $i++){
            $user->create([
                'name' => $faker->unique()->userName,
                'email' => $faker->unique()->email,
                'password' => bcrypt('123123'),
            ]);
        }*/
    }
}

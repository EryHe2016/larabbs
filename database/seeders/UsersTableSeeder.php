<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //生成数据集合
        User::factory()->count(10)->create();

        //单独处理第一个用户数据
        $user = User::find(1);
        $user->name = 'ery.he';
        $user->email = 'ery.he@feisu.com';
        $user->password = Hash::make('Ery0704he');
        $user->avatar = "https://cdn.learnku.com/uploads/images/201710/14/1/ZqM7iaP4CR.png";
        $user->save();

        //将1号用户指派为站长
        $user->assignRole('Founder');
        //将2号管理员指派为 管理员
        $user = User::find(2);
        $user->assignRole('Maintainer');
    }
}

<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tenant = Tenant::query()
            ->where('is_active', true)
            ->orderBy('created_at')
            ->first();

        User::create([
            'tenant_id'=>$tenant->id,
            'name'=>'test1',
            'email'=>'test@mail.com',
            'password'=> Hash::make('test1'),
            'role'=>'admin',
            'is_active'=>true,
        ]);
    }
}

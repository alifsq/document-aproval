<?php

namespace Database\Seeders;

use App\Models\Tenant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tenant = new Tenant();
        $tenant->name = 'test_tenant1';
        $tenant->code = 'code_tenant1';
        $tenant->is_active = true;
        $tenant->save();
    }
}

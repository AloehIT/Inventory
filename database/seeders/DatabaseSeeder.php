<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;

use App\Models\User;
use App\Models\DetailAlamat;
use App\Models\DetailUsers;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $default_user_value = [
            'email_verified_at' => now(),
            'password' => '$2y$10$CRkxK7eNLUPAezdPq84M.uq5mPMGr/orObzIIOMtUKoyX6JutE.SC', // 12345678
            'remember_token' => Str::random(10),
        ];

        DB::beginTransaction();
        try {
            $admin = User::create(array_merge ([
                'username' => 'admin',
                'unique' => '12345678',
                'email' => 'kantrawibawa10@gmail.com',
                'owner_id' => 'wngnet',
                'status' => '1',
            ], $default_user_value));

            $admin = DetailAlamat::create(array_merge ([
                'provinsi' => 'Bali',
                'kabupaten' => 'Tabanan',
                'kecamatan' => 'Tabanan',
                'desa' => 'Tabanan',
                'alamat' => 'Jln. Mawar No.40',
                'maps' => 'test',
            ]));

            $admin = DetailUsers::create(array_merge ([
                'users_id' => 'A0001',
                'nik' => '10209128912236236',
                'nama_users' => 'Kadek Satria Kantra Wibawa',
                'telpon' => 'Tabanan',
                'profile' => 'profile.png',
                'ktp' => 'ktp.png',
                'perusahaanid' => '1',
            ]));

            $role_admin = Role::create(['name' => 'admin']);

            // $permission = Permission::create(['name' => 'read role']);
            // $permission = Permission::create(['name' => 'create role']);
            // $permission = Permission::create(['name' => 'update role']);
            // $permission = Permission::create(['name' => 'delete role']);
            // $permission = Permission::create(['name' => 'read setting']);

            $role_admin->givePermissionTo('read role');
            $role_admin->givePermissionTo('create role');
            $role_admin->givePermissionTo('update role');
            $role_admin->givePermissionTo('delete role');
            $role_admin->givePermissionTo('read setting');

            $admin->assignRole('admin');


            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
        }
    }
}

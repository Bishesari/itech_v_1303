<?php

namespace Database\Seeders;
use App\Models\Contact;
use App\Models\Profile;
use App\Models\User;
use App\Models\Role;
use App\Models\RoleAssignment;

use Illuminate\Database\Seeder;

class RoleAssignSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    $superAdmin = Role::where('slug', 'super-admin')->firstOrFail();
    $newbie = Role::where('slug', 'newbie')->firstOrFail();
    $founder = Role::where('slug', 'founder')->firstOrFail();

    // =========================
    // Yasser
    // =========================

    $user = User::updateOrCreate(
        ['user_name' => 'Yasser'],
        [
            'password' => bcrypt('123456'),
            'is_active' => true,
        ]
    );

    Profile::updateOrCreate(
        ['user_id' => $user->id],
        [
            'identifier_type' => 'national_id',
            'identifier_value' => '2063531218',
            'gender' => 1,
            'f_name_fa' => 'یاسر',
            'l_name_fa' => 'بیشه سری',
            'nickname' => 'یاسر بی',
        ]
    );

    $contact = Contact::updateOrCreate(
        [
            'type' => 'mobile',
            'value' => '09177755924',
        ],
        []
    );

    $user->contacts()->syncWithoutDetaching([
        $contact->id => [
            'is_primary' => true,
        ],
    ]);

    RoleAssignment::updateOrCreate(
        [
            'user_id' => $user->id,
            'role_id' => $superAdmin->id,
            'institute_id' => null,
            'branch_id' => null,
        ],
        [
            'is_active' => true,
        ]
    );

    RoleAssignment::updateOrCreate(
        [
            'user_id' => $user->id,
            'role_id' => $newbie->id,
            'institute_id' => null,
            'branch_id' => null,
        ],
        [
            'is_active' => true,
        ]
    );

    // =========================
    // Neda
    // =========================

    $user = User::updateOrCreate(
        ['user_name' => 'Neda'],
        [
            'password' => bcrypt('123456'),
            'is_active' => true,
        ]
    );

    Profile::updateOrCreate(
        ['user_id' => $user->id],
        [
            'identifier_type' => 'national_id',
            'identifier_value' => '3500984886',
            'gender' => 2,
            'f_name_fa' => 'ندا',
            'l_name_fa' => 'بخشی زاده',
            'nickname' => 'ندا بخشی',
        ]
    );

    $contact = Contact::updateOrCreate(
        [
            'type' => 'mobile',
            'value' => '09177729312',
        ],
        []
    );

    $user->contacts()->syncWithoutDetaching([
        $contact->id => [
            'is_primary' => true,
        ],
    ]);

    RoleAssignment::updateOrCreate(
        [
            'user_id' => $user->id,
            'role_id' => $founder->id,

            // بعداً باید آموزشگاه واقعی اینجا قرار گیرد
            'institute_id' => 1,

            'branch_id' => null,
        ],
        [
            'is_active' => true,
        ]
    );
}
}

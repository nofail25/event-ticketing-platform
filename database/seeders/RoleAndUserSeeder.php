<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RoleAndUserSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create the 4 roles
        $superAdmin      = Role::firstOrCreate(['name' => 'Super Admin']);
        $eventOrganizer  = Role::firstOrCreate(['name' => 'Event Organizer']);
        $customer        = Role::firstOrCreate(['name' => 'Customer']);
        $gateScanner     = Role::firstOrCreate(['name' => 'Gate Scanner']);

        // Create Super Admin user
        $superAdminUser = User::firstOrCreate(
            ['email' => 'superadmin@ticketing.com'],
            [
                'name'     => 'Super Admin',
                'password' => Hash::make('password'),
            ]
        );
        $superAdminUser->assignRole($superAdmin);

        // Create Event Organizer user
        $organizerUser = User::firstOrCreate(
            ['email' => 'organizer@ticketing.com'],
            [
                'name'     => 'Event Organizer',
                'password' => Hash::make('password'),
            ]
        );
        $organizerUser->assignRole($eventOrganizer);

        // Create Customer user
        $customerUser = User::firstOrCreate(
            ['email' => 'customer@ticketing.com'],
            [
                'name'     => 'Customer',
                'password' => Hash::make('password'),
            ]
        );
        $customerUser->assignRole($customer);

        // Create Gate Scanner user
        $scannerUser = User::firstOrCreate(
            ['email' => 'scanner@ticketing.com'],
            [
                'name'     => 'Gate Scanner',
                'password' => Hash::make('password'),
            ]
        );
        $scannerUser->assignRole($gateScanner);

        $this->command->info('Roles and users seeded successfully.');
        $this->command->table(
            ['Name', 'Email', 'Role'],
            [
                [$superAdminUser->name,  $superAdminUser->email,  'Super Admin'],
                [$organizerUser->name,   $organizerUser->email,   'Event Organizer'],
                [$customerUser->name,    $customerUser->email,    'Customer'],
                [$scannerUser->name,     $scannerUser->email,     'Gate Scanner'],
            ]
        );
    }
}

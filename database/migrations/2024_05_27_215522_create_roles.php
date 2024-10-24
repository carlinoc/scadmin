<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // $role1 = Role::create(['name' => 'admin']);
        // $role2 = Role::create(['name' => 'maitre']);
        // $role3 = Role::create(['name' => 'mozo']);

        // $user = User::find(1);
        // $user->assignRole('Admin');

        // $user1 = User::find(2);
        // $user1->assignRole('Mozo');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        
    }
};

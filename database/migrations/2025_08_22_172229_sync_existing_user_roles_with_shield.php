<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create roles if they don't exist
        $roles = ['super_admin', 'admin', 'customer'];
        
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }
        
        // Sync existing user roles with Shield
        $users = User::all();
        foreach ($users as $user) {
            if ($user->role && !$user->hasRole($user->role)) {
                try {
                    $user->assignRole($user->role);
                    echo "Assigned role '{$user->role}' to user: {$user->email}\n";
                } catch (\Exception $e) {
                    echo "Error assigning role to {$user->email}: {$e->getMessage()}\n";
                }
            }
        }
        
        echo "Role synchronization completed!\n";
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove all role assignments
        $users = User::all();
        foreach ($users as $user) {
            $user->roles()->detach();
        }
        
        // Optionally remove roles
        Role::whereIn('name', ['super_admin', 'admin', 'customer'])->delete();
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE users
            MODIFY role ENUM(
                'admin',
                'accountant',
                'budget',
                'bookkeeper'
            ) NOT NULL
        ");

        DB::statement("
            ALTER TABLE users
            MODIFY is_active ENUM(
                'active',
                'inactive'
            ) NOT NULL DEFAULT 'active'
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE users
            MODIFY role ENUM(
                'Admin',
                'Accountant',
                'Budget',
                'Book Keeper'
            ) NOT NULL
        ");

        DB::statement("
            ALTER TABLE users
            MODIFY is_active ENUM(
                'Active',
                'Inactive'
            ) NOT NULL DEFAULT 'Active'
        ");
    }
};

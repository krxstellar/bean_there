<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staffs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->unique();
            $table->string('position')->nullable();
            $table->string('staff_code')->nullable()->unique();
            $table->timestamp('hired_at')->nullable();
            $table->timestamps();
        });

        // If seeded Bianca exists, create a staff profile for her (idempotent)
        if (Schema::hasTable('users')) {
            $u = DB::table('users')->where('email', 'bianca@beanthere.com')->first();
            if ($u) {
                DB::table('staffs')->updateOrInsert(
                    ['user_id' => $u->id],
                    [
                        'position' => 'Store Manager',
                        'staff_code' => 'STF-' . str_pad($u->id, 3, '0', STR_PAD_LEFT),
                        'hired_at' => now(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('staffs');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Login ahora es por username (antes era por email).
     * - Asigna username basado en email a usuarios que no lo tengan.
     * - Hace username NOT NULL.
     */
    public function up(): void
    {
        // Asignar username a usuarios existentes que no lo tengan
        DB::table('users')
            ->whereNull('username')
            ->orWhere('username', '')
            ->get()
            ->each(function ($user) {
                $base = explode('@', $user->email)[0];
                $username = $base;
                $counter = 1;
                while (DB::table('users')->where('username', $username)->where('id', '!=', $user->id)->exists()) {
                    $username = $base . $counter;
                    $counter++;
                }
                DB::table('users')->where('id', $user->id)->update(['username' => $username]);
            });

        // Hacer username NOT NULL
        Schema::table('users', function (Blueprint $table) {
            $table->string('username', 50)->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username', 50)->nullable()->change();
        });
    }
};

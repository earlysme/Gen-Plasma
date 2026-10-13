<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('program_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('program_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['user_id', 'program_id']);
        });

        // Migrasi data existing: isi tabel pivot dari program_id yang ada di users
        $users = \Illuminate\Support\Facades\DB::table('users')->whereNotNull('program_id')->get();
        foreach ($users as $u) {
            \Illuminate\Support\Facades\DB::table('program_user')->updateOrInsert([
                'user_id' => $u->id,
                'program_id' => $u->program_id,
            ], [
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program_user');
    }
};

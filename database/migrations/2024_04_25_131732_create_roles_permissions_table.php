<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('roles_permissions', function (Blueprint $table) {
            $table->integer('role_id');
            $table->integer('permission_id');

            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->restrictOnUpdate()
                ->restrictOnDelete();

            $table->foreign('permission_id')
                ->references('id')
                ->on('permissions')
                ->restrictOnUpdate()
                ->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('roles_permissions');
    }
};

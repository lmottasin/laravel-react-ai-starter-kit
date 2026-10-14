<?php

declare(strict_types=1);

use Illuminate\Contracts\Cache\Factory;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * spatie/laravel-permission tables, adapted from the package stub to read config with typed getters.
 */
return new class extends Migration
{
    public function up(): void
    {
        $teams = config()->boolean('permission.teams');

        $permissions = $this->tableName('permissions');
        $roles = $this->tableName('roles');

        $pivotRole = $this->columnName('role_pivot_key');
        $pivotPermission = $this->columnName('permission_pivot_key');
        $morphKey = $this->columnName('model_morph_key');
        $teamKey = $teams ? $this->columnName('team_foreign_key') : '';

        Schema::create($permissions, static function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();

            $table->unique(['name', 'guard_name']);
        });

        Schema::create($roles, static function (Blueprint $table) use ($teams, $teamKey): void {
            $table->id();

            if ($teams) {
                $table->unsignedBigInteger($teamKey)->nullable();
                $table->index($teamKey, 'roles_team_foreign_key_index');
            }

            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();

            $table->unique($teams ? [$teamKey, 'name', 'guard_name'] : ['name', 'guard_name']);
        });

        Schema::create($this->tableName('model_has_permissions'), static function (Blueprint $table) use ($permissions, $pivotPermission, $morphKey, $teams, $teamKey): void {
            $table->unsignedBigInteger($pivotPermission);

            $table->string('model_type');
            $table->unsignedBigInteger($morphKey);
            $table->index([$morphKey, 'model_type'], 'model_has_permissions_model_id_model_type_index');

            $table->foreign($pivotPermission)
                ->references('id')
                ->on($permissions)
                ->cascadeOnDelete();

            if ($teams) {
                $table->unsignedBigInteger($teamKey);
                $table->index($teamKey, 'model_has_permissions_team_foreign_key_index');
            }

            $table->primary(
                $teams ? [$teamKey, $pivotPermission, $morphKey, 'model_type'] : [$pivotPermission, $morphKey, 'model_type'],
                'model_has_permissions_permission_model_type_primary',
            );
        });

        Schema::create($this->tableName('model_has_roles'), static function (Blueprint $table) use ($roles, $pivotRole, $morphKey, $teams, $teamKey): void {
            $table->unsignedBigInteger($pivotRole);

            $table->string('model_type');
            $table->unsignedBigInteger($morphKey);
            $table->index([$morphKey, 'model_type'], 'model_has_roles_model_id_model_type_index');

            $table->foreign($pivotRole)
                ->references('id')
                ->on($roles)
                ->cascadeOnDelete();

            if ($teams) {
                $table->unsignedBigInteger($teamKey);
                $table->index($teamKey, 'model_has_roles_team_foreign_key_index');
            }

            $table->primary(
                $teams ? [$teamKey, $pivotRole, $morphKey, 'model_type'] : [$pivotRole, $morphKey, 'model_type'],
                'model_has_roles_role_model_type_primary',
            );
        });

        Schema::create($this->tableName('role_has_permissions'), static function (Blueprint $table) use ($permissions, $roles, $pivotRole, $pivotPermission): void {
            $table->unsignedBigInteger($pivotPermission);
            $table->unsignedBigInteger($pivotRole);

            $table->foreign($pivotPermission)
                ->references('id')
                ->on($permissions)
                ->cascadeOnDelete();

            $table->foreign($pivotRole)
                ->references('id')
                ->on($roles)
                ->cascadeOnDelete();

            $table->primary([$pivotPermission, $pivotRole], 'role_has_permissions_permission_id_role_id_primary');
        });

        $store = config()->string('permission.cache.store');

        resolve(Factory::class)
            ->store($store === 'default' ? null : $store)
            ->forget(config()->string('permission.cache.key'));
    }

    public function down(): void
    {
        Schema::dropIfExists($this->tableName('role_has_permissions'));
        Schema::dropIfExists($this->tableName('model_has_roles'));
        Schema::dropIfExists($this->tableName('model_has_permissions'));
        Schema::dropIfExists($this->tableName('roles'));
        Schema::dropIfExists($this->tableName('permissions'));
    }

    private function tableName(string $key): string
    {
        return config()->string('permission.table_names.'.$key);
    }

    private function columnName(string $key): string
    {
        return config()->string('permission.column_names.'.$key);
    }
};

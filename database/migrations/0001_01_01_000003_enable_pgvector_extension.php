<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Turn on pgvector so tables can use $table->vector(...) columns.
     */
    public function up(): void
    {
        Schema::ensureVectorExtensionExists();
    }

    public function down(): void
    {
        //
    }
};

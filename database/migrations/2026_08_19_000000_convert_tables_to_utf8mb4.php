<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        $database = DB::getDatabaseName();

        DB::statement("ALTER DATABASE `{$database}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

        $this->convert($this->tables(), 'utf8mb4', 'utf8mb4_unicode_ci');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        $database = DB::getDatabaseName();

        DB::statement("ALTER DATABASE `{$database}` CHARACTER SET utf8 COLLATE utf8_unicode_ci");

        $this->convert($this->tables(), 'utf8', 'utf8_unicode_ci');
    }

    /**
     * Convert the given tables, foreign key checks disabled so that indexed
     * columns referenced by a constraint can be altered.
     *
     * @param  array<int, string>  $tables
     */
    private function convert(array $tables, string $charset, string $collation): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        try {
            foreach ($tables as $table) {
                DB::statement("ALTER TABLE `{$table}` CONVERT TO CHARACTER SET {$charset} COLLATE {$collation}");
            }
        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        }
    }

    /**
     * All tables of the current database.
     *
     * @return array<int, string>
     */
    private function tables(): array
    {
        return array_map(
            fn (array $table): string => $table['name'],
            DB::getSchemaBuilder()->getTables()
        );
    }
};

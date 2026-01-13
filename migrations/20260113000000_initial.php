<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Initial extends AbstractMigration
{
    public function up(): void
    {
        // execute raw SQL from 001_initial.sql
        $sqlFile = __DIR__ . '/001_initial.sql';
        if (file_exists($sqlFile)) {
            $sql = file_get_contents($sqlFile);
            $this->execute($sql);
        }
    }

    public function down(): void
    {
        // No down implemented for initial schema; implement if needed
    }
}

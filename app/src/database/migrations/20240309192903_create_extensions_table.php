<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateExtensionsTable extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $table = $this->table('extensions');
        $table->addColumn('name', 'string')
              ->addColumn('extension', 'string')
              ->addColumn('agent', 'string')
              ->addColumn('ip_address', 'string')
              ->addColumn('online', 'boolean', ['default' => false])
              ->addColumn('status', 'enum', [
                'values' => ['available','calling', 'busy', 'paused', 'unavailable'],
                'default' => 'unavailable'
                ])
              ->addTimestamps()
              ->create();
    }

    /**
     * Rollback Method.
     *
     * Revert all changes
     */
    public function down(): void
    {
        $this->table('extensions')->drop()->save();
    }
}

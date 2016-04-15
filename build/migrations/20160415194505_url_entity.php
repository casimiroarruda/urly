<?php

use Phinx\Migration\AbstractMigration;

class UrlEntity extends AbstractMigration
{
    public function change()
    {
        $this->table('url')
            ->addColumn('hash', 'string', ['limit' => 64, 'null' => true])
            ->addColumn('url', 'string', ['limit' => 255, 'null' => false])
            ->addColumn('user_hash', 'string', ['limit' => 64, 'null' => false])
            ->addColumn('hits', 'biginteger', ['signed' => false, 'default' => 0])
            ->addIndex('hash')
            ->addIndex('user_hash')
            ->create();
    }
}

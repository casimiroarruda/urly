<?php

use Phinx\Migration\AbstractMigration;

class UserEntity extends AbstractMigration
{
    public function change()
    {
        {
            $this->table('user')
                ->addColumn('hash', 'string', ['limit' => 64, 'null' => false])
                ->addColumn('alias', 'string', ['limit' => 64, 'null' => true])
                ->addIndex('hash')
                ->create();
        }
    }
}

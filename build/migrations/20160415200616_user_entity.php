<?php

use Phinx\Migration\AbstractMigration;

class UserEntity extends AbstractMigration
{
    public function change()
    {
        {
            $this->table('user')
                ->addColumn('name','string',['limit'=>64,'null'=>false])
                ->create();
        }
    }
}

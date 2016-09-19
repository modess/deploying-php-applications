<?php

use Phinx\Migration\AbstractMigration;

class AddUniqueUsernameIndexOnUsersTable extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('users');
        $table
            ->addIndex('username', ['unique' => true])
            ->save();
    }

    public function down()
    {
        $table = $this->table('users');
        $table->removeIndex(['username']);
    }
}

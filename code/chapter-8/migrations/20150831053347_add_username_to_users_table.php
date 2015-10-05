<?php

use Phinx\Migration\AbstractMigration;

class AddUsernameToUsersTable extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('users');
        $table
            ->addColumn('username', 'string')
            ->save();
    }

    public function down()
    {
        $table = $this->table('users');
        $table
            ->removeColumn('username')
            ->update();
    }
}

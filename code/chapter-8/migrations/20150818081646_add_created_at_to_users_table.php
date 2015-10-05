<?php

use Phinx\Migration\AbstractMigration;

class AddCreatedAtToUsersTable extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('users');
        $table
            ->addColumn('created_at', 'datetime')
            ->save();
    }

    public function down()
    {
        $table = $this->table('users');
        $table
            ->removeColumn('created_at')
            ->update();
    }
}

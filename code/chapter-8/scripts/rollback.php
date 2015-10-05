<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/PhinxBatch.php';

$phinxBatch = new PhinxBatch;
$previousMigrationVersion = $phinxBatch->getPreviousMigrationVersion();

// Run Phinx
system('php vendor/bin/phinx rollback -t ' . $previousMigrationVersion, $exitStatus);

if ($exitStatus === 0) {
    $phinxBatch->removePreviousMigrationVersion();
}

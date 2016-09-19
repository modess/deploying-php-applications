<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/PhinxBatch.php';

$phinxBatch = new PhinxBatch;

// Run Phinx
system('php vendor/bin/phinx migrate', $exitStatus);

if ($exitStatus === 0) {
    $phinxBatch->writeLatestMigrationVersion();
}

<?php

class PhinxBatch
{
    protected $db;

    protected $batchFilePath = __DIR__ . '/batches';

    public function __construct()
    {
        $config          = Spyc::YAMLLoad(__DIR__ . '/../phinx.yml');
        $defaultDatabase = $config['environments']['default_database'];
        $databaseConfig  = $config['environments'][$defaultDatabase];

        $this->db = new PDO("{$databaseConfig['adapter']}:host={$databaseConfig['host']};dbname={$databaseConfig['name']}", $databaseConfig['user'], $databaseConfig['pass']);
    }

    public function getLatestMigrationVersion()
    {
        $query = $this->db->query('SELECT version FROM phinxlog ORDER BY version DESC LIMIT 1');
        $query->execute();
        $row = $query->fetch();

        return ($row) ? $row['version'] : 0;
    }

    public function getBatchesFromFile()
    {
        if (!file_exists($this->batchFilePath)) {
            file_put_contents($this->batchFilePath, 0);
        }

        return array_map(function ($batch) {
            return trim($batch);
        }, file($this->batchFilePath));
    }

    public function getPreviousMigrationVersion()
    {
        $batches = $this->getBatchesFromFile();

        return isset($batches[count($batches) - 2]) ? $batches[count($batches) - 2] : 0;
    }

    public function getLatestMigrationVersionFromFile()
    {
        $batches = $this->getBatchesFromFile();

        return isset($batches[count($batches) - 1]) ? $batches[count($batches) - 1] : 0;
    }

    public function writeLatestMigrationVersion()
    {
        $latestVersion         = $this->getLatestMigrationVersion();
        $latestVersionFromFile = $this->getLatestMigrationVersionFromFile();

        if ($latestVersion != $latestVersionFromFile) {
            file_put_contents($this->batchFilePath, "\n" . $latestVersion, FILE_APPEND);
        }
    }

    public function removePreviousMigrationVersion()
    {
        $batches = $this->getBatchesFromFile();

        unset($batches[sizeof($batches) - 1]);

        $batches = count($batches) > 0 ? $batches : [0];
        file_put_contents($this->batchFilePath, implode("\n", $batches));
    }
}

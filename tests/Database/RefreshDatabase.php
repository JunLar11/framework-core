<?php

namespace Chomsky\Tests\Database;

use Chomsky\Database\Drivers\DatabaseDriver;
use Chomsky\Database\Drivers\PdoDriver;
use Chomsky\Database\Model;
use PDOException;

trait RefreshDatabase {
    protected function setUp(): void {
        if (is_null($this->driver)) {
            $this->driver = singleton(DatabaseDriver::class, PdoDriver::class);
            Model::setDatabaseDriver($this->driver);
            try {
                $this->driver->connect('mysql', 'localhost', 3306, 'chomsky', 'root', 'root');
            } catch (PDOException $e) {
                $this->markTestSkipped("Can't connect to test database: {$e->getMessage()}");
            }
        }
    }

    protected function tearDown(): void {
        $this->driver->statement("DROP DATABASE IF EXISTS chomsky");
        $this->driver->statement("CREATE DATABASE chomsky");
    }
}

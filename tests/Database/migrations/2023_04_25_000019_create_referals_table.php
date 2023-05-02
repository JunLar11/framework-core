<?php

use Chomsky\Database\DB;
use Chomsky\Database\Migrations\Migration;

return new class () implements Migration {
    public function up() {
        DB::statement('CREATE TABLE referals (id INT AUTO_INCREMENT PRIMARY KEY)');
    }

    public function down() {
        DB::statement('DROP TABLE referals');
    }
};
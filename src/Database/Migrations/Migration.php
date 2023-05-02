<?php

namespace Chomsky\Database\Migrations;

interface Migration
{
    public function up();

    public function down();
}

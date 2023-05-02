<?php

namespace Chomsky\Cli\Commands;

use Chomsky\App;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MakeMiddleware extends Command 
{
    protected static $defaultName = "make:middleware";

    protected static $defaultDescription = "Create a new middleware";

    protected function configure() {
        $this->addArgument("name", InputArgument::REQUIRED, "middleware name");
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $name = $input->getArgument("name");
        $template = file_get_contents(resourcesDirectory() . "/templates/middleware.php");
        $template = str_replace("MiddlewareName", $name, $template);
        file_put_contents(App::$root . "/app/Middlewares/$name"."Middleware.php", $template);
        $output->writeln("<info>Controller created => $name"."Middleware.php</info>");

        return Command::SUCCESS;
    }
}

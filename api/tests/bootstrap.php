<?php

declare(strict_types=1);

use App\Kernel;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

require dirname(__DIR__) . '/vendor/autoload.php';

if (file_exists(dirname(__DIR__) . '/config/bootstrap.php')) {
    require dirname(__DIR__) . '/config/bootstrap.php';
} elseif (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__) . '/.env');
}

$kernel = new Kernel('test', false);
$kernel->boot();

$application = new Application($kernel);
$output = new ConsoleOutput();

$application->find('doctrine:database:drop')->run(
    new ArrayInput(
        [
            '--env' => 'test',
            '--force' => true,
            '--if-exists' => true,
        ]
    ),
    $output
);

$application->find('doctrine:database:create')->run(new ArrayInput([
    '--env' => 'test',
]), $output);

$migrationInput = new ArrayInput([
    '--env' => 'test',
]);
$migrationInput->setInteractive(false);
$application->find('doctrine:migrations:migrate')->run($migrationInput, $output);

$fixtureInput = new ArrayInput([
    '--env' => 'test',
]);
$fixtureInput->setInteractive(false);
$application->find('doctrine:fixtures:load')->run($fixtureInput, $output);

$process = new Process(
    command: ['php', 'bin/console', 'lexik:jwt:generate-keypair', '--skip-if-exists'],
    timeout: 120,
);
$process->run();

if (! $process->isSuccessful()) {
    throw new ProcessFailedException($process);
}

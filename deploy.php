<?php

declare(strict_types=1);

namespace Deployer;

require 'recipe/symfony.php';

set('application', 'homepage');

set('git_tty', false);
set('ssh_multiplexing', false);

set('composer_options', '{{composer_action}} --prefer-dist --no-progress --no-interaction --no-dev --optimize-autoloader');

set('php8_path', '/usr/bin/php8.0');
set('composer_path', '/usr/local/bin/composer');

set('repository', getenv('DEPLOYMENT_REPOSITORY'));
set('allow_anonymous_stats', false);

set('application_root_path', '{{release_or_current_path}}/src');

host(getenv('DEPLOYMENT_HOST'))
    ->set('remote_user', getenv('DEPLOYMENT_USER'))
    ->set('identity_file', '~/.ssh/id_rsa')
    ->set('deploy_path', getenv('DEPLOYMENT_PATH'));

set('shared_dirs', [
    'src/var/log',
]);

set('shared_files', [
    'src/.env.local'
]);

set('writable_dirs', [
    'src/config',
    'src/var',
    'src/var/cache',
    'src/var/log',
    'src/var/sessions',
]);

task('deploy:prepare', [
    'deploy:info',
    'deploy:setup',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:shared',
    'deploy:writable',
])->desc('Prepares a new release');

task('deploy:install-vendors', [
    'deploy:install-composer-packages',
    'deploy:install-npm-packages',
])->desc('Installs vendors');

task('deploy:install-composer-packages', function () {
    if (!commandExist('unzip')) {
        warning('To speed up composer installation setup "unzip" command with PHP zip extension.');
    }

    set('env', ['COMPOSER_ALLOW_SUPERUSER' => 1]);
    run('cd {{application_root_path}} && {{php8_path}} {{composer_path}} {{composer_options}} 2>&1');
})->desc('Installs composer packages');

task('deploy:install-npm-packages', function () {
    run('cd {{application_root_path}} && yarn install 2>&1');
})->desc('Installs NPM packages');

task('deploy:build-application', function () {
    run('cd {{application_root_path}} && yarn build 2>&1');
})->desc('Builds frontend assets');

task('deploy:npm:cache:clear', function () {
    run('cd {{application_root_path}} && yarn cache clean 2>&1');
})->desc('Clears NPM cache');

task('deploy:clear-cache', [
    'deploy:cache:clear',
    'deploy:npm:cache:clear',
])->desc('Clears Symfony & NPM caches');

task('deploy:migrate-database', function () {
    $options = '--allow-no-migration --no-interaction';
    if (get('migrations_config') !== '') {
        $options = "$options --configuration={{release_or_current_path}}/{{migrations_config}}";
    }

    run("cd {{application_root_path}} && {{php8_path}} bin/console doctrine:migrations:migrate $options {{console_options}}");
})->desc('Migrates database');


task('deploy', [
    'deploy:prepare',
    'deploy:install-vendors',
    'deploy:build-application',
    'deploy:clear-cache',
    'deploy:migrate-database',
    'deploy:publish',
])->desc('Deploys project');

after('deploy:failed', 'deploy:unlock');

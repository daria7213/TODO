<?php

use App\Controller\TaskController;
use App\Repository\TaskRepository;
use Doctrine\DBAL\Schema\Table;
use Symfony\Component\Validator\Constraints as Assert;

$app = new Silex\Application();

$app['controller.task'] = function(){
    return new TaskController();
};

$app['repository.task'] = function($app){
    return new TaskRepository($app['db']);
};

$app->register(new Silex\Provider\VarDumperServiceProvider());
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '\views',
));

$app->register(new Silex\Provider\ServiceControllerServiceProvider());
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver'   => 'pdo_sqlite',
        'path'     => __DIR__.'/app.sqlite3',
    ),
));

$app->register(new Silex\Provider\ValidatorServiceProvider());

try {
    $file_db = new PDO('sqlite:app.sqlite3');
    } catch(PDOException $e) {
    echo $e->getMessage();
    }

$schema = $app['db']->getSchemaManager();
if (!$schema->tablesExist('task')) {
    $tasks = new Table('task');
    $tasks->addColumn('id', 'integer', array('unsigned' => true, 'autoincrement' => true));
    $tasks->setPrimaryKey(array('id'));
    $tasks->addColumn('description', 'string');
    $tasks->addColumn('status', 'string');

    $schema->createTable($tasks);

    $app['db']->insert('task', array(
        'description' => 'выполненно',
        'status' => 'DONE',
    ));

    $app['db']->insert('task', array(
        'description' => 'невыполненно',
        'status' => 'UNDONE',
    ));
}
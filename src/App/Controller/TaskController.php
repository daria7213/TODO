<?php
namespace App\Controller;

use App\Entity\Task;
use DateTime;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\VarDumper\VarDumper;
class TaskController
{
    public function showAction(Application $app){
        $tasksDone = $app['repository.task']->findAllDone();
        $tasksUndone = $app['repository.task']->findAllUndone();

        return $app['twig']->render('tasks.html.twig',[
            'doneTasks' => $tasksDone,
            'undoneTasks' => $tasksUndone
        ]);
    }

    public function newAction(Request $request, Application $app){

        $task = new Task(
            null,
            $request->request->get('description'),
            $request->request->get('status') == 'false' ? 'UNDONE' : 'DONE'
        );

        $app['repository.task']->save($task);

        $taskData = array(
            'id' => $task->getId(),
            'description' => $task->getDescription(),
            'status' => $task->getStatus()
        );
        return $app->json(json_encode($taskData));
    }

    public function deleteAction(Request $request, Application $app){

        $id = $request->request->get('id');
        $app['repository.task']->delete($id);

        return new Response();
    }

    public function updateAction(Request $request, Application $app){
        $task = new Task(
            $request->request->get('id'),
            $request->request->get('description'),
            $request->request->get('status') == 'false' ? 'UNDONE' : 'DONE'
        );

        $app['repository.task']->update($task);

        return new Response();
    }
}
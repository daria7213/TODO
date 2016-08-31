<?php

namespace App\Repository;

use DateTime;
use Doctrine\DBAL\Driver\Connection;
use App\Entity\Task;
use Doctrine\DBAL\Driver\PDOException;
use Symfony\Component\Config\Definition\Exception\Exception;


class TaskRepository
{
    /**
     * @var \Doctrine\DBAL\Connection
     */
    protected $conn;

    public function __construct(Connection $conn)
    {
        $this->conn = $conn;
    }

    public function save(Task $task){
        $taskData = [
            'description' => $task->getDescription(),
            'status' => $task->getStatus(),
        ];

        $this->conn->insert('task', $taskData);
        $id = $this->conn->lastInsertId();
        $task->setId($id);
    }

    public function findAll(){
        $query = 'SELECT * FROM task';
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $taskData = $stmt->fetchAll();
        $tasks = [];
        foreach($taskData as $task){
            $tasks[] = $this->buildTask($task);
        }

        return $tasks;
    }

    public function findAllDone(){
        $query = 'SELECT * FROM task WHERE status = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(1, 'DONE');
        $stmt->execute();
        $taskData = $stmt->fetchAll();
        $tasks = [];
        foreach($taskData as $task){
            $tasks[] = $this->buildTask($task);
        }

        return $tasks;
    }

    public function findAllUndone(){
        $query = 'SELECT * FROM task WHERE status = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(1, 'UNDONE');
        $stmt->execute();
        $taskData = $stmt->fetchAll();
        $tasks = [];
        foreach($taskData as $task){
            $tasks[] = $this->buildTask($task);
        }

        return $tasks;
    }

    public function find($id) {
        $taskData = $this->conn->fetchAssoc('SELECT * FROM task WHERE id = ?', array($id));
        return $this->buildTask($taskData);
    }

    public function delete($id) {
        return $this->conn->delete('task', array('id' => $id));
    }

    public function update(Task $task) {
        return $this->conn->update('task',array(
            'description' => $task->getDescription(),
            'status' => $task->getStatus()
            ), array('id' => $task->getId()));
    }

    public function buildTask($task){
        return new Task(
            $task['id'],
            $task['description'],
            $task['status']
        );
    }
}
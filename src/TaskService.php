<?php

namespace Src;

use Exception;

class TaskNotFoundException extends Exception
{
}

class TaskService
{
  private $taskRepository;

  public function __construct($taskRepository)
  {
    $this->taskRepository = $taskRepository;
  }

  public function getAllTasks()
  {
    return $this->taskRepository->getAllTasks();
  }

  public function getTaskById($id)
  {
    $taskFound = $this->taskRepository->getTaskById($id);
    if ($taskFound == null) throw new TaskNotFoundException;

    return $taskFound;
  }

  public function createTask($task)
  {
    return $this->taskRepository->createTask($task);
  }

  public function updateTask($id, $task)
  {
    $taskFound = $this->taskRepository->getTaskById($id);
    if ($taskFound == null) throw new TaskNotFoundException;

    return $this->taskRepository->updateTask($id, $task);
  }

  public function deleteTask($id)
  {
    $taskFound = $this->taskRepository->getTaskById($id);
    if ($taskFound == null) throw new TaskNotFoundException;

    return $this->taskRepository->deleteTask($id);
  }
}

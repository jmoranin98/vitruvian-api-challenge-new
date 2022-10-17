<?php

namespace Src;

use Exception;

class Task
{
  private $taskService;
  private $requestMethod;
  private $taskId;

  public function __construct($db, $requestMethod, $taskId)
  {
    $taskRepository = new TaskRepository($db);
    $this->taskService = new TaskService($taskRepository);
    $this->requestMethod = $requestMethod;
    $this->taskId = $taskId;
  }

  public function processRequest()
  {
    switch ($this->requestMethod) {
      case 'GET':
        if ($this->taskId) {
          $response = $this->getTask($this->taskId);
        } else {
          $response = $this->getAllTask();
        };
        break;
      case 'POST':
        $response = $this->createTask();
        break;
      case 'PUT':
        $response = $this->updateTask($this->taskId);
        break;
      case 'DELETE':
        $response = $this->deleteTask($this->taskId);
        break;
      case 'OPTIONS':
        die;
      default:
        $response = $this->notFoundResponse();
        break;
    }

    // CORS headers
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
    header("Allow: GET, POST, OPTIONS, PUT, DELETE");

    header($response['status_code_header']);

    if ($response['body']) {
      echo $response['body'];
    }
  }

  private function getAllTask()
  {
    $statusCode = Http::STATUS_OK;
    $tasks = [];

    try {
      $tasks = $this->taskService->getAllTasks();
    } catch (Exception $e) {
      $statusCode = Http::STATUS_INTERNAL_ERROR;
    }

    $response['status_code_header'] = $statusCode;
    $response['body'] = json_encode($tasks);
    return $response;
  }

  private function getTask($id)
  {
    $statusCode = Http::STATUS_OK;
    $task = null;

    try {
      $task = $this->taskService->getTaskById($id);
    } catch (TaskNotFoundException $e) {
      $statusCode = Http::STATUS_NOT_FOUD;
    } catch (Exception $e) {
      $statusCode = Http::STATUS_INTERNAL_ERROR;
    }

    $response['status_code_header'] = $statusCode;
    $response['body'] = ($task != null) ? json_encode($task) : null;
    return $response;
  }

  private function createTask()
  {
    $statusCode = Http::STATUS_OK;
    $requestBody = file_get_contents('php://input');
    $jsonBody = json_decode($requestBody);

    $task = new TaskModel(
      null,
      $jsonBody->name,
      $jsonBody->description,
      $jsonBody->autor,
      $jsonBody->isComplete,
      null,
      null
    );

    try {
      $task->validate();
      $this->taskService->createTask($task);
    } catch (InvalidTaskException $e) {
      $statusCode = Http::STATUS_BAD_REQUEST;
    } catch (Exception $e) {
      var_dump($e->getMessage());
      $statusCode = Http::STATUS_INTERNAL_ERROR;
    }

    $response['status_code_header'] = $statusCode;
    $response['body'] = null;
    return $response;
  }

  private function updateTask($id)
  {
    $statusCode = Http::STATUS_OK;
    $requestBody = file_get_contents('php://input');
    $jsonBody = json_decode($requestBody);

    $task = new TaskModel(
      null,
      $jsonBody->name,
      $jsonBody->description,
      $jsonBody->autor,
      $jsonBody->isComplete,
      null,
      null
    );

    try {
      $task->validate();
      $this->taskService->updateTask($id, $task);
    } catch (InvalidTaskException $e) {
      $statusCode = Http::STATUS_BAD_REQUEST;
    } catch (TaskNotFoundException $e) {
      $statusCode = Http::STATUS_NOT_FOUD;
    } catch (Exception $e) {
      $statusCode = Http::STATUS_INTERNAL_ERROR;
    }

    $response['status_code_header'] = $statusCode;
    $response['body'] = null;
    return $response;
  }

  private function deletetask($id)
  {
    $statusCode = Http::STATUS_OK;

    try {
      $this->taskService->deleteTask($id);
    } catch (TaskNotFoundException $e) {
      $statusCode = Http::STATUS_NOT_FOUD;
    } catch (Exception $e) {
      $statusCode = Http::STATUS_INTERNAL_ERROR;
    }

    $response['status_code_header'] = $statusCode;
    $response['body'] = null;
    return $response;
  }

  private function notFoundResponse()
  {
    $response['status_code_header'] = Http::STATUS_NOT_FOUD;
    $response['body'] = null;
    return $response;
  }
}

<?php

namespace Src;

use PDO;

class TaskRepository
{
  private $db;

  public function __construct($db)
  {
    $this->db = $db;
  }

  public function getAllTasks()
  {
    $query = "SELECT t.* FROM todo t";
    $stmt = $this->db->query($query);

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $mappedRows = array();
    foreach ($rows as $row) {
      array_push($mappedRows, new TaskModel(
        $row['id'],
        $row['name'],
        $row['description'],
        $row['autor'],
        boolval($row['is_complete']),
        $row['create_at'],
        $row['update_at'],
      ));
    }

    return $mappedRows;
  }

  public function getTaskById($id)
  {
    $query = "SELECT t.* FROM todo t WHERE t.id=?";
    $stmt = $this->db->prepare($query);
    $stmt->execute([$id]);

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row != null) {
      $row = new TaskModel(
        $row['id'],
        $row['name'],
        $row['description'],
        $row['autor'],
        boolval($row['is_complete']),
        $row['create_at'],
        $row['update_at'],
      );
    }

    return $row;
  }

  public function createTask($task)
  {
    $sql = "INSERT INTO todo(name, description, autor, is_complete, create_at, update_at) 
    VALUES (:name, :description, :autor, :isComplete, NOW(), NOW())";

    $stmt = $this->db->prepare($sql);

    $stmt->bindValue(':name', $task->name, PDO::PARAM_STR);
    $stmt->bindValue(':description', $task->description, PDO::PARAM_STR);
    $stmt->bindValue(':autor', $task->autor, PDO::PARAM_STR);
    $stmt->bindValue(':isComplete', $task->isComplete, PDO::PARAM_BOOL);

    $stmt->execute();
  }

  public function updateTask($id, $task)
  {
    $sql = "UPDATE todo t SET name=:name, description=:description, autor=:autor, is_complete=:isComplete, update_at=NOW() WHERE t.id=:id";

    $stmt = $this->db->prepare($sql);

    $stmt->bindValue(':name', $task->name, PDO::PARAM_STR);
    $stmt->bindValue(':description', $task->description, PDO::PARAM_STR);
    $stmt->bindValue(':autor', $task->autor, PDO::PARAM_STR);
    $stmt->bindValue(':isComplete', $task->isComplete, PDO::PARAM_BOOL);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);

    $stmt->execute();
  }

  public function deleteTask($id)
  {
    $sql = "DELETE FROM todo t WHERE t.id=?";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([$id]);
  }
}

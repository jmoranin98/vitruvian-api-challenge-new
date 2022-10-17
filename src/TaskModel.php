<?php

namespace Src;

use Exception;

class InvalidTaskException extends Exception
{
}

class TaskModel
{
  public $id;
  public $name;
  public $description;
  public $autor;
  public $isComplete;
  public $createAt;
  public $updateAt;

  public function __construct(
    $id,
    $name,
    $description,
    $autor,
    $isComplete,
    $createAt,
    $updateAt
  ) {
    $this->id = $id;
    $this->name = $name;
    $this->description = $description;
    $this->autor = $autor;
    $this->isComplete = $isComplete;
    $this->createAt = $createAt;
    $this->updateAt = $updateAt;
  }

  public function validate()
  {
    if ($this->name === null || $this->name === "") {
      throw new InvalidTaskException;
    }

    if ($this->description === null || $this->description === "") {
      throw new InvalidTaskException;
    }

    if ($this->autor === null || $this->autor === "") {
      throw new InvalidTaskException;
    }

    if ($this->isComplete === null) {
      throw new InvalidTaskException;
    }
  }
}

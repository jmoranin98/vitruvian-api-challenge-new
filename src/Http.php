<?php

namespace Src;

class Http {
  const STATUS_OK = "HTTP/1.1 200 OK";
  const STATUS_NOT_FOUD = "HTTP/1.1 404 Not Found";
  const STATUS_CREATED = "HTTP/1.1 201 Created";
  const STATUS_INTERNAL_ERROR = "HTTP/1.1 500 Internal Server Error";
  const STATUS_BAD_REQUEST = "HTTP/1.1 400 Bad Request";
}
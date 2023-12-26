<?php

namespace DUVX\Models\Builders;

use DUVX\Models\PostModel;

class PostBuilder
{
  private $uuid = null;
  private $userUuid = null;
  private $topicUuid = null;
  private $title = null;
  private $content = null;
  private $status = null;
  private $createdAt = null;
  private $updatedAt = null;
  private $location = null;

  public function uuid($uuid)
  {
    $this->uuid = $uuid;
    return $this;
  }

  public function userUuid($userUuid)
  {
    $this->userUuid = $userUuid;
    return $this;
  }

  public function topicUuid($topicUuid)
  {
    $this->topicUuid = $topicUuid;
    return $this;
  }

  public function title($title)
  {
    $this->title = $title;
    return $this;
  }

  public function content($content)
  {
    $this->content = $content;
    return $this;
  }

  public function status($status)
  {
    $this->status = $status;
    return $this;
  }

  public function createdAt($createdAt)
  {
    $this->createdAt = $createdAt;
    return $this;
  }

  public function updatedAt($updatedAt)
  {
    $this->updatedAt = $updatedAt;
    return $this;
  }

  public function location($location)
  {
    $this->location = $location;
    return $this;
  }

  public function build()
  {
    return new PostModel(
      $this->uuid,
      $this->userUuid,
      $this->topicUuid,
      $this->title,
      $this->content,
      $this->status,
      $this->createdAt,
      $this->updatedAt,
      $this->location
    );
  }

  function fromDatabase($data)
  {
    $this->uuid = $data['uuid'];
    $this->userUuid = $data['user_uuid'];
    $this->topicUuid = $data['topic_uuid'];
    $this->title = $data['title'];
    $this->content = $data['content'];
    $this->status = $data['status'];
    $this->createdAt = $data['created_at'];
    $this->updatedAt = $data['updated_at'];
    $this->location = $data['location'];
    return $this;
  }

  /**
   * @param \DunnServer\Utils\DunnMap<string> $data
   */
  function fromBody($data)
  {
    $this->topicUuid = $data->get('topic');
    $this->title = $data->get('title');
    $this->content = $data->get('content');
    $this->location = $data->get('location');
    return $this;
  }
}

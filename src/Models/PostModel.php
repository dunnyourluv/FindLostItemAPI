<?php

namespace DUVX\Models;

use DunnServer\Utils\DunnArray;
use DunnServer\Utils\DunnMap;
use DUVX\Exceptions\PostException;
use DUVX\Models\Builders\PostBuilder;
use DUVX\Utils\Database;
use DUVX\Utils\StringBuilder;

/**
 * @extends Model<PostModel>
 */
class PostModel extends Model
{
  private $uuid;
  private $userUuid;
  private $topicUuid;
  private $title;
  private $content;
  private $status;
  private $location;
  private $createdAt;
  private $updatedAt;

  public function __construct($uuid = null, $userUuid = null, $topicUuid = null, $title = null, $content = null, $status = null, $createdAt = null, $updatedAt = null, $location = null)
  {
    parent::__construct('posts');
    $this->uuid = $uuid;
    $this->userUuid = $userUuid;
    $this->topicUuid = $topicUuid;
    $this->title = $title;
    $this->content = $content;
    $this->status = $status;
    $this->createdAt = $createdAt;
    $this->updatedAt = $updatedAt;
    $this->location = $location;
  }

  /**
   * @return PostBuilder
   */
  static function builder()
  {
    return new PostBuilder();
  }

  function toDatabase()
  {
    return [
      'uuid' => $this->uuid,
      'user_uuid' => $this->userUuid,
      'topic_uuid' => $this->topicUuid,
      'title' => $this->title,
      'content' => $this->content,
      'status' => $this->status,
      'location' => $this->location,
    ];
  }

  function save()
  {
    $data = $this->toDatabase();
    if (!$data['uuid'])
      $data['uuid'] = uniqid();
    if (!$data['status'])
      $data['status'] = 'pending';

    $topic = new TopicModel($this->topicUuid);
    if (!$topic->has())
      throw new PostException('Topic not found', 404);
    $user = UserModel::builder()->uuid($this->userUuid)->build();
    if (!$user->getById())
      throw new PostException('User not found', 404);
    $errors = $this->validate();
    if ($errors->length() > 0)
      throw new PostException($errors->values()->join(', '), 400);
    $db = Database::connect();
    $db->insert($this->getTableName(), $data);
    return $this->getById();
  }

  function update()
  {
    if (!$this->has())
      throw new PostException('Post not found', 404);
    $db = Database::connect();
    $data = $this->toDatabase();
    unset($data['uuid']);
    unset($data['user_uuid']);

    if ($this->topicUuid) {
      $topic = new TopicModel($this->topicUuid);
      if (!$topic->has())
        throw new PostException('Topic not found', 404);
    }

    $user = UserModel::builder()->uuid($this->userUuid)->build();
    if (!$user->getById())
      throw new PostException('User not found', 404);
    $data = $this->getValuesWithoutNull($data);
    $db->update($this->getTableName(), $data, 'uuid = :uuid', ['uuid' => $this->uuid]);
    return $this->getById();
  }

  function delete()
  {
    if (!$this->has())
      throw new PostException('Post not found', 404);
    $db = Database::connect();
    $success = $db->delete($this->getTableName(), 'uuid = :uuid', ['uuid' => $this->uuid]);
    return $success;
  }

  /**
   * @return DunnArray<PostModel>
   */
  function getAll($limit = 0, $offset = 0)
  {
    $db = Database::connect();
    $sql = new StringBuilder();
    $sql->append('SELECT * FROM ' . $this->getTableName());
    if ($limit > 0)
      $sql->append(' LIMIT ' . $limit);
    if ($offset > 0)
      $sql->append(' OFFSET ' . $offset);
    $stm = $db->run($sql->toString());
    $data = new DunnArray(...$stm->fetchAll());
    return $data->map(function ($item) {
      return self::builder()->fromDatabase($item)->build();
    });
  }

  function getById()
  {
    $db = Database::connect();

    $stm = $db->run('SELECT * FROM ' . $this->getTableName() . ' WHERE uuid = :uuid', ['uuid' => $this->uuid]);
    $data = $stm->fetch();
    return $data ? self::builder()->fromDatabase($data)->build() : null;
  }

  function jsonSerialize(): mixed
  {
    $map = new DunnMap();
    $map->set('content', $this->content);
    $map->set('title', $this->title);
    $map->set('createdAt', $this->createdAt);
    $map->set('updatedAt', $this->updatedAt);
    $map->set('uuid', $this->uuid);
    $map->set('location', $this->location);
    $map->set('status', $this->status);
    $user = UserModel::builder()->uuid($this->userUuid)->build()->getById();
    $map->set('user', $user);
    $topic = new TopicModel($this->topicUuid);
    $topic = $topic->getById();
    $map->set('topic', $topic);
    $images = new ImageModel(null, $this->uuid);
    $images = $images->getsWithPostUuid();
    $map->set('images', $images);
    return $map->toArray();
  }

  function getsFromUser($limit = 0, $offset = 0)
  {
    $db = Database::connect();
    $sql = new StringBuilder();
    $sql->append('SELECT * FROM ' . $this->getTableName() . ' WHERE user_uuid = :user_uuid');
    if ($limit > 0)
      $sql->append(' LIMIT ' . $limit);
    if ($offset > 0)
      $sql->append(' OFFSET ' . $offset);
    $stm = $db->run($sql->toString(), ['user_uuid' => $this->userUuid]);
    $data = new DunnArray(...$stm->fetchAll());
    return $data->map(function ($item) {
      return self::builder()->fromDatabase($item)->build();
    });
  }

  /**
   * @return DunnArray<PostModel>
   */
  function getSWithStatus($limit = 0, $offset = 0)
  {
    $db = Database::connect();
    $sql = new StringBuilder();
    $sql->append('SELECT * FROM ' . $this->getTableName() . ' WHERE status = ?');
    $sql->append(' ORDER BY created_at DESC');
    if ($limit > 0)
      $sql->append(' LIMIT ' . $limit);
    if ($offset > 0)
      $sql->append(' OFFSET ' . $offset);

    $stm = $db->run($sql->toString(), [$this->status]);
    $data = new DunnArray(...$stm->fetchAll());
    return $data->map(function ($item) {
      return self::builder()->fromDatabase($item)->build();
    });
  }

  static function search($keyword = '', $topic = '', $address = '')
  {
    $sql = new StringBuilder();
    $db = Database::connect();
    $sql->append('SELECT * FROM posts WHERE ');
    $sql->append('title LIKE :keyword ');
    if ($topic) {
      $sql->append('AND topic_uuid = :topic ');
    }

    if ($address) {
      $sql->append('AND location LIKE :address ');
    }

    $sql->append('ORDER BY created_at DESC');

    $stmt = $db->prepare($sql->toString());

    $stmt->bindValue(':keyword', '%' . $keyword . '%');
    if ($topic) {
      $stmt->bindValue(':topic', $topic);
    }
    if ($address) {
      $stmt->bindValue(':address', '%' . $address . '%');
    }

    $stmt->execute();
    $posts = new DunnArray(...$stmt->fetchAll());
    $stmt->closeCursor();
    return $posts->map(function ($post) {
      return PostModel::builder()->fromDatabase($post)->build();
    });
  }

  /**
   * Get the value of title
   */
  public function getTitle()
  {
    return $this->title;
  }

  /**
   * Set the value of title
   *
   * @return  self
   */
  public function setTitle($title)
  {
    $this->title = $title;

    return $this;
  }

  /**
   * Get the value of content
   */
  public function getContent()
  {
    return $this->content;
  }

  /**
   * Set the value of content
   *
   * @return  self
   */
  public function setContent($content)
  {
    $this->content = $content;

    return $this;
  }

  /**
   * Get the value of status
   */
  public function getStatus()
  {
    return $this->status;
  }

  /**
   * Set the value of status
   *
   * @return  self
   */
  public function setStatus($status)
  {
    $this->status = $status;

    return $this;
  }

  /**
   * Get the value of createdAt
   */
  public function getCreatedAt()
  {
    return $this->createdAt;
  }

  /**
   * Set the value of createdAt
   *
   * @return  self
   */
  public function setCreatedAt($createdAt)
  {
    $this->createdAt = $createdAt;

    return $this;
  }

  /**
   * Get the value of updatedAt
   */
  public function getUpdatedAt()
  {
    return $this->updatedAt;
  }

  /**
   * Set the value of updatedAt
   *
   * @return  self
   */
  public function setUpdatedAt($updatedAt)
  {
    $this->updatedAt = $updatedAt;

    return $this;
  }

  /**
   * Get the value of uuid
   */
  public function getUuid()
  {
    return $this->uuid;
  }

  /**
   * Set the value of uuid
   *
   * @return  self
   */
  public function setUuid($uuid)
  {
    $this->uuid = $uuid;

    return $this;
  }

  /**
   * Get the value of userUuid
   */
  public function getUserUuid()
  {
    return $this->userUuid;
  }

  /**
   * Set the value of userUuid
   *
   * @return  self
   */
  public function setUserUuid($userUuid)
  {
    $this->userUuid = $userUuid;

    return $this;
  }

  /**
   * Get the value of topicUuid
   */
  public function getTopicUuid()
  {
    return $this->topicUuid;
  }

  /**
   * Set the value of topicUuid
   *
   * @return  self
   */
  public function setTopicUuid($topicUuid)
  {
    $this->topicUuid = $topicUuid;

    return $this;
  }

  /**
   * Get the value of location
   */
  public function getLocation()
  {
    return $this->location;
  }

  /**
   * Set the value of location
   *
   * @return  self
   */
  public function setLocation($location)
  {
    $this->location = $location;

    return $this;
  }
}

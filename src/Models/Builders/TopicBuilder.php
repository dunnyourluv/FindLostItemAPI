<?php
namespace DUVX\Models\Builders;
use DUVX\Models\TopicModel;

class TopicBuilder{
    // CREATE TABLE `post_topic` (
    //     `uuid` VARCHAR(36) NOT NULL PRIMARY KEY,
    //     `name` VARCHAR(255) NOT NULL,
    //     `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    //   );
    private $uuid;
    private $name;
    private $createdAt;
    private $updatedAt;
    // getters and setters
    function uuid($id)
    {
        $this->uuid = $id;
        return $this;
    }
    function name($name)
    {
        $this->name = $name;
        return $this;
    }
    function createdAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }
    function updatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
    function build()
    {
        return new TopicModel($this->uuid, $this->name, $this->createdAt, $this->updatedAt);
    }
    public function fromBody($body){
        $this->name = $body->get('name');
        $this->uuid = $body->get('uuid');
        return $this;
    }
}
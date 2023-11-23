<?php
namespace DUVX\Models;

use DunnServer\Utils\DunnArray;
use DUVX\Models\Builders\TopicBuilder;
use DUVX\Utils\Database;
use DUVX\Utils\StringBuilder;

class TopicModel{
    private $uuid;
    private $name;
    private $createdAt;
    private $updatedAt;

    function __construct($uuid, $name, $createdAt, $updatedAt)
    {
        $this->uuid = $uuid;
        $this->name = $name;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return TopicBuilder
     */
    static function builder()
    {
        return new TopicBuilder();
    }

    function jsonSerialize(): mixed
    {
        return [
            "uuid" => $this->uuid,
            "name" => $this->name,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt
        ];
    }

    function getName()
    {
        return $this->name;
    }

    function getUuid()
    {
        return $this->uuid;
    }

    function getCreatedAt()
    {
        return $this->createdAt;
    }

    function getUpdatedAt()
    {
        return $this->updatedAt;
    }
    public function getAll(){
        $db = Database::connect();
        $sql = "SELECT * FROM post_topic";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $data = new DunnArray(...$stmt->fetchAll());
    }
    public function getOne($id){

    }
    public function create($data){

    }
    public function update($id, $data){

    }
    public function delete($id){

    }

}
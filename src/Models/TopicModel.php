<?php
namespace DUVX\Models;

use DunnServer\Utils\DunnArray;
use DUVX\Models\Builders\TopicBuilder;
use DUVX\Utils\Database;
use DUVX\Utils\StringBuilder;
use DUVX\Models\Model;

class TopicModel extends Model{
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
        return $data;
    }
    public function getById($id){
        
    }
    public function save(){
        $db = Database::connect();
        $data = $this->toDatabase();
        $db->insert($this->tableName, $data);
        return $this->getById($this->uuid);
    }
    public function update(){
        $db = Database::connect();
        $data = $this->getValuesWithoutNull($this->toDatabase());
        return $db->update($this->tableName, $data, 'uuid = :uuid', ['uuid' => $this->uuid]);
    }
    public function delete(){
        $db = Database::connect();
        return $db->delete($this->tableName, 'uuid = :uuid', ['uuid' => $this->uuid]);
    }

    public function toDatabase()
    {
        return [
            "uuid" => $this->uuid,
            "name" => $this->name,
        ];
    }
    public function getValuesWithoutNull($data){
        $result = [];
        foreach ($data as $key => $value) {
            if($value != null){
                $result[$key] = $value;
            }
        }
        return $result;
    }
    function validate()
    {
        $errors = new DunnArray();
        if ($this->name == null) {
            $errors->push('Name is required!');
        }
        return $errors;
    }
}
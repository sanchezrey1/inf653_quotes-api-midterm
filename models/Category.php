<?php
class Category {
    private $conn;
    private $table = 'categories';

    public $id;
    public $category;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read($id = null) {
        $query = 'SELECT id, category FROM ' . $this->table;

        if ($id !== null) {
            $query .= ' WHERE id = :id';
        }

        $query .= ' ORDER BY id';

        $stmt = $this->conn->prepare($query);

        if ($id !== null) {
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        }

        $stmt->execute();

        return $stmt;
    }

    public function create() {
        $query = 'INSERT INTO ' . $this->table . ' (category)
                  VALUES (:category)
                  RETURNING id, category';

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':category', $this->category);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update() {
        $query = 'UPDATE ' . $this->table . '
                  SET category = :category
                  WHERE id = :id
                  RETURNING id, category';

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
        $stmt->bindValue(':category', $this->category);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function delete() {
    $query = 'DELETE FROM ' . $this->table . '
              WHERE id = :id
              RETURNING id';

    $stmt = $this->conn->prepare($query);
    $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
  }
}
?>
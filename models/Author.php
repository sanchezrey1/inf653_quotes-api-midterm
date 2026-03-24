<?php
class Author {
    private $conn;
    private $table = 'authors';

    public $id;
    public $author;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read($id = null) {
        $query = 'SELECT id, author FROM ' . $this->table;

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
        $query = 'INSERT INTO ' . $this->table . ' (author)
                  VALUES (:author)
                  RETURNING id, author';

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':author', $this->author);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update() {
        $query = 'UPDATE ' . $this->table . '
                  SET author = :author
                  WHERE id = :id
                  RETURNING id, author';

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
        $stmt->bindValue(':author', $this->author);
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
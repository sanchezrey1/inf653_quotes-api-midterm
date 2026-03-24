<?php
class Quote {
    private $conn;
    private $table = 'quotes';

    public $id;
    public $quote;
    public $author_id;
    public $category_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        $query = 'SELECT 
                    q.id,
                    q.quote,
                    a.author,
                    c.category
                  FROM ' . $this->table . ' q
                  JOIN authors a ON q.author_id = a.id
                  JOIN categories c ON q.category_id = c.id
                  ORDER BY q.id';

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    public function read_filtered($id = null, $author_id = null, $category_id = null) {
        $query = 'SELECT 
                    q.id,
                    q.quote,
                    a.author,
                    c.category
                  FROM ' . $this->table . ' q
                  JOIN authors a ON q.author_id = a.id
                  JOIN categories c ON q.category_id = c.id
                  WHERE 1=1';

        if ($id !== null) {
            $query .= ' AND q.id = :id';
        }

        if ($author_id !== null) {
            $query .= ' AND q.author_id = :author_id';
        }

        if ($category_id !== null) {
            $query .= ' AND q.category_id = :category_id';
        }

        $query .= ' ORDER BY q.id';

        $stmt = $this->conn->prepare($query);

        if ($id !== null) {
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        }

        if ($author_id !== null) {
            $stmt->bindValue(':author_id', $author_id, PDO::PARAM_INT);
        }

        if ($category_id !== null) {
            $stmt->bindValue(':category_id', $category_id, PDO::PARAM_INT);
        }

        $stmt->execute();

        return $stmt;
    }

    public function authorExists($author_id) {
        $query = 'SELECT id FROM authors WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $author_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    public function categoryExists($category_id) {
        $query = 'SELECT id FROM categories WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $category_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    public function create() {
        $query = 'INSERT INTO ' . $this->table . ' (quote, author_id, category_id)
                  VALUES (:quote, :author_id, :category_id)
                  RETURNING id, quote, author_id, category_id';

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':quote', $this->quote);
        $stmt->bindValue(':author_id', $this->author_id, PDO::PARAM_INT);
        $stmt->bindValue(':category_id', $this->category_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update() {
        $query = 'UPDATE ' . $this->table . '
                  SET quote = :quote,
                      author_id = :author_id,
                      category_id = :category_id
                  WHERE id = :id
                  RETURNING id, quote, author_id, category_id';

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
        $stmt->bindValue(':quote', $this->quote);
        $stmt->bindValue(':author_id', $this->author_id, PDO::PARAM_INT);
        $stmt->bindValue(':category_id', $this->category_id, PDO::PARAM_INT);
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
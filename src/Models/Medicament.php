<?php

class Medicament {
    private $id;
    private $name;
    private $description;
    private $price;

    public function __construct($id, $name, $description, $price) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getPrice() {
        return $this->price;
    }

    public function save($conn) {
        $stmt = $conn->prepare("INSERT INTO medicaments (name, description, price) VALUES (?, ?, ?)");
        $stmt->bind_param("ssd", $this->name, $this->description, $this->price);
        return $stmt->execute();
    }

    public static function findById($conn, $id) {
        $stmt = $conn->prepare("SELECT * FROM medicaments WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_object('Medicament');
    }

    public static function all($conn) {
        $result = $conn->query("SELECT * FROM medicaments");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function update($conn) {
        $stmt = $conn->prepare("UPDATE medicaments SET name = ?, description = ?, price = ? WHERE id = ?");
        $stmt->bind_param("ssdi", $this->name, $this->description, $this->price, $this->id);
        return $stmt->execute();
    }

    public function delete($conn) {
        $stmt = $conn->prepare("DELETE FROM medicaments WHERE id = ?");
        $stmt->bind_param("i", $this->id);
        return $stmt->execute();
    }
}
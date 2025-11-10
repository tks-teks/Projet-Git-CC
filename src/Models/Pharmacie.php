<?php

class Pharmacie {
    private $id;
    private $name;
    private $address;
    private $phone;

    public function __construct($id, $name, $address, $phone) {
        $this->id = $id;
        $this->name = $name;
        $this->address = $address;
        $this->phone = $phone;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getAddress() {
        return $this->address;
    }

    public function getPhone() {
        return $this->phone;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setAddress($address) {
        $this->address = $address;
    }

    public function setPhone($phone) {
        $this->phone = $phone;
    }

    public function save($conn) {
        $stmt = $conn->prepare("INSERT INTO pharmacies (name, address, phone) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $this->name, $this->address, $this->phone);
        return $stmt->execute();
    }

    public static function findById($conn, $id) {
        $stmt = $conn->prepare("SELECT * FROM pharmacies WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_object('Pharmacie');
    }

    public static function all($conn) {
        $result = $conn->query("SELECT * FROM pharmacies");
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>
<?php 
class AccountModel { 
private $conn; 
private $table_name = "account"; 
public function __construct($db) { 
$this->conn = $db; 
}
public function getAccountByUsername($username) { 
$query = "SELECT * FROM " . $this->table_name . " WHERE username = :username 
LIMIT 0,1"; 
$stmt = $this->conn->prepare($query); 
$stmt->bindParam(":username", $username); 
$stmt->execute(); 
return $stmt->fetch(PDO::FETCH_OBJ); 
} 
public function save($username, $fullName, $phone, $password, $role = 'user') { 
if ($this->getAccountByUsername($username)) { 
return false; 
} 

$username = htmlspecialchars(strip_tags($username)); 
$fullName = htmlspecialchars(strip_tags($fullName)); 
$phone = htmlspecialchars(strip_tags($phone)); 
$password = password_hash($password, PASSWORD_BCRYPT); 
$role = htmlspecialchars(strip_tags($role)); 

$columns = ['username', 'password', 'role'];
$values = [':username', ':password', ':role'];
$params = [
    ':username' => $username,
    ':password' => $password,
    ':role' => $role
];

$checkColsStmt = $this->conn->query("SHOW COLUMNS FROM " . $this->table_name);
$existingCols = $checkColsStmt->fetchAll(PDO::FETCH_COLUMN, 0);

if (in_array('fullname', $existingCols, true)) {
    $columns[] = 'fullname';
    $values[] = ':fullname';
    $params[':fullname'] = $fullName;
}

if (in_array('phone', $existingCols, true)) {
    $columns[] = 'phone';
    $values[] = ':phone';
    $params[':phone'] = $phone;
}

$query = "INSERT INTO " . $this->table_name .
         " (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $values) . ")";
$stmt = $this->conn->prepare($query); 

return $stmt->execute($params); 
} 
} 
?>
<?php

require_once __DIR__.'/db.php';


// create a MongoDB client instance
$client = new MongoDB\Client('mongodb+srv://liyonraja21:Liyon215@cluster0.nj5bn4w.mongodb.net/?retryWrites=true&w=majority');
// select the database and collection
$database = $client->selectDatabase('project');

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // Get the POST data from the request body
  $data = json_decode(file_get_contents('php://input'), true);

  // Validate the input data
  $name = $data['name'];
  $password = $data['password'];
  $email = $data['email'];
  $phone = $data['phone'];
  


  if (empty($name) || empty($password) || empty($email)||empty($phone)) {
    // Return an error if any field is empty
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'All fields are required.']);
    exit();
  }

  // Check if the email address is valid
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Invalid email address.']);
    exit();
  }


  // Check if the username is already taken
  $stmt = $db->prepare('SELECT COUNT(*) FROM user WHERE email = :email');
  $stmt->bindValue(':email', $email, PDO::PARAM_STR);
  $stmt->execute();
  $count = $stmt->fetchColumn();

  if ($count > 0) {
    // Return an error if the username is already taken
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'email is already taken.']);
    exit();
  }

  // Hash the password
  $password_hash = password_hash($password, PASSWORD_DEFAULT);

  // Insert the new user into the database
  $stmt = $db->prepare('INSERT INTO user (name,mobile_no,email,password,status) VALUES (:name,:mobile_no,:email,:password,:status)');
  $stmt->bindValue(':email', $email, PDO::PARAM_STR);
  $stmt->bindValue(':password', $password_hash, PDO::PARAM_STR);
  $stmt->bindValue(':mobile_no', $phone, PDO::PARAM_INT);
  $stmt->bindValue(':name', $name, PDO::PARAM_STR);
  $stmt->bindValue(':status', true, PDO::PARAM_BOOL);
  $stmt->execute();

  // Insert the New user basic information to profile

  $collection = $database->selectCollection('user_profile');
  $document = [
    'name' => $name,
    'email' => $email,
    'mobile' => $mobile
  ];
  $result = $collection->insertOne($document);
  // Return a success message
  http_response_code(200); // OK
  echo json_encode(['status' => true , 'message' => 'User registered successfully.']);
} else {
  // Return an error if the request method is not POST
  http_response_code(405); // Method Not Allowed
  echo json_encode(['status' => false ,'error' => 'Only POST requests are allowed.']);
  exit();
}

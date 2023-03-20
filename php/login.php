<?php

require_once 'db.php';

// Connect to Redis server
$redis = new Redis();
$redis->connect('redis-14225.c212.ap-south-1-1.ec2.cloud.redislabs.com', 14225);
$redis->auth('7Sox6EIkU3s53iJN9XhMuF5qRPOodAqJ');
// Set Redis as the session handler
ini_set('session.save_handler', 'redis');
ini_set('session.save_path', 'tcp://redis-14225.c212.ap-south-1-1.ec2.cloud.redislabs.com:14225?auth=7Sox6EIkU3s53iJN9XhMuF5qRPOodAqJ');

// Start PHP session
session_start();

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // Get the POST data from the request body
  $data = json_decode(file_get_contents('php://input'), true);

  // Validate the input data
  $username = $data['username'];
  $password = $data['password'];

  if (empty($username) || empty($password)) {
    // Return an error if any field is empty
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'All fields are required.']);
    exit();
  }



  // Check if the username exists in the database
  $stmt = $db->prepare('SELECT * FROM user WHERE email = :username');
  $stmt->bindValue(':username', $username, PDO::PARAM_STR);
  $stmt->execute();
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$user) {
    // Return an error if the username is not found
    http_response_code(401); // Unauthorized
    echo json_encode(['error' => 'Invalid username or password.']);
    exit();
  }

  // Check if the password is correct
  if (!password_verify($password, $user['password'])) {
    // Return an error if the password is incorrect
    http_response_code(401); // Unauthorized
    echo json_encode(['error' => 'Invalid username or password.']);
    exit();
  }

  // Store user data in session
  $_SESSION['user_id'] = $user['id'];
  $_SESSION['username'] = $user['email'];

  // Set Redis session expiration time to 24 hours
  $redis->expire(session_id(), 86400);

  // Return success message
  http_response_code(200); // OK
  echo json_encode(['status' => true, 'message' => 'Login successful.', 'userdata' => json_encode($_SESSION) ]);
} else {
  // Return an error if the request method is not POST
  http_response_code(405); // Method Not Allowed
  echo json_encode(['error' => 'Only POST requests are allowed.']);
  exit();
}

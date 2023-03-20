<?php
require_once __DIR__ . '/vendor/autoload.php'; // include the MongoDB PHP library

require('mongodb.php');

$collection = $database->selectCollection('user_profile');

// Get user profile data API endpoint
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Get the user ID from the request parameters
    $email = $_GET['email'];


    // Get the users collection
    $users = $collection;

    // Query the database to get the user profile data
    $result = $users->findOne(['email' => $email]);

    // Return the profile data as a JSON response
    header('Content-Type: application/json');
    echo json_encode($result);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get the POST data from the request body
    $data = json_decode(file_get_contents('php://input'), true);




    // retrieve the profile data from the HTTP POST request
    $profile_id = $data["id"];
    $name = $data['name'];
    $parent = $data['parent'];
    $gender = $data['gender'];
    $dob = $data['dob'];
    $age = $data['age'];
    $bloodgroup = $data['blood'];
    $email = $data['email'];
    $alt_email = $data['alt_email'];
    $mobile = $data['mobile'];
    $alt_mobile = $data['alt_mobile'];
    $present_address = $data['alt_mobile'];
    $permanent_address = $data['permanent_address'];



    // create a new document with the profile data
    $document = [
        'name' => $name,
        'parent' => $parent,
        'gender' => $gender,
        'dob' => $dob,
        'age' => $age,
        'bloodgroup' => $bloodgroup,
        'email' => $email,
        'alt_email' => $alt_email,
        'mobile' => $mobile,
        'alt_mobile' => $alt_mobile,
        'present_address' => $present_address,
        'permanent_address' => $permanent_address,
        'status' => 1
    ];
    // Update the user data
    $filter = ['_id' => new MongoDB\BSON\ObjectID($profile_id)]; // Replace with the ID of the user you want to update
    $update = ['$set' => $document]; // Replace with the new user data
    $options = ['upsert' => true];

    $result = $collection->updateOne($filter, $update, $options);

    // print_r($result);
    // insert the document into the collection
    // $result = $collection->insertOne($document);

    // check if the insertion was successful
    if ($result->getModifiedCount() === 1) {
        http_response_code(200); // OK
        echo json_encode(['status' => true, 'message' => 'Profile Data saved successful.']);
    } else {
        http_response_code(500);
        echo 'Failed to store profile data.';
    }
}
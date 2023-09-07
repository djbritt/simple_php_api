<?php
error_reporting(E_ALL);

// Define your authentication token (change this to a secure token).
$authToken = "PUT_KEY_HERE";

// Check if the request method is POST.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Check if the auth token matches the expected value.
  $providedAuthToken = $_SERVER['HTTP_AUTH_TOKEN'] ?? '';
  if ($providedAuthToken !== $authToken) {
    http_response_code(401); // Unauthorized
    die("Authentication failed");
  }

  // Read the JSON data from the POST request body.
  $postData = file_get_contents('php://input');

  // Remove double quotes if present.
  $postData = trim($postData, '"');

  // Define the path to the JSON file where data will be stored.
  $dataFilePath = __DIR__ . '/data.json';

  // Read existing data from the file (if it exists).
  $existingData = file_exists($dataFilePath) ? json_decode(file_get_contents($dataFilePath), true) : [];

  // Ensure that the "input" key always holds an array.
  if (!isset($existingData["input"])) {
    $existingData["input"] = [];
  }

  // Check if the input data is not already in the "input" array.
  if (!in_array($postData, $existingData["input"])) {
    // Add the input data to the "input" array.
    $existingData["input"][] = $postData;
  }

  // Encode the combined data as JSON.
  $encodedData = json_encode($existingData, JSON_PRETTY_PRINT);

  // Write the JSON data back to the file.
  if (file_put_contents($dataFilePath, $encodedData) !== false) {
    http_response_code(200); // OK
    echo "Data stored successfully.";
  } else {
    http_response_code(500); // Internal Server Error
    die("Failed to store data.");
  }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
  // Check if the auth token matches the expected value.
  $providedAuthToken = $_GET['auth_token'] ?? '';
  if ($providedAuthToken !== $authToken) {
    http_response_code(401); // Unauthorized
    die("Authentication failed");
  }

  // Define the path to the JSON file where data is stored.
  $dataFilePath = __DIR__ . '/data.json';

  // Read data from the file.
  $jsonData = file_exists($dataFilePath) ? json_decode(file_get_contents($dataFilePath), true) : [];

  if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(500); // Internal Server Error
    die("Failed to read data from file.");
  }

  if (isset($jsonData["input"]) && is_array($jsonData["input"])) {
    // Encode the "input" array as JSON and output it.
    header('Content-Type: application/json');
    echo json_encode($jsonData["input"], JSON_PRETTY_PRINT);
  } else {
    http_response_code(404); // Not Found
    die("Data not found");
  }
} else {
  http_response_code(405); // Method Not Allowed
  die("Method not allowed");
}


?>

<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include 'db.php';

// Handle GET request: Fetch all feedback
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT * FROM feedback";
    $result = $conn->query($sql);

    $feedbacks = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $feedbacks[] = $row;
        }
    }

    echo json_encode($feedbacks);
    exit;
}

// Handle POST request: Add new feedback
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['rating']) && isset($data['comments'])) {
        $rating = $conn->real_escape_string($data['rating']);
        $comments = $conn->real_escape_string($data['comments']);

        $sql = "INSERT INTO feedback (rating, comments) VALUES ('$rating', '$comments')";

        if ($conn->query($sql)) {
            echo json_encode(["message" => "Feedback submitted successfully"]);
        } else {
            echo json_encode(["message" => "Error submitting feedback"]);
        }
    } else {
        echo json_encode(["message" => "Invalid input"]);
    }
    exit;
}
?>
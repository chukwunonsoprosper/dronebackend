<?php
include './access/header.php';

$host = "localhost";
$dbname = "drone_gallery";
$username = "root";  // Update if needed
$password = "";      // Update if needed

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Database connection failed"]));
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_FILES["file"]) && isset($_POST["productname"]) && isset($_POST["productlink"])) {
        $productName = $_POST["productname"];
        $productLink = $_POST["productlink"];
        $file = $_FILES["file"];

        $uploadDir = "./uploads/";
        $fileName = time() . "_" . basename($file["name"]);
        $filePath = $uploadDir . $fileName;
        $imagelink = 'http://localhost/dronebackend/api/uploads/' . $fileName;

        if (move_uploaded_file($file["tmp_name"], $filePath)) {
            $stmt = $conn->prepare("INSERT INTO drone_images (product_name, product_link, file_path) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $productName, $productLink, $imagelink);
            
            if ($stmt->execute()) {
                echo json_encode(["success" => true, "message" => "Image uploaded successfully"]);
            } else {
                echo json_encode(["success" => false, "message" => "Database error"]);
            }

            $stmt->close();
        } else {
            echo json_encode(["success" => false, "message" => "File upload failed"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Missing required fields"]);
    }
}

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $result = $conn->query("SELECT * FROM drone_images ORDER BY created_at DESC LIMIT 3");
    
    $images = [];
    while ($row = $result->fetch_assoc()) {
        $images[] = [
            "id" => $row["id"],
            "product_name" => $row["product_name"],
            "product_link" => $row["product_link"],
            "file_path" => $row["file_path"],
            "created_at" => $row["created_at"]
        ];
    }

    echo json_encode($images);
}

$conn->close();
?>

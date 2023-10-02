<?php
$pageTitle="Add Drug Details";
$section = "addDrug";

include 'header.php';

//session_start(); 
require_once("db_connection.php"); 

$categoryOptions = array(
    'CNS Depressants' => 1,
    'CNS Stimulants' => 2,
    'Hallucinogens' => 3,
    'Dissociative Anesthetics' => 4,
    'Narcotic Analgesics' => 5,
    'Inhalants' => 6
);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // get form data
    $drug_name = $_POST['drug_name'];
    $category_name = $_POST['category']; 
    $category_id = $categoryOptions[$category_name]; 
    $description = $_POST['description'];

    //  file upload
    $upload_dir = "images/"; 
    $image_name = $_FILES['image']['name'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_path = $upload_dir . $image_name;

    if (move_uploaded_file($image_tmp_name, $image_path)) {
    $query = "INSERT INTO drug_details (drug_name, category_id, description, image_url) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("siss", $drug_name, $category_id, $description, $image_path);
       if ($stmt->execute()) {
            // if Drug details saved successfully
            header("Location: dashboard.php"); 
            exit;
        } else {
            // Error in saving drug details
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        // Error in file upload
        echo "Error uploading image.";
    }
}

// Close db conn
$conn->close();
?>

<h2>Add Drug Details</h2>
    <form action="process_drug_details.php" method="post" enctype="multipart/form-data">
        <label for="drug_name">Drug Name:</label>
        <input type="text" name="drug_name" required><br><br>

        <label for="category">Category:</label>
        <select name="category" required>
            <option value="1">(CNS) Depressants</option>
            <option value="2">CNS Stimulants</option>
            <option value="3">Hallucinogens</option>
            <option value="4">Dissociative Anesthetics</option>
            <option value="5">Narcotic Analgesics</option>
            <option value="6">Inhalants</option>
        </select><br><br>

        <label for="description">Drug Description:</label>
        <textarea name="description" rows="4" cols="50"></textarea><br><br>

        <label for="image">Drug Image:</label>
        <input type="file" name="image" accept="image/*" required><br><br>

        <input type="submit" value="Add Drug">
    </form>
</div>

<?php include 'footer.php';?>
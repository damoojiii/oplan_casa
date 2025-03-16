<?php
include "session.php";
include("connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if file was uploaded without errors
    if (isset($_FILES["logoFile"]) && $_FILES["logoFile"]["error"] == 0) {
        $allowed = ["jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png"];
        $filename = $_FILES["logoFile"]["name"];
        $filetype = $_FILES["logoFile"]["type"];
        $filesize = $_FILES["logoFile"]["size"];

        // Verify file extension
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if (!array_key_exists($ext, $allowed)) {
            die("Error: Please select a valid file format.");
        }

        // Verify file size - 2MB maximum
        $maxsize = 2 * 1024 * 1024;
        if ($filesize > $maxsize) {
            die("Error: File size is larger than the allowed limit (2MB).");
        }

        // Verify MIME type of the file
        if (in_array($filetype, $allowed)) {
            // Check if the file exists before uploading it
            $newFilename = "logo_" . date("YmdHis") . "." . $ext;
            $uploadPath = "uploads/logos/" . $newFilename;
            
            // Create directory if it doesn't exist
            if (!file_exists("uploads/logos/")) {
                mkdir("uploads/logos/", 0777, true);
            }
            
            if (move_uploaded_file($_FILES["logoFile"]["tmp_name"], $uploadPath)) {
                // Update database with new logo path
                $sql = "SELECT * FROM site_settings WHERE id = 1";
                $result = mysqli_query($conn, $sql);
                
                if (mysqli_num_rows($result) > 0) {
                    // Update existing record
                    $updateSql = "UPDATE site_settings SET logo_path = '$uploadPath', updated_at = NOW() WHERE id = 1";
                } else {
                    // Insert new record
                    $updateSql = "INSERT INTO site_settings (logo_path, created_at, updated_at) VALUES ('$uploadPath', NOW(), NOW())";
                }
                
                if (mysqli_query($conn, $updateSql)) {
                    header("Location: settings.php?success=Logo updated successfully");
                    exit();
                } else {
                    echo "Error updating database: " . mysqli_error($conn);
                }
            } else {
                echo "Error: There was a problem uploading your file. Please try again.";
            }
        } else {
            echo "Error: There was a problem with the file type. Please try again.";
        }
    } else {
        echo "Error: " . $_FILES["logoFile"]["error"];
    }
}

header("Location: settings.php");
exit();
?>

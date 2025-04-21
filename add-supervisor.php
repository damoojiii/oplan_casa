<?php 
    include 'connection.php';
    
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['supervisor'])) {
        $firstname = htmlspecialchars($_POST['firstname']);
        $lastname = htmlspecialchars($_POST['lastname']);
        $position = htmlspecialchars($_POST['position']);
        $contact = htmlspecialchars($_POST['contact']);
        $gender = $_POST['gender'];
        $school = $_POST['school'];
        
        // Validate that all fields have data
        if (empty($firstname) || empty($lastname) || empty($position) || empty($contact)) {
            $_SESSION['message'] = "All fields are required!";
            $_SESSION['message_type'] = "Error";
            $_SESSION['icon'] = "error";
            echo '<script type="text/javascript">
                alert("All fields are required!"); // Show an alert message
                window.location = "add-visitor.php"; 
            </script>';

            exit();
        }
    
        // Prepare SQL query for insertion
        $sql_insert = "INSERT INTO `supervisor_tbl` (`scheduled_id`, `firstname`, `lastname`, `position`, `contact`, `gender`) VALUES (?, ?, ?, ?, ?, ?)";
        if ($stmt = $conn->prepare($sql_insert)) {
            $stmt->bind_param("isssss", $school, $firstname, $lastname, $position, $contact, $gender);   
            // Execute the query and check if successful
            if ($stmt->execute()) {
                $_SESSION['message'] = "Supervisor added successfully!";
                $_SESSION['message_type'] = "Success";
                $_SESSION['icon'] = "success";
            } else {
                $_SESSION['message'] = "Error: " . $stmt->error;
                $_SESSION['message_type'] = "Error";
                $_SESSION['icon'] = "error";
            }

            $stmt->close();
            echo '<script type="text/javascript">
                window.location = "add-visitor.php";
            </script>';
            exit();
        } else {
            $_SESSION['message'] = "Error preparing the query.";
            $_SESSION['message_type'] = "Error";
            $_SESSION['icon'] = "error";
            echo '<script type="text/javascript">
                alert("Error: ' . $conn->error . '"); // Error message
                window.location = "add-visitor.php"; 
            </script>';
            exit();
        }
    }
?>
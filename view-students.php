<?php
    include "session.php";
    include("connection.php");

    $scheduled_id = $_GET['scheduled_id'];

    $sql_fetch = "SELECT * FROM student_tbl WHERE scheduled_id = ? ORDER BY lastname ASC";
    $stmt = $conn->prepare($sql_fetch);
    $stmt->bind_param("i", $scheduled_id);
    $stmt->execute();
    $student_result = $stmt->get_result();

    $sql_fetch1 = "SELECT * FROM supervisor_tbl WHERE scheduled_id = ?";
    $stmt = $conn->prepare($sql_fetch1);
    $stmt->bind_param("i", $scheduled_id);
    $stmt->execute();
    $supervisor_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Tourism</title>
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="vendor/fontawesome-free/css/fontawesome.min.css">
    <link rel="icon" href="img/rosariologo.png">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">

    <style>
    <?php include 'sidebarcss.php'; ?>
    .nav-tabs {
       margin-top: 20px;
    }

    .nav-tabs .nav-item {
        margin-right: 3px;
    }
    .nav-tabs .nav-link {
        border: 2px solid #ddd;
        border-bottom: none;
        border-radius: 8px 8px 0 0;
        background: #f8f9fa;
        color: #333;
        padding: 10px 15px;
        font-weight: bold;
        transition: 0.3s;
    }
    .nav-tabs .nav-link.active {
        background: #273E26;
        color: #fff;
        border-color: #273E26;
    }

    /* Hover Effect */
    .nav-tabs .nav-link:hover {
        background: #273E26;
        border-color: #273E26;
        color: #fff;
    }

    .header-title{
        font-weight: bolder;
    }

    thead,
    th {
        background-color: #5D9C59 !important;
        text-align: center !important;
        color: #fff !important;
    }

    td{
        font-size: 15px !important;
    }
    .schedule-list {
        max-width: 800px;
        margin: auto;
    }
    .schedule-item {
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 10px;
        background-color: #f8f9fa;
        border-left: 6px solid limegreen;
        transition: all 0.3s ease;
    }
    .schedule-item:hover {
        background-color: #e9ecef;
        cursor: pointer;
    }
    .schedule-header {
        font-weight: bold;
        font-size: 18px;
    }
    .schedule-details {
        font-size: 14px;
        color: #555;
    }
    .status-badge {
        padding: 5px 10px;
        font-size: 12px;
        border-radius: 5px;
    }
    .upcoming { background-color: #ffc107; color: #000; }
    .ongoing { background-color: #28a745; color: #fff; }
    .completed { background-color: #6c757d; color: #fff; }

    .search-box {
        border: 2px solid #5D9C59;
        width: 300px;
    }

    .search-box:focus {
        border: 2px solid green;
        box-shadow: 0 0 10px green;
    }
    
    </style>
</head>

<body>
    <!-- Header -->
    <header id="header" class="bg-light shadow-sm">
        <button id="hamburger" class="btn btn-primary" onclick="toggleSidebar()">
            ☰
        </button>
    </header>

    <!-- Sidebar -->
    <div id="sidebar" class="d-flex flex-column p-3 vh-100">
        <!-- Logo/Icon -->
        <div class="text-center">
            <div class="logo-circle">
            <?php
                    $db = new mysqli('localhost', 'root', '', 'casadb');
                    if ($db->connect_error) {
                    die("Connection failed: " . $db->connect_error);
                    }
                    $sql = "SELECT logo_path FROM site_settings WHERE id = 1";
                    $result = $db->query($sql);
                    
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $logo = !empty($row['logo_path']) ? $row['logo_path'] : 'img/rosariologo.png'; // Use default if empty
                            echo "<div class='logo-item'>";
                            echo "<img src='$logo' alt='Logo' class='logo-circle' style='width: 80px; height: 80px;'>";
                            echo "</div>";
                        }
                    } else {
                        // If walay logong makita, display the default logo nganii para di empty yung logo
                        echo "<div class='logo-item'>";
                        echo "<img src='img/rosariologo.png' alt='Default Logo' class='logo-circle' style='width: 80px; height: 80px;'>";
                        echo "</div>";
                    }
                ?>
            </div>
        </div>

        <h6 class="text-white text-center mt-2">Tourism Office</h6>
        <p class="text-white text-center small">Municipality of Rosario</p>
        
        <hr>

        <div class="text-white main-menu">Main Menu</div>
        <ul class="nav flex-column mb-auto">
            <li class="nav-item">
                <a href="admin-dashboard.php" class="nav-link">
                    <i class="fa-solid fa-list"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="visitorslist.php" class="nav-link">
                    <i class="fa-solid fa-user-group"></i> Visitor's List
                </a>
            </li>
            <li>
                <a href="trips.php" class="nav-link active">
                    <i class="fa-solid fa-bus"></i> Scheduled Field Trips
                </a>
            </li>
            <li>
                <a href="history.php" class="nav-link">
                    <i class="fa-solid fa-clock-rotate-left"></i> History
                </a>
            </li>
            <li>
                <a href="settings.php" class="nav-link">
                    <i class="fa-solid fa-gear"></i> Settings
                </a>
            </li>
        </ul>

        <hr>

        <div class="logout">
            <a href="logout.php" class="nav-link">
                <i class="fa-solid fa-arrow-right-from-bracket"></i> Log out
            </a>
        </div>
    </div>

    <div id="main-content" class="container mt-1">
        <!-- Tabs Navigation -->
        <ul class="nav nav-tabs" id="scheduleTabs">
            <li class="nav-item tabs">
                <a class="nav-link" href="trips.php">Scheduled Trips</a>
            </li>
            <li class="nav-item tabs">
                <a class="nav-link" href="add-visitor.php">Add Visitor</a>
            </li>
            <li class="nav-item tabs">
                <a class="nav-link active" href="trip-info.php">Trip Info</a>
            </li>
        </ul>

        <div class="container mt-4">
            <ul class="nav nav-tabs" id="dataTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="students-tab" data-bs-toggle="tab" data-bs-target="#students" type="button" role="tab">Students</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="supervisors-tab" data-bs-toggle="tab" data-bs-target="#supervisors" type="button" role="tab">Supervisors</button>
                </li>
            </ul>

            <div class="tab-content mt-3" id="dataTabsContent">
                <!-- Students Tab -->
                <div class="tab-pane fade show active" id="students" role="tabpanel">
                    <h4 class="text-center">Students List</h4>
                    <div class="d-flex justify-content-between mb-3">
                        <a href="export_students_pdf.php?id=<?php echo $scheduled_id?>" class="btn btn-danger">
                            <i class="fa fa-file-pdf"></i> Export to PDF
                        </a>
                        
                        <input type="text" id="customSearchBox" class="form-control search-box" placeholder="Search students">
                    </div>
                    <?php if ($student_result->num_rows > 0): ?>
                        
                        <table id="studentTable" class="table table-bordered text-center">
                            <thead class="table-dark">
                                <tr>
                                    <th>Last Name</th>
                                    <th>First Name</th>
                                    <th>Grade Level</th>
                                    <th>Guardian</th>
                                    <th>Contact</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $student_result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= $row['lastname'] ?></td>
                                        <td><?= $row['firstname'] ?></td>
                                        <td><?= $row['grade_level'] ?></td>
                                        <td><?= $row['guardian'] ?></td>
                                        <td><?= $row['contact'] ?></td>
                                        <td class="d-flex justify-content-evenly">
                                            <a href="#" class="btn btn-info btn-sm" onclick="printStudentCertificate('<?= $row['student_id'] ?>')">
                                                <i class='fa-solid fa-print'></i>
                                            </a>
                                            <a href="#" class="btn btn-warning btn-sm edit-btn" data-bs-toggle='modal' 
                                            data-bs-target='#editStudentModal'
                                            data-studentid='<?= htmlspecialchars($row['student_id'], ENT_QUOTES, 'UTF-8') ?>'
                                            data-firstname='<?= htmlspecialchars($row['firstname'], ENT_QUOTES, 'UTF-8') ?>'
                                            data-lastname='<?= htmlspecialchars($row['lastname'], ENT_QUOTES, 'UTF-8') ?>'
                                            data-grade='<?= htmlspecialchars($row['grade_level'], ENT_QUOTES, 'UTF-8') ?>'
                                            data-guardian='<?= htmlspecialchars($row['guardian'], ENT_QUOTES, 'UTF-8') ?>'
                                            data-contact='<?= htmlspecialchars($row['contact']) ?>'
                                            ><i class='fa-solid fa-pen-to-square'></i></a>
                                            <a href="deleteStudent.php?id=<?php echo $row['student_id']; ?>&scheduled_id=<?php echo $scheduled_id; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this student?')"><i class='fa-solid fa-trash'></i></a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="alert alert-warning text-center">No student records found.</div>
                    <?php endif; ?>
                </div>

                <!-- Supervisors Tab -->
                <div class="tab-pane fade" id="supervisors" role="tabpanel">
                    <h4 class="text-center">Supervisors List</h4>
                    <?php if ($supervisor_result->num_rows > 0): ?>
                        <input type="text" id="customSearchBoxSupervisor" class="form-control search-box" placeholder="Search students" style="margin-bottom: 16px;">
                        <table id="supervisorTable" class="table table-bordered text-center">
                            <thead class="table-secondary">
                                <tr>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Position</th>
                                    <th>Contact</th>
                                    <th>Gender</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $supervisor_result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= $row['firstname'] ?></td>
                                        <td><?= $row['lastname'] ?></td>
                                        <td><?= $row['position'] ?></td>
                                        <td><?= $row['contact'] ?></td>
                                        <td><?= $row['gender'] ?></td>
                                        <td class="d-flex justify-content-evenly">
                                            <a href="#" class="btn btn-info btn-sm" onclick="printSupervisorCertificate('<?= $row['supervisor_id'] ?>')">
                                                <i class='fa-solid fa-print'></i>
                                            </a>
                                            <a href="#" class="btn btn-warning btn-sm edit-btn1"
                                            data-bs-toggle='modal' 
                                            data-bs-target='#editSuperVisorModal'
                                            data-supervisorid='<?= htmlspecialchars($row['supervisor_id'], ENT_QUOTES, 'UTF-8') ?>'
                                            data-firstname='<?= htmlspecialchars($row['firstname'], ENT_QUOTES, 'UTF-8') ?>'
                                            data-lastname='<?= htmlspecialchars($row['lastname'], ENT_QUOTES, 'UTF-8') ?>'
                                            data-position='<?= htmlspecialchars($row['position'], ENT_QUOTES, 'UTF-8') ?>'
                                            data-contact='<?= htmlspecialchars($row['contact'], ENT_QUOTES, 'UTF-8') ?>'
                                            data-gender='<?= htmlspecialchars($row['gender']) ?>' 
                                            ><i class='fa-solid fa-pen-to-square'></i></a>
                                            <a href="deleteSupervisor.php?id=<?php echo $row['supervisor_id']; ?>&scheduled_id=<?php echo $scheduled_id; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this supervisor?')"><i class='fa-solid fa-trash'></i></a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="alert alert-warning text-center">No supervisor records found.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editStudentModal" tabindex="-1" aria-labelledby="editStudentModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editStudentModalLabel">Edit Student</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editStudentForm">
                            <input type="hidden" id="editStudentId" name="student_id">
                            <div class="form-group">
                                <label for="editFirstName">First Name</label>
                                <input type="text" class="form-control" id="editFirstName" name="firstname" required>
                            </div>
                            <div class="form-group">
                                <label for="editLastName">Last Name</label>
                                <input type="text" class="form-control" id="editLastName" name="lastname" required>
                            </div>
                            <div class="form-group">
                                <label for="editGradeLevel">Grade Level</label>
                                <select class="form-control" id="editGradeLevel" name="grade_name" required>
                                    <option value="" selected hidden>Select City</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="editGuardian">Guardian</label>
                                <input type="text" class="form-control" id="editGuardian" name="guardian" required>
                            </div>
                            <div class="form-group">
                                <label for="editContact">Contact</label>
                                <input type="tel" class="form-control" id="editContact" name="contact" required>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editSuperVisorModal" tabindex="-1" aria-labelledby="editSuperVisorModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editSuperVisorModalLabel">Edit Supervisor</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editSuperVisorForm">
                            <input type="hidden" id="editSuperVisorId" name="supervisor_id">
                            <div class="form-group">
                                <label for="editFirstName1">First Name</label>
                                <input type="text" class="form-control" id="editFirstName1" name="firstname" required>
                            </div>
                            <div class="form-group">
                                <label for="editLastName1">Last Name</label>
                                <input type="text" class="form-control" id="editLastName1" name="lastname" required>
                            </div>
                            <div class="form-group">
                                <label for="editPosition1">Position</label>
                                <input type="text" class="form-control" id="editPosition1" name="position" required>
                            </div>
                            <div class="form-group">
                                <label for="editContact1">Contact</label>
                                <input type="tel" class="form-control" id="editContact1" name="contact" required>
                            </div>
                            <div class="form-group">
                                <label for="editGender1">Gender</label>
                                <select class="form-control" id="editGender1" name="gender" required>
                                    <option value="" selected hidden>Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    

    <script src="vendor/fontawesome-free/js/all.min.js"></script>
    <script src="vendor/fontawesome-free/js/fontawesome.min.js"></script>
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

    <script>

        $(document).ready(function() {
            // Initialize the DataTable and store it in a variable
            var studentTable = $('#studentTable').DataTable({
                "paging": true, 
                "ordering": true, 
                "info": false, 
                "lengthChange": false,  
                "searching": true, 
                "pageLength": 10,
                "language": {
                    "paginate": {
                        "previous": "<i class='fas fa-chevron-left'></i>",
                        "next": "<i class='fas fa-chevron-right'></i>"
                    }
                },
                dom: 'rt<"bottom"p><"clear">'
            });

            // Initialize the Supervisor Table
            var supervisorTable = $('#supervisorTable').DataTable({
                "paging": true, 
                "ordering": true, 
                "info": false, 
                "lengthChange": false,  
                "searching": true, 
                "pageLength": 10,
                "language": {
                    "paginate": {
                        "previous": "<i class='fas fa-chevron-left'></i>",
                        "next": "<i class='fas fa-chevron-right'></i>"
                    }
                },
                dom: 'rt<"bottom"p><"clear">'
            });

            // Custom search for the Student Table
            $('#customSearchBox').on('keyup', function() {
                studentTable.search(this.value).draw();  // Trigger the DataTable search and redraw for student table
            });

            // Custom search for the Supervisor Table (if needed)
            $('#customSearchBoxSupervisor').on('keyup', function() {
                supervisorTable.search(this.value).draw();  // Trigger the DataTable search and redraw for supervisor table
            });
        });

        $(document).ready(function () {
            function gradeLevel() {
                $.ajax({
                    url: "fetch_grade.php", 
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                        var gradeSelect = $("#editGradeLevel");
                        gradeSelect.empty(); // Clear existing options
                        gradeSelect.append('<option value="">Select Grade Level</option>'); // Default option
                        
                        data.forEach(grade => {
                            gradeSelect.append(`<option value="${grade.grade_name}">${grade.grade_name}</option>`);
                        });
                    },
                    error: function () {
                        console.error("Error fetching grades.");
                    }
                });
            }
            
            $(".edit-btn").click(function () {
                var id = $(this).data("studentid");
                var firstname = $(this).data("firstname");
                var lastname = $(this).data("lastname");
                var grade = $(this).data("grade");
                var guardian = $(this).data("guardian");
                var contact = $(this).data("contact");

                $("#editStudentId").val(id);
                $("#editFirstName").val(firstname);
                $("#editLastName").val(lastname);
                $("#editGradeLevel").val(grade);
                $("#editGuardian").val(guardian);
                $("#editContact").val(contact);

                gradeLevel();
                
                setTimeout(() => {
                    $("#editGradeLevel").val(grade); 
                }, 500);

                $("#editStudentModal").modal("show");
            });

            $("#editStudentForm").submit(function (e) {
                e.preventDefault();

                $.ajax({
                    url: 'update_student.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function (response) {
                        if (response.status === 'success') {
                            alert(response.message);
                            location.reload(); // or refresh just the table list
                        } else {
                            alert("Error: " + response.message);
                        }
                    },
                    error: function () {
                        alert("An error occurred while updating.");
                    }
                });
            });

            $(".edit-btn1").click(function () {
                var id = $(this).data("supervisorid");
                var firstname = $(this).data("firstname");
                var lastname = $(this).data("lastname");
                var position = $(this).data("position");
                var contact = $(this).data("contact");
                var gender = $(this).data("gender");

                $("#editSuperVisorId").val(id);
                $("#editFirstName1").val(firstname);
                $("#editLastName1").val(lastname);
                $("#editPosition1").val(position);
                $("#editContact1").val(contact);
                $("#editGender1").val(gender);


                $("#editSuperVisorModal").modal("show");
            });

            $("#editSuperVisorForm").submit(function (e) {
                e.preventDefault();

                $.ajax({
                    url: 'update_supervisor.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function (response) {
                        if (response.status === 'success') {
                            alert(response.message);
                            location.reload(); // or refresh only the supervisor table
                        } else {
                            alert("Error: " + response.message);
                        }
                    },
                    error: function () {
                        alert("An error occurred while updating.");
                    }
                });
            });
        });

        function getOrdinalSuffix(day) {
            if (day > 3 && day < 21) return 'th'; // covers 11th–13th
            switch (day % 10) {
                case 1:  return 'st';
                case 2:  return 'nd';
                case 3:  return 'rd';
                default: return 'th';
            }
        }

        function formatDateWithSuffix(dateStr) {
            const date = new Date(dateStr);
            const day = date.getDate();
            const suffix = getOrdinalSuffix(day);
            const month = date.toLocaleString('default', { month: 'long' });
            const year = date.getFullYear();
            return `${day}${suffix} of ${month}, ${year}`;
        }
        
        function printStudentCertificate(studentId) {
            fetch('fetchStudentData.php?id=' + studentId)
                .then(response => response.json())
                .then(data => {
                    const fullName = data.firstname + ' ' + data.lastname;
                    const scheduledDate = formatDateWithSuffix(data.date);

                    openPrintWindow(fullName, scheduledDate);
                });
        }

        function printSupervisorCertificate(supervisorid) {
            fetch('fetchSupervisorData.php?id=' + supervisorid)
                .then(response => response.json())
                .then(data => {
                    const fullName = data.firstname + ' ' + data.lastname;
                    const scheduledDate = formatDateWithSuffix(data.date);

                    openPrintWindow(fullName, scheduledDate);
                });
        }

        function openPrintWindow(fullName, scheduledDate) {
            const printWindow = window.open('', '', 'width=1200,height=850');
            printWindow.document.write('<html><head><title>Student Certificate</title>');
            printWindow.document.write('<style>');
            printWindow.document.write('@font-face { font-family: "Nova"; src: url("fonts/Nova/Arial-Nova.ttf") format("truetype"); }');
            printWindow.document.write('@page { size: A4 landscape; margin: 0; }');
            printWindow.document.write('body { margin: 0; padding: 0; display: flex; align-items: center; justify-content: center; height: 100vh; }');
            printWindow.document.write('.certificate { position: relative; width: 100%; height: 100vh; overflow: hidden; }');
            printWindow.document.write('img { width: 100%; height: 100vh; object-fit: cover; }');
            printWindow.document.write('.name { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 70px; font-weight: bold; white-space: nowrap; }');
            printWindow.document.write('.date { position: absolute; top: 69%; left: 55%; transform: translateX(-50%); font-size: 25px; font-family: Nova; font-weight: normal; }');
            printWindow.document.write('</style></head><body>');
            printWindow.document.write('<div class="certificate">');
            printWindow.document.write('<img src="img/cert.png" alt="Certificate Background">');
            printWindow.document.write('<div class="name">' + fullName + '</div>');
            printWindow.document.write('<div class="date">' + scheduledDate + '</div>');
            printWindow.document.write('</div></body></html>');

            printWindow.document.close();
            printWindow.onload = function () {
                printWindow.print();
                printWindow.close();
            };
        }
    </script>
</body>

</html>
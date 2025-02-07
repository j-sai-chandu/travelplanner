<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = ""; // Default password for XAMPP
$dbname = "project";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function for password validation
function validatePassword($password) {
    return preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*]).{8,}$/", $password);
}

// Handle Add/Edit/Delete Requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'add') {
        $first_name = $_POST['first_name'] ?? '';
        $last_name = $_POST['last_name'] ?? '';
        $email = $_POST['email'] ?? '';
        $country_code = $_POST['country_code'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $password = $_POST['password'] ?? '';
        $user_type = 2; // Explicitly set user_type for admin
    
        // Validate password
        if (!validatePassword($password)) {
            echo json_encode(["success" => false, "message" => "Password must contain at least one uppercase letter, one lowercase letter, one number, one special character, and be at least 8 characters long."]);
            exit;
        }
    
        // Hash the password
    
        // Validate phone number
        if (!preg_match("/^\d{10}$/", $phone)) {
            echo json_encode(["success" => false, "message" => "Phone number must be exactly 10 digits without any characters"]);
            exit;
        }
    
        // Check if phone number already exists
        $sql = "SELECT id1 FROM users1 WHERE phone = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $phone);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            echo json_encode(["success" => false, "message" => "This phone number is already registered."]);
            exit;
        }
    
        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match("/@gmail\.com$/", $email)) {
            echo json_encode(["success" => false, "message" => "Email must be in the format @gmail.com."]);
            exit;
        }
    
        // Check if email already exists
        $sql = "SELECT id1 FROM users1 WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            echo json_encode(["success" => false, "message" => "This email is already registered."]);
            exit;
        }
    
        // Insert into the database
        $sql = "INSERT INTO users1 (first_name, last_name, email, country_code, phone, password, user_type) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssi", $first_name, $last_name, $email, $country_code, $phone, $password, $user_type);
    
        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Admin added successfully"]);
        } else {
            echo json_encode(["success" => false, "message" => "Error adding admin: " . $conn->error]);
        }
    
        $stmt->close();
        exit;
    }
    

    if ($action === 'edit') {
        $id = $_POST['id'] ?? '';
        $first_name = $_POST['first_name'] ?? '';
        $last_name = $_POST['last_name'] ?? '';
        $email = $_POST['email'] ?? '';
        $country_code = $_POST['country_code'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $password = $_POST['password'] ?? '';

        // Validate password
        if (!validatePassword($password)) {
            echo json_encode(["success" => false, "message" => "Password must contain at least one uppercase letter, one lowercase letter, one number, one special character, and be at least 8 characters long."]);
            exit;
        }

        // Validate phone number (only digits, 10 digits)
        if (!preg_match("/^\d{10}$/", $phone)) {
            echo json_encode(["success" => false, "message" => "Phone number must be exactly 10 digits without any characters"]);
            exit;
        }

        // Check if phone number already exists (excluding the current record)
        $sql = "SELECT id1 FROM users1 WHERE phone = ? AND id1 != ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $phone, $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            echo json_encode(["success" => false, "message" => "This phone number is already registered."]);
            exit;
        }

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match("/@gmail\.com$/", $email)) {
            echo json_encode(["success" => false, "message" => "Email must be in the format @gmail.com."]);
            exit;
        }

        // Check if email already exists (excluding the current record)
        $sql = "SELECT id1 FROM users1 WHERE email = ? AND id1 != ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $email, $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            echo json_encode(["success" => false, "message" => "This email is already registered."]);
            exit;
        }

        // Update the database
        $sql = "UPDATE users1 SET first_name = ?, last_name = ?, email = ?, country_code = ?, phone = ?, password = ? WHERE id1 = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssi", $first_name, $last_name, $email, $country_code, $phone, $password, $id);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Admin updated successfully"]);
        } else {
            echo json_encode(["success" => false, "message" => "Error updating admin: " . $conn->error]);
        }

        $stmt->close();
        exit;
    }

    if ($action === 'delete') {
        $id = $_POST['id'] ?? '';

        $sql = "DELETE FROM users1 WHERE id1 = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Admin deleted successfully"]);
        } else {
            echo json_encode(["success" => false, "message" => "Error deleting admin: " . $conn->error]);
        }

        $stmt->close();
        exit;
    }
}

// Fetch all admins (user_type = 2) from the users1 table
$sql = "SELECT id1, first_name, last_name, country_code, phone, email, password FROM users1 WHERE user_type = 2";
$result = $conn->query($sql);

// Check if there are any admins and store them in an array
$admins = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $admins[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile</title>
    <link rel="stylesheet" href="adminRegistrations.css">
    <style>
        table {
    width: 70%; /* Reduce the width of the table */
    margin: 10px 10px 10px 10px; /* Center the table */
    border-collapse: collapse;
    font-size: 16px; /* Reduce the font size */
    text-align: left;
}
table th, table td {
    border: 1px solid #ddd;
    padding: 8px; /* Reduce padding to make columns narrower */
}
table th {
    background-color: #f2f2f2;
    font-size: 14px; /* Adjust font size for the header */
}

        .action-buttons {
            display: flex;
            gap: 5px;
        }
        .edit-button, .save-button, .delete-button {
            padding: 5px 10px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .edit-button {
            background-color: #4CAF50;
            color: white;
        }
        .save-button {
            background-color: #008CBA;
            color: white;
            display: none; /* Initially hidden */
        }
        .delete-button {
            background-color: #f44336;
            color: white;
        }
        .action-buttons {
    display: flex;
    gap: 10px; /* Adds space between buttons */
}
.add-button {
    padding: 5px 10px;
    border: none;
    cursor: pointer;
    border-radius: 5px;
    background-color: #4CAF50; /* Same as edit button */
    color: white;
    font-weight: bold;
}

.add-button:hover {
    background-color: #45a049; /* Slightly darker shade on hover */
}
.search-add-container {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin: 20px 0;
    margin-left:10px;
}

.search-box input {
    padding: 8px 15px;
    font-size: 16px;
    border: 1px solid #ddd;
    border-radius: 5px;
    width: 70%;
    margin-right: 10px;
}

.add-button {
    padding: 8px 15px;
    border: none;
    cursor: pointer;
    border-radius: 5px;
    background-color: #4CAF50;
    color: white;
    font-weight: bold;
}

.add-button:hover {
    background-color: #45a049;
}
h3{
    margin-left:10px;
}

    </style>
</head>
<body>
<div class="sidebar">
        <h2>Travel Admin Panel</h2>
        <button onclick="window.location.href='adminDashboard.html'">Dashboard</button>
        <button onclick="window.location.href='adminRegistrations.php'">Registered Users</button>
        <button onclick="window.location.href='adminListedCountries.php'">Listed Destinations</button>
        <button onclick="window.location.href='adminProfile.php'">Admins Data</button>
        <button onclick="window.location.href='adminReview.php'">View Feedbacks</button>
        <button onclick="window.location.href='index.html'">Logout</button>
    </div>
    <div class="content">
        <h3>All Admins Data</h3>
        <div class="search-add-container">
    <div class="search-box">
        <input type="text" id="search-input" placeholder="Search Admins" onkeyup="filterAdmins()">
    </div>
</div>

        <!-- Admin Table -->
        <table id="adminTable">
            <thead>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Country Code</th>
                    <th>Phone</th>
                    <th>Password</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($admins as $admin): ?>
                    <tr data-id="<?php echo $admin['id1']; ?>">
                        <td class="editable"><?php echo htmlspecialchars($admin['first_name']); ?></td>
                        <td class="editable"><?php echo htmlspecialchars($admin['last_name']); ?></td>
                        <td class="editable"><?php echo htmlspecialchars($admin['email']); ?></td>
                        <td class="editable"><?php echo htmlspecialchars($admin['country_code']); ?></td>
                        <td class="editable"><?php echo htmlspecialchars($admin['phone']); ?></td>
                        <td class="editable"><?php echo htmlspecialchars($admin['password']); ?></td>
                        <td>
    <div class="action-buttons">
        <button class="edit-button" onclick="editRow(this)">Edit</button>
        <button class="save-button" onclick="saveRow(this)" style="display: none;">Save</button>
        <button class="delete-button" onclick="deleteRow(this)">Delete</button>
    </div>
</td>

                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td><input type="text" placeholder="First Name"></td>
                    <td><input type="text" placeholder="Last Name"></td>
                    <td><input type="text" placeholder="Email"></td>
                    <td><input type="text" placeholder="country_code"></td>
                    <td><input type="text" placeholder="Phone"></td>
                    <td><input type="text" placeholder="Password"></td>
                    <td><button class="add-button" onclick="addRow(this)">Add</button></td>
                </tr>
            </tbody>
        </table>
    </div>
<script>
    function editRow(button) {
    const row = button.closest('tr');
    const cells = row.querySelectorAll('.editable');
    cells.forEach(cell => {
        const currentValue = cell.textContent.trim();
        cell.innerHTML = `<input type="text" value="${currentValue}">`;
    });
    button.style.display = 'none';
    row.querySelector('.save-button').style.display = 'inline-block';
}

function saveRow(button) {
    const row = button.closest('tr');
    const id = row.getAttribute('data-id');
    const cells = row.querySelectorAll('.editable');
    const data = Array.from(cells).map(cell => cell.querySelector('input').value.trim());

    fetch('', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `action=edit&id=${id}&first_name=${encodeURIComponent(data[0])}&last_name=${encodeURIComponent(data[1])}&email=${encodeURIComponent(data[2])}&country_code=${encodeURIComponent(data[3])}&phone=${encodeURIComponent(data[4])}&password=${encodeURIComponent(data[5])}`
    }).then(response => response.json()).then(result => {
        if (result.success) {
            cells.forEach((cell, index) => {
                cell.textContent = data[index];
            });
            button.style.display = 'none';
            row.querySelector('.edit-button').style.display = 'inline-block';
            alert(result.message);
        } else {
            alert(result.message);
        }
    });
}

function deleteRow(button) {
    const row = button.closest('tr');
    const id = row.getAttribute('data-id');

    fetch('', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `action=delete&id=${id}`
    }).then(response => response.json()).then(result => {
        if (result.success) {
            row.remove();
            alert(result.message);
        } else {
            alert(result.message);
        }
    });
}

function addRow(button) {
    const row = button.closest('tr');
    const inputs = row.querySelectorAll('input');
    const data = Array.from(inputs).map(input => input.value.trim());

    fetch('', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `action=add&first_name=${encodeURIComponent(data[0])}&last_name=${encodeURIComponent(data[1])}&email=${encodeURIComponent(data[2])}&country_code=${encodeURIComponent(data[3])}&phone=${encodeURIComponent(data[4])}&password=${encodeURIComponent(data[5])}`
}).then(response => response.json()).then(result => {6
        if (result.success) {
            alert(result.message);
            location.reload();
        } else {
            alert(result.message);
        }
    });
}
function filterAdmins() {
    const input = document.getElementById('search-input');
    const filter = input.value.toLowerCase();
    const table = document.getElementById('adminTable');
    const rows = table.getElementsByTagName('tr');

    // Loop through all rows and hide those that don't match the search input
    for (let i = 1; i < rows.length; i++) {
        let cells = rows[i].getElementsByTagName('td');
        let match = false;
        
        // Check if any cell matches the search filter
        for (let j = 0; j < cells.length - 1; j++) { // Exclude the last cell (Actions)
            if (cells[j].textContent.toLowerCase().indexOf(filter) > -1) {
                match = true;
                break;
            }
        }
        
        // Display row if it matches, else hide it
        if (match) {
            rows[i].style.display = '';
        } else {
            rows[i].style.display = 'none';
        }
    }
}

</script>
</body>
</html>

<?php
// Database connection parameters
$host = "localhost";
$user = "root";
$password = "";
$database = "project";

// Create connection
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id = $_GET['id']; // The ID of the destination to edit

    // Retrieve destination data from the database
    $query = "SELECT * FROM destinations1 WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $destination = $result->fetch_assoc();
} else {
    // Redirect or handle the case when there's no ID passed
    echo "No destination selected for editing.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Destination</title>
    <link rel="stylesheet" href="adminAddCountry.css">
</head>
<body>
<div class="form-container">
    <div class="go-back">
        <img src="photo/go-back-image.png" alt="Go Back" onclick="goBack()">
    </div>

    <h2>Edit Destination</h2>
    <form id="destinationForm" action="updateDestination.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $destination['id']; ?>">
        <input type="text" name="place_name" value="<?php echo $destination['place_name']; ?>" placeholder="Give Name of the Place" required>

        <!-- Couple Section -->
        <h3>Couple</h3>
        <input type="text" name="couple_places" value="<?php echo $destination['couple_places']; ?>" placeholder="Add nearby Places for Couple with comma separation">
        <input type="text" name="couple_best_time" value="<?php echo $destination['couple_best_time']; ?>" placeholder="Add best time for Couple with comma separation">

        <label>Worst months to visit for Couple:</label>
        <div class="months-container">
            <?php
            $months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            foreach ($months as $month) {
                $checked = in_array($month, explode(",", $destination['couple_worst_months'])) ? 'checked' : '';
                echo "<label><input type='checkbox' name='couple_worst_months[]' value='$month' $checked> $month</label>";
            }
            ?>
        </div>
        <input type="text" name="couple_worst_months_reason" value="<?php echo $destination['couple_worst_months_reason']; ?>" placeholder="Reason for worst months for Couple">
        <input type="text" name="couple_min_cost" value="<?php echo $destination['couple_min_cost']; ?>" placeholder="Minimum Cost to visit all these places">

        <!-- Family Section -->
        <h3>Family</h3>
        <input type="text" name="family_places" value="<?php echo $destination['family_places']; ?>" placeholder="Add nearby Places for Family with comma separation">
        <input type="text" name="family_best_time" value="<?php echo $destination['family_best_time']; ?>" placeholder="Add best time for Family with comma separation">

        <label>Worst months to visit for Family:</label>
        <div class="months-container">
            <?php
            foreach ($months as $month) {
                $checked = in_array($month, explode(",", $destination['family_worst_months'])) ? 'checked' : '';
                echo "<label><input type='checkbox' name='family_worst_months[]' value='$month' $checked> $month</label>";
            }
            ?>
        </div>
        <input type="text" name="family_worst_months_reason" value="<?php echo $destination['family_worst_months_reason']; ?>" placeholder="Reason for worst months for Family">
        <input type="text" name="family_min_cost" value="<?php echo $destination['family_min_cost']; ?>" placeholder="Minimum Cost to visit all these places">

        <!-- Friends Section -->
        <h3>Friends</h3>
        <input type="text" name="friends_places" value="<?php echo $destination['friends_places']; ?>" placeholder="Add nearby Places for Friends with comma separation">
        <input type="text" name="friends_best_time" value="<?php echo $destination['friends_best_time']; ?>" placeholder="Add best time for Friends with comma separation">

        <label>Worst months to visit for Friends:</label>
        <div class="months-container">
            <?php
            foreach ($months as $month) {
                $checked = in_array($month, explode(",", $destination['friends_worst_months'])) ? 'checked' : '';
                echo "<label><input type='checkbox' name='friends_worst_months[]' value='$month' $checked> $month</label>";
            }
            ?>
        </div>
        <input type="text" name="friends_worst_months_reason" value="<?php echo $destination['friends_worst_months_reason']; ?>" placeholder="Reason for worst months for Friends">
        <input type="text" name="friends_min_cost" value="<?php echo $destination['friends_min_cost']; ?>" placeholder="Minimum Cost to visit all these places">

        <!-- Solo Section -->
        <h3>Solo</h3>
        <input type="text" name="solo_places" value="<?php echo $destination['solo_places']; ?>" placeholder="Add nearby Places for Solo with comma separation">
        <input type="text" name="solo_best_time" value="<?php echo $destination['solo_best_time']; ?>" placeholder="Add best time for Solo with comma separation">

        <label>Worst months to visit for Solo:</label>
        <div class="months-container">
            <?php
            foreach ($months as $month) {
                $checked = in_array($month, explode(",", $destination['solo_worst_months'])) ? 'checked' : '';
                echo "<label><input type='checkbox' name='solo_worst_months[]' value='$month' $checked> $month</label>";
            }
            ?>
        </div>
        <input type="text" name="solo_worst_months_reason" value="<?php echo $destination['solo_worst_months_reason']; ?>" placeholder="Reason for worst months for Solo">
        <input type="text" name="solo_min_cost" value="<?php echo $destination['solo_min_cost']; ?>" placeholder="Minimum Cost to visit all these places">

        <button type="submit">Save Changes</button>
    </form>
</div>

<script>
    function goBack() {
        window.history.back();
    }
</script>

</body>
</html>

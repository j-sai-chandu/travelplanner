<?php
// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'project');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch 'id1' from the URL
$id1 = isset($_GET['id1']) ? $_GET['id1'] : null;

if ($id1) {
    // Fetch the user data based on 'id1'
    $sql = "SELECT * FROM users1 WHERE id1 = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id1); // Bind the 'id1' as an integer
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the user is found
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        echo "User not found.";
        exit;
    }
    $stmt->close();

    // Update the user information when Save is clicked
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get the updated values from the form
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $country_code = $_POST['country_code'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Validate the phone number
        if (!preg_match('/^\d{10}$/', $phone)) {
            echo "<script>alert('Phone number must be 10 digits without any characters.');</script>";
        } else {
            $phone_check_sql = "SELECT * FROM users1 WHERE phone = ? AND id1 != ?";
            $stmt = $conn->prepare($phone_check_sql);
            $stmt->bind_param("si", $phone, $id1);
            $stmt->execute();
            $phone_check_result = $stmt->get_result();

            if ($phone_check_result->num_rows !== 0) {
                echo "<script>alert('Phone number already in use. Please use another number.');</script>";
                $stmt->close();
            } else {
                $stmt->close();

                // Validate the email address
                if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('/@gmail\.com$/', $email)) {
                    echo "<script>alert('Email must be a valid Gmail address (e.g., example@gmail.com).');</script>";
                } else {
                    $email_check_sql = "SELECT * FROM users1 WHERE email = ? AND id1 != ?";
                    $stmt = $conn->prepare($email_check_sql);
                    $stmt->bind_param("si", $email, $id1);
                    $stmt->execute();
                    $email_check_result = $stmt->get_result();

                    if ($email_check_result->num_rows > 0) {
                        echo "<script>alert('Email already exists. Please use a different Gmail address.');</script>";
                        $stmt->close();
                    } else {
                        $stmt->close();

                        // Validate the password
                        if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
                            echo "<script>alert('Password must be at least 8 characters long and include an uppercase letter, a lowercase letter, a number, and a special character.');</script>";
                        } else {
                            // Update the user in the database
                            $update_sql = "UPDATE users1 SET first_name=?, last_name=?, country_code=?, phone=?, email=?, password=? WHERE id1=?";
                            $stmt = $conn->prepare($update_sql);
                            $stmt->bind_param("ssssssi", $first_name, $last_name, $country_code, $phone, $email, $password, $id1);

                            if ($stmt->execute()) {
                                echo "<script>alert('Profile updated successfully!');</script>";
                                // Redirect to profile page with id1
                                echo "<script>window.location.href = 'profile1.php?id1=$id1';</script>";
                            } else {
                                echo "Error updating record: " . $conn->error;
                            }
                            $stmt->close();
                        }
                    }
                }
            }
        }
    }
} else {
    echo "No user ID provided.";
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <style>
       body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(to right, #d9e4f5, #f1e3e6);
            font-family: Arial, sans-serif;
        }
        .form-box {
            background-color: #ffffff;
            border-radius: 15px;
            padding: 30px;
            width: 350px;
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.1);
            position: relative;
            text-align: center;
        }
        .icons {
            position: absolute;
            top: 15px;
            left: 15px;
        }
        .icons img {
            width: 25px;
            height: 25px;
            margin: 5px;
            cursor: pointer;
        }
        h2 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #333;
            font-weight: bold;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            background-color: #eeeeee;
            color: #333;
            text-align: center;
        }
        select {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            background-color: #eeeeee;
            color: #333;
            text-align: center;
        }
        button {
            padding: 10px 20px;
            background-color: #ff4d4d;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            font-weight: bold;
        }
        button:hover {
            background-color: #e04444;
        }
        .back-button {
            position: absolute;
            top: 20px; /* Positioned at the top-left of the container */
            left: 20px;
            width: 50px; /* Increased size */
            height: 50px; /* Increased size */
            cursor: pointer;
            position: fixed;
        }
        .back-button img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        footer {
            background-color: #000;
            color: #fff;
            text-align: center;
            padding: 3px;
            width: 100%;
            position: fixed;
            bottom: 0;
            left: 0;
            box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.2);
        }

        footer p {
            font-size: 14px;
            margin: 0;
        }

        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 5px;
            background-color: #1e1e1e;
            color: white;
            width: 100%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
        }

        .back-button {
            margin-top:-10px;
            position: relative;
            cursor: pointer;
        }

        .back-button img {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .title-container {
            display: flex;
            align-items: center;
            position: relative;
            cursor: pointer;
            margin-right: 700px;
        }

        .main-icon {
            width: 30px;
            height: 30px;
            margin-right: 10px;
        }

        .wiseglobe-text {
            font-size: 18px;
            font-weight: bold;
            display: flex;
            align-items: center;
        }

        .wise {
            color: orangered;
            font-size: 20px;
            font-weight: bold;
        }

        .globe {
            color: white;
            font-size: 20px;
            font-weight: bold;
        }
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(to right, #d9e4f5, #f1e3e6);
            font-family: Arial, sans-serif;
        }
        .form-box {
            background-color: #ffffff;
            border-radius: 15px;
            padding: 30px;
            width: 350px;
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.1);
            position: relative;
            text-align: center;
        }
        h2 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #333;
            font-weight: bold;
        }
        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            background-color: #eeeeee;
            color: #333;
            text-align: center;
        }
        button {
            padding: 10px 20px;
            background-color: #ff4d4d;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            font-weight: bold;
        }
        button:hover {
            background-color: #e04444;
        }
    </style>
</head>
<body>
<header>
    <div class="back-button" onclick="goBack()">
        <img src="photo/back.jpeg" alt="Back">
    </div>
    <div class="title-container">
        <img src="photo/main-icon.png" alt="Main Icon" class="main-icon">
        <div class="wiseglobe-text">
            <span class="wise">Travel</span><span class="globe">Planner</span>
        </div>
    </div>
</header>
<div class="form-box">
    <h2>Edit Profile</h2>
    <form action="edit_profile1.php?id1=<?php echo $id1; ?>" method="POST">
        <div class="form-group">
            <label>First Name</label>
            <input type="text" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
        </div>
        <div class="form-group">
            <label>Last Name</label>
            <input type="text" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
        </div>
        <div class="form-group">
            <label>Country Code</label>
            <select name="country_code" required>
                <option value="+91" <?php echo $user['country_code'] == '+91' ? 'selected' : ''; ?>>India (+91)</option>
                <option value="+93" <?php echo $user['country_code'] == '+93' ? 'selected' : ''; ?>>Afghanistan (+93)</option>
    <option value="+355" <?php echo $user['country_code'] == '+355' ? 'selected' : ''; ?>>Albania (+355)</option>
    <option value="+213" <?php echo $user['country_code'] == '+213' ? 'selected' : ''; ?>>Algeria (+213)</option>
    <option value="+376" <?php echo $user['country_code'] == '+376' ? 'selected' : ''; ?>>Andorra (+376)</option>
    <option value="+244" <?php echo $user['country_code'] == '+244' ? 'selected' : ''; ?>>Angola (+244)</option>
    <option value="+1268" <?php echo $user['country_code'] == '+1268' ? 'selected' : ''; ?>>Antigua and Barbuda (+1268)</option>
    <option value="+54" <?php echo $user['country_code'] == '+54' ? 'selected' : ''; ?>>Argentina (+54)</option>
    <option value="+374" <?php echo $user['country_code'] == '+374' ? 'selected' : ''; ?>>Armenia (+374)</option>
    <option value="+61" <?php echo $user['country_code'] == '+61' ? 'selected' : ''; ?>>Australia (+61)</option>
    <option value="+43" <?php echo $user['country_code'] == '+43' ? 'selected' : ''; ?>>Austria (+43)</option>
    <option value="+994" <?php echo $user['country_code'] == '+994' ? 'selected' : ''; ?>>Azerbaijan (+994)</option>
    <option value="+1-242" <?php echo $user['country_code'] == '+1-242' ? 'selected' : ''; ?>>Bahamas (+1-242)</option>
    <option value="+973" <?php echo $user['country_code'] == '+973' ? 'selected' : ''; ?>>Bahrain (+973)</option>
    <option value="+880" <?php echo $user['country_code'] == '+880' ? 'selected' : ''; ?>>Bangladesh (+880)</option>
    <option value="+1-246" <?php echo $user['country_code'] == '+1-246' ? 'selected' : ''; ?>>Barbados (+1-246)</option>
    <option value="+375" <?php echo $user['country_code'] == '+375' ? 'selected' : ''; ?>>Belarus (+375)</option>
    <option value="+32" <?php echo $user['country_code'] == '+32' ? 'selected' : ''; ?>>Belgium (+32)</option>
    <option value="+501" <?php echo $user['country_code'] == '+501' ? 'selected' : ''; ?>>Belize (+501)</option>
    <option value="+229" <?php echo $user['country_code'] == '+229' ? 'selected' : ''; ?>>Benin (+229)</option>
    <option value="+975" <?php echo $user['country_code'] == '+975' ? 'selected' : ''; ?>>Bhutan (+975)</option>
    <option value="+591" <?php echo $user['country_code'] == '+591' ? 'selected' : ''; ?>>Bolivia (+591)</option>
    <option value="+387" <?php echo $user['country_code'] == '+387' ? 'selected' : ''; ?>>Bosnia and Herzegovina (+387)</option>
    <option value="+267" <?php echo $user['country_code'] == '+267' ? 'selected' : ''; ?>>Botswana (+267)</option>
    <option value="+55" <?php echo $user['country_code'] == '+55' ? 'selected' : ''; ?>>Brazil (+55)</option>
    <option value="+673" <?php echo $user['country_code'] == '+673' ? 'selected' : ''; ?>>Brunei (+673)</option>
    <option value="+359" <?php echo $user['country_code'] == '+359' ? 'selected' : ''; ?>>Bulgaria (+359)</option>
    <option value="+226" <?php echo $user['country_code'] == '+226' ? 'selected' : ''; ?>>Burkina Faso (+226)</option>
    <option value="+257" <?php echo $user['country_code'] == '+257' ? 'selected' : ''; ?>>Burundi (+257)</option>
    <option value="+238" <?php echo $user['country_code'] == '+238' ? 'selected' : ''; ?>>Cabo Verde (+238)</option>
    <option value="+855" <?php echo $user['country_code'] == '+855' ? 'selected' : ''; ?>>Cambodia (+855)</option>
    <option value="+237" <?php echo $user['country_code'] == '+237' ? 'selected' : ''; ?>>Cameroon (+237)</option>
    <option value="+1" <?php echo $user['country_code'] == '+1' ? 'selected' : ''; ?>>Canada (+1)</option>
    <option value="+236" <?php echo $user['country_code'] == '+236' ? 'selected' : ''; ?>>Central African Republic (+236)</option>
    <option value="+235" <?php echo $user['country_code'] == '+235' ? 'selected' : ''; ?>>Chad (+235)</option>
    <option value="+56" <?php echo $user['country_code'] == '+56' ? 'selected' : ''; ?>>Chile (+56)</option>
    <option value="+86" <?php echo $user['country_code'] == '+86' ? 'selected' : ''; ?>>China (+86)</option>
    <option value="+57" <?php echo $user['country_code'] == '+57' ? 'selected' : ''; ?>>Colombia (+57)</option>
    <option value="+269" <?php echo $user['country_code'] == '+269' ? 'selected' : ''; ?>>Comoros (+269)</option>
    <option value="+242" <?php echo $user['country_code'] == '+242' ? 'selected' : ''; ?>>Congo (Congo-Brazzaville) (+242)</option>
    <option value="+506" <?php echo $user['country_code'] == '+506' ? 'selected' : ''; ?>>Costa Rica (+506)</option>
    <option value="+385" <?php echo $user['country_code'] == '+385' ? 'selected' : ''; ?>>Croatia (+385)</option>
    <option value="+53" <?php echo $user['country_code'] == '+53' ? 'selected' : ''; ?>>Cuba (+53)</option>
    <option value="+357" <?php echo $user['country_code'] == '+357' ? 'selected' : ''; ?>>Cyprus (+357)</option>
    <option value="+420" <?php echo $user['country_code'] == '+420' ? 'selected' : ''; ?>>Czech Republic (+420)</option>
    <option value="+243" <?php echo $user['country_code'] == '+243' ? 'selected' : ''; ?>>Democratic Republic of the Congo (+243)</option>
    <option value="+45" <?php echo $user['country_code'] == '+45' ? 'selected' : ''; ?>>Denmark (+45)</option>
    <option value="+253" <?php echo $user['country_code'] == '+253' ? 'selected' : ''; ?>>Djibouti (+253)</option>
    <option value="+1-767" <?php echo $user['country_code'] == '+1-767' ? 'selected' : ''; ?>>Dominica (+1-767)</option>
    <option value="+1-809" <?php echo $user['country_code'] == '+1-809' ? 'selected' : ''; ?>>Dominican Republic (+1-809)</option>
    <option value="+670" <?php echo $user['country_code'] == '+670' ? 'selected' : ''; ?>>East Timor (+670)</option>
    <option value="+593" <?php echo $user['country_code'] == '+593' ? 'selected' : ''; ?>>Ecuador (+593)</option>
    <option value="+20" <?php echo $user['country_code'] == '+20' ? 'selected' : ''; ?>>Egypt (+20)</option>
    <option value="+503" <?php echo $user['country_code'] == '+503' ? 'selected' : ''; ?>>El Salvador (+503)</option>
    <option value="+240" <?php echo $user['country_code'] == '+240' ? 'selected' : ''; ?>>Equatorial Guinea (+240)</option>
    <option value="+291" <?php echo $user['country_code'] == '+291' ? 'selected' : ''; ?>>Eritrea (+291)</option>
    <option value="+372" <?php echo $user['country_code'] == '+372' ? 'selected' : ''; ?>>Estonia (+372)</option>
    <option value="+268" <?php echo $user['country_code'] == '+268' ? 'selected' : ''; ?>>Eswatini (+268)</option>
    <option value="+251" <?php echo $user['country_code'] == '+251' ? 'selected' : ''; ?>>Ethiopia (+251)</option>
    <option value="+679" <?php echo $user['country_code'] == '+679' ? 'selected' : ''; ?>>Fiji (+679)</option>
    <option value="+358" <?php echo $user['country_code'] == '+358' ? 'selected' : ''; ?>>Finland (+358)</option>
    <option value="+33" <?php echo $user['country_code'] == '+33' ? 'selected' : ''; ?>>France (+33)</option>
    <option value="+241" <?php echo $user['country_code'] == '+241' ? 'selected' : ''; ?>>Gabon (+241)</option>
    <option value="+220" <?php echo $user['country_code'] == '+220' ? 'selected' : ''; ?>>Gambia (+220)</option>
    <option value="+995" <?php echo $user['country_code'] == '+995' ? 'selected' : ''; ?>>Georgia (+995)</option>
    <option value="+49" <?php echo $user['country_code'] == '+49' ? 'selected' : ''; ?>>Germany (+49)</option>
    <option value="+233" <?php echo $user['country_code'] == '+233' ? 'selected' : ''; ?>>Ghana (+233)</option>
    <option value="+30" <?php echo $user['country_code'] == '+30' ? 'selected' : ''; ?>>Greece (+30)</option>
    <option value="+1-473" <?php echo $user['country_code'] == '+1-473' ? 'selected' : ''; ?>>Grenada (+1-473)</option>
    <option value="+502" <?php echo $user['country_code'] == '+502' ? 'selected' : ''; ?>>Guatemala (+502)</option>
    <option value="+224" <?php echo $user['country_code'] == '+224' ? 'selected' : ''; ?>>Guinea (+224)</option>
    <option value="+245" <?php echo $user['country_code'] == '+245' ? 'selected' : ''; ?>>Guinea-Bissau (+245)</option>
    <option value="+592" <?php echo $user['country_code'] == '+592' ? 'selected' : ''; ?>>Guyana (+592)</option>
    <option value="+509" <?php echo $user['country_code'] == '+509' ? 'selected' : ''; ?>>Haiti (+509)</option>
    <option value="+504" <?php echo $user['country_code'] == '+504' ? 'selected' : ''; ?>>Honduras (+504)</option>
    <option value="+36" <?php echo $user['country_code'] == '+36' ? 'selected' : ''; ?>>Hungary (+36)</option>
    <option value="+354" <?php echo $user['country_code'] == '+354' ? 'selected' : ''; ?>>Iceland (+354)</option>
    <option value="+62" <?php echo $user['country_code'] == '+62' ? 'selected' : ''; ?>>Indonesia (+62)</option>
    <option value="+98" <?php echo $user['country_code'] == '+98' ? 'selected' : ''; ?>>Iran (+98)</option>
    <option value="+964" <?php echo $user['country_code'] == '+964' ? 'selected' : ''; ?>>Iraq (+964)</option>
    <option value="+353" <?php echo $user['country_code'] == '+353' ? 'selected' : ''; ?>>Ireland (+353)</option>
    <option value="+972" <?php echo $user['country_code'] == '+972' ? 'selected' : ''; ?>>Israel (+972)</option>
    <option value="+39" <?php echo $user['country_code'] == '+39' ? 'selected' : ''; ?>>Italy (+39)</option>
    <option value="+225" <?php echo $user['country_code'] == '+225' ? 'selected' : ''; ?>>Ivory Coast (+225)</option>
    <option value="+1-876" <?php echo $user['country_code'] == '+1-876' ? 'selected' : ''; ?>>Jamaica (+1-876)</option>
    <option value="+81" <?php echo $user['country_code'] == '+81' ? 'selected' : ''; ?>>Japan (+81)</option>
    <option value="+962" <?php echo $user['country_code'] == '+962' ? 'selected' : ''; ?>>Jordan (+962)</option>
    <option value="+7" <?php echo $user['country_code'] == '+7' ? 'selected' : ''; ?>>Kazakhstan (+7)</option>
    <option value="+254" <?php echo $user['country_code'] == '+254' ? 'selected' : ''; ?>>Kenya (+254)</option>
    <option value="+686" <?php echo $user['country_code'] == '+686' ? 'selected' : ''; ?>>Kiribati (+686)</option>
    <option value="+850" <?php echo $user['country_code'] == '+850' ? 'selected' : ''; ?>>Korea, North (+850)</option>
    <option value="+82" <?php echo $user['country_code'] == '+82' ? 'selected' : ''; ?>>Korea, South (+82)</option>
    <option value="+965" <?php echo $user['country_code'] == '+965' ? 'selected' : ''; ?>>Kuwait (+965)</option>
    <option value="+996" <?php echo $user['country_code'] == '+996' ? 'selected' : ''; ?>>Kyrgyzstan (+996)</option>
    <option value="+856" <?php echo $user['country_code'] == '+856' ? 'selected' : ''; ?>>Laos (+856)</option>
    <option value="+371" <?php echo $user['country_code'] == '+371' ? 'selected' : ''; ?>>Latvia (+371)</option>
    <option value="+961" <?php echo $user['country_code'] == '+961' ? 'selected' : ''; ?>>Lebanon (+961)</option>
    <option value="+266" <?php echo $user['country_code'] == '+266' ? 'selected' : ''; ?>>Lesotho (+266)</option>
    <option value="+231" <?php echo $user['country_code'] == '+231' ? 'selected' : ''; ?>>Liberia (+231)</option>
    <option value="+218" <?php echo $user['country_code'] == '+218' ? 'selected' : ''; ?>>Libya (+218)</option>
    <option value="+423" <?php echo $user['country_code'] == '+423' ? 'selected' : ''; ?>>Liechtenstein (+423)</option>
    <option value="+370" <?php echo $user['country_code'] == '+370' ? 'selected' : ''; ?>>Lithuania (+370)</option>
    <option value="+352" <?php echo $user['country_code'] == '+352' ? 'selected' : ''; ?>>Luxembourg (+352)</option>
    <option value="+853" <?php echo $user['country_code'] == '+853' ? 'selected' : ''; ?>>Macau (+853)</option>
    <option value="+389" <?php echo $user['country_code'] == '+389' ? 'selected' : ''; ?>>North Macedonia (+389)</option>
    <option value="+261" <?php echo $user['country_code'] == '+261' ? 'selected' : ''; ?>>Madagascar (+261)</option>
    <option value="+265" <?php echo $user['country_code'] == '+265' ? 'selected' : ''; ?>>Malawi (+265)</option>
    <option value="+60" <?php echo $user['country_code'] == '+60' ? 'selected' : ''; ?>>Malaysia (+60)</option>
    <option value="+960" <?php echo $user['country_code'] == '+960' ? 'selected' : ''; ?>>Maldives (+960)</option>
    <option value="+223" <?php echo $user['country_code'] == '+223' ? 'selected' : ''; ?>>Mali (+223)</option>
    <option value="+356" <?php echo $user['country_code'] == '+356' ? 'selected' : ''; ?>>Malta (+356)</option>
    <option value="+692" <?php echo $user['country_code'] == '+692' ? 'selected' : ''; ?>>Marshall Islands (+692)</option>
    <option value="+596" <?php echo $user['country_code'] == '+596' ? 'selected' : ''; ?>>Martinique (+596)</option>
    <option value="+222" <?php echo $user['country_code'] == '+222' ? 'selected' : ''; ?>>Mauritania (+222)</option>
    <option value="+230" <?php echo $user['country_code'] == '+230' ? 'selected' : ''; ?>>Mauritius (+230)</option>
    <option value="+262" <?php echo $user['country_code'] == '+262' ? 'selected' : ''; ?>>Mayotte (+262)</option>
    <option value="+52" <?php echo $user['country_code'] == '+52' ? 'selected' : ''; ?>>Mexico (+52)</option>
    <option value="+691" <?php echo $user['country_code'] == '+691' ? 'selected' : ''; ?>>Micronesia (+691)</option>
    <option value="+373" <?php echo $user['country_code'] == '+373' ? 'selected' : ''; ?>>Moldova (+373)</option>
    <option value="+377" <?php echo $user['country_code'] == '+377' ? 'selected' : ''; ?>>Monaco (+377)</option>
    <option value="+976" <?php echo $user['country_code'] == '+976' ? 'selected' : ''; ?>>Mongolia (+976)</option>
    <option value="+382" <?php echo $user['country_code'] == '+382' ? 'selected' : ''; ?>>Montenegro (+382)</option>
    <option value="+1-664" <?php echo $user['country_code'] == '+1-664' ? 'selected' : ''; ?>>Montserrat (+1-664)</option>
    <option value="+212" <?php echo $user['country_code'] == '+212' ? 'selected' : ''; ?>>Morocco (+212)</option>
    <option value="+258" <?php echo $user['country_code'] == '+258' ? 'selected' : ''; ?>>Mozambique (+258)</option>
    <option value="+95" <?php echo $user['country_code'] == '+95' ? 'selected' : ''; ?>>Myanmar (+95)</option>
    <option value="+264" <?php echo $user['country_code'] == '+264' ? 'selected' : ''; ?>>Namibia (+264)</option>
    <option value="+674" <?php echo $user['country_code'] == '+674' ? 'selected' : ''; ?>>Nauru (+674)</option>
    <option value="+977" <?php echo $user['country_code'] == '+977' ? 'selected' : ''; ?>>Nepal (+977)</option>
    <option value="+31" <?php echo $user['country_code'] == '+31' ? 'selected' : ''; ?>>Netherlands (+31)</option>
    <option value="+599" <?php echo $user['country_code'] == '+599' ? 'selected' : ''; ?>>Netherlands Antilles (+599)</option>
    <option value="+687" <?php echo $user['country_code'] == '+687' ? 'selected' : ''; ?>>New Caledonia (+687)</option>
    <option value="+64" <?php echo $user['country_code'] == '+64' ? 'selected' : ''; ?>>New Zealand (+64)</option>
    <option value="+505" <?php echo $user['country_code'] == '+505' ? 'selected' : ''; ?>>Nicaragua (+505)</option>
    <option value="+227" <?php echo $user['country_code'] == '+227' ? 'selected' : ''; ?>>Niger (+227)</option>
    <option value="+234" <?php echo $user['country_code'] == '+234' ? 'selected' : ''; ?>>Nigeria (+234)</option>
    <option value="+683" <?php echo $user['country_code'] == '+683' ? 'selected' : ''; ?>>Niue (+683)</option>
    <option value="+850" <?php echo $user['country_code'] == '+850' ? 'selected' : ''; ?>>North Korea (+850)</option>
    <option value="+47" <?php echo $user['country_code'] == '+47' ? 'selected' : ''; ?>>Norway (+47)</option>
    <option value="+968" <?php echo $user['country_code'] == '+968' ? 'selected' : ''; ?>>Oman (+968)</option>
    <option value="+92" <?php echo $user['country_code'] == '+92' ? 'selected' : ''; ?>>Pakistan (+92)</option>
    <option value="+680" <?php echo $user['country_code'] == '+680' ? 'selected' : ''; ?>>Palau (+680)</option>
    <option value="+970" <?php echo $user['country_code'] == '+970' ? 'selected' : ''; ?>>Palestinian Territories (+970)</option>
    <option value="+507" <?php echo $user['country_code'] == '+507' ? 'selected' : ''; ?>>Panama (+507)</option>
    <option value="+675" <?php echo $user['country_code'] == '+675' ? 'selected' : ''; ?>>Papua New Guinea (+675)</option>
    <option value="+595" <?php echo $user['country_code'] == '+595' ? 'selected' : ''; ?>>Paraguay (+595)</option>
    <option value="+51" <?php echo $user['country_code'] == '+51' ? 'selected' : ''; ?>>Peru (+51)</option>
    <option value="+63" <?php echo $user['country_code'] == '+63' ? 'selected' : ''; ?>>Philippines (+63)</option>
    <option value="+48" <?php echo $user['country_code'] == '+48' ? 'selected' : ''; ?>>Poland (+48)</option>
    <option value="+351" <?php echo $user['country_code'] == '+351' ? 'selected' : ''; ?>>Portugal (+351)</option>
    <option value="+1-787" <?php echo $user['country_code'] == '+1-787' ? 'selected' : ''; ?>>Puerto Rico (+1-787)</option>
    <option value="+974" <?php echo $user['country_code'] == '+974' ? 'selected' : ''; ?>>Qatar (+974)</option>
    <option value="+40" <?php echo $user['country_code'] == '+40' ? 'selected' : ''; ?>>Romania (+40)</option>
    <option value="+7" <?php echo $user['country_code'] == '+7' ? 'selected' : ''; ?>>Russia (+7)</option>
    <option value="+250" <?php echo $user['country_code'] == '+250' ? 'selected' : ''; ?>>Rwanda (+250)</option>
    <option value="+1-758" <?php echo $user['country_code'] == '+1-758' ? 'selected' : ''; ?>>Saint Lucia (+1-758)</option>
    <option value="+1-784" <?php echo $user['country_code'] == '+1-784' ? 'selected' : ''; ?>>Saint Vincent and the Grenadines (+1-784)</option>
    <option value="+684" <?php echo $user['country_code'] == '+684' ? 'selected' : ''; ?>>Samoa (+684)</option>
    <option value="+1-721" <?php echo $user['country_code'] == '+1-721' ? 'selected' : ''; ?>>Sint Maarten (+1-721)</option>
    <option value="+221" <?php echo $user['country_code'] == '+221' ? 'selected' : ''; ?>>Senegal (+221)</option>
    <option value="+381" <?php echo $user['country_code'] == '+381' ? 'selected' : ''; ?>>Serbia (+381)</option>
    <option value="+65" <?php echo $user['country_code'] == '+65' ? 'selected' : ''; ?>>Singapore (+65)</option>
    <option value="+421" <?php echo $user['country_code'] == '+421' ? 'selected' : ''; ?>>Slovakia (+421)</option>
    <option value="+386" <?php echo $user['country_code'] == '+386' ? 'selected' : ''; ?>>Slovenia (+386)</option>
    <option value="+677" <?php echo $user['country_code'] == '+677' ? 'selected' : ''; ?>>Solomon Islands (+677)</option>
    <option value="+252" <?php echo $user['country_code'] == '+252' ? 'selected' : ''; ?>>Somalia (+252)</option>
    <option value="+27" <?php echo $user['country_code'] == '+27' ? 'selected' : ''; ?>>South Africa (+27)</option>
    <option value="+34" <?php echo $user['country_code'] == '+34' ? 'selected' : ''; ?>>Spain (+34)</option>
    <option value="+94" <?php echo $user['country_code'] == '+94' ? 'selected' : ''; ?>>Sri Lanka (+94)</option>
    <option value="+249" <?php echo $user['country_code'] == '+249' ? 'selected' : ''; ?>>Sudan (+249)</option>
    <option value="+597" <?php echo $user['country_code'] == '+597' ? 'selected' : ''; ?>>Suriname (+597)</option>
    <option value="+47" <?php echo $user['country_code'] == '+47' ? 'selected' : ''; ?>>Sweden (+47)</option>
    <option value="+41" <?php echo $user['country_code'] == '+41' ? 'selected' : ''; ?>>Switzerland (+41)</option>
    <option value="+963" <?php echo $user['country_code'] == '+963' ? 'selected' : ''; ?>>Syria (+963)</option>
    <option value="+886" <?php echo $user['country_code'] == '+886' ? 'selected' : ''; ?>>Taiwan (+886)</option>
    <option value="+992" <?php echo $user['country_code'] == '+992' ? 'selected' : ''; ?>>Tajikistan (+992)</option>
    <option value="+255" <?php echo $user['country_code'] == '+255' ? 'selected' : ''; ?>>Tanzania (+255)</option>
    <option value="+66" <?php echo $user['country_code'] == '+66' ? 'selected' : ''; ?>>Thailand (+66)</option>
    <option value="+228" <?php echo $user['country_code'] == '+228' ? 'selected' : ''; ?>>Togo (+228)</option>
    <option value="+690" <?php echo $user['country_code'] == '+690' ? 'selected' : ''; ?>>Tokelau (+690)</option>
    <option value="+676" <?php echo $user['country_code'] == '+676' ? 'selected' : ''; ?>>Tonga (+676)</option>
    <option value="+1-868" <?php echo $user['country_code'] == '+1-868' ? 'selected' : ''; ?>>Trinidad and Tobago (+1-868)</option>
    <option value="+216" <?php echo $user['country_code'] == '+216' ? 'selected' : ''; ?>>Tunisia (+216)</option>
    <option value="+90" <?php echo $user['country_code'] == '+90' ? 'selected' : ''; ?>>Turkey (+90)</option>
    <option value="+993" <?php echo $user['country_code'] == '+993' ? 'selected' : ''; ?>>Turkmenistan (+993)</option>
    <option value="+1-649" <?php echo $user['country_code'] == '+1-649' ? 'selected' : ''; ?>>Turks and Caicos Islands (+1-649)</option>
    <option value="+688" <?php echo $user['country_code'] == '+688' ? 'selected' : ''; ?>>Tuvalu (+688)</option>
    <option value="+256" <?php echo $user['country_code'] == '+256' ? 'selected' : ''; ?>>Uganda (+256)</option>
    <option value="+380" <?php echo $user['country_code'] == '+380' ? 'selected' : ''; ?>>Ukraine (+380)</option>
    <option value="+971" <?php echo $user['country_code'] == '+971' ? 'selected' : ''; ?>>United Arab Emirates (+971)</option>
    <option value="+44" <?php echo $user['country_code'] == '+44' ? 'selected' : ''; ?>>United Kingdom (+44)</option>
    <option value="+1" <?php echo $user['country_code'] == '+1' ? 'selected' : ''; ?>>United States (+1)</option>
    <option value="+598" <?php echo $user['country_code'] == '+598' ? 'selected' : ''; ?>>Uruguay (+598)</option>
    <option value="+998" <?php echo $user['country_code'] == '+998' ? 'selected' : ''; ?>>Uzbekistan (+998)</option>
    <option value="+678" <?php echo $user['country_code'] == '+678' ? 'selected' : ''; ?>>Vanuatu (+678)</option>
    <option value="+379" <?php echo $user['country_code'] == '+379' ? 'selected' : ''; ?>>Vatican City (+379)</option>
    <option value="+58" <?php echo $user['country_code'] == '+58' ? 'selected' : ''; ?>>Venezuela (+58)</option>
    <option value="+84" <?php echo $user['country_code'] == '+84' ? 'selected' : ''; ?>>Vietnam (+84)</option>
    <option value="+1-284" <?php echo $user['country_code'] == '+1-284' ? 'selected' : ''; ?>>British Virgin Islands (+1-284)</option>
    <option value="+681" <?php echo $user['country_code'] == '+681' ? 'selected' : ''; ?>>Wallis and Futuna (+681)</option>
    <option value="+967" <?php echo $user['country_code'] == '+967' ? 'selected' : ''; ?>>Yemen (+967)</option>
    <option value="+260" <?php echo $user['country_code'] == '+260' ? 'selected' : ''; ?>>Zambia (+260)</option>
    <option value="+263" <?php echo $user['country_code'] == '+263' ? 'selected' : ''; ?>>Zimbabwe (+263)</option>

            </select>
        </div>
        <div class="form-group">
            <label>Phone</label>
            <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="text" name="password" value="<?php echo htmlspecialchars($user['password']); ?>" required>
        </div>

        <button type="submit">Save</button>
    </form>
</div>

<footer>
    <p>&copy; 2025 Travel Planner. All rights reserved.</p>
</footer>

<script>
function goBack() {
    window.history.back();
}
</script>

</body>
</html>

<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

// Establishing a database connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the first 16 destinations with their IDs from the database
$places = [];
$sql = "SELECT id, place_name FROM destinations1 
        WHERE (id BETWEEN 1 AND 24) OR (id BETWEEN 49 AND 54)";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $places[] = $row;
    }
}
$conn->close();

// Retrieve 'id1' from the URL (if passed)
$id1 = isset($_GET['id1']) ? $_GET['id1'] : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WiseGlobe</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Navbar fixed styling */
        .navbar {
    position: fixed;
    top: 0;
    width: 100%;
    z-index: 1000;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    background-color:#1e1e1e;
    display: flex;
    justify-content: space-between; /* Ensures home and login are on the left and right sides */
    align-items: center;
    padding: 10px 20px;
    margin-right:10px;
    margin-left:-20px;
}

.navbar .left-nav {
    display: flex;
    gap: 20px;
}

.navbar .center-nav {
    display: flex;
    gap: 20px;
}

.navbar .right-nav {
    gap: 30px;
    display: flex;
    justify-content: flex-end;
    margin-right:30px;
}

.navbar button {
    background-color: white;
    color: black;
    padding: 4px 10px; /* Adjusted padding for larger buttons */
    font-size: 16px; /* Slightly larger font size for better spacing */
    font-weight: bold;
    cursor: pointer;
    border-radius: 7px;
    transition: background-color 0.2s ease;
}

.navbar button:hover {
    background-color: #FF4500;
}

        /* Dropdown and hover styling */
        .search-bar-container {
            position: relative;
        }

        .search-bar-dropdown {
            display: none;
            background: #fff;
            border-radius: 5px;
            position: absolute;
            width: 98%;
            max-height: 200px;
            overflow-y: auto;
            z-index: 1000;
            opacity: 0.9;
            margin-left: 2px;
        }

        .search-bar-dropdown p {
            padding: 10px;
            cursor: pointer;
            margin: 0;
            color: #000;
        }

        .search-bar-dropdown p:hover {
            background: #f0f0f0;
            color: orange;
        }
        .wiseglobe-text {
    font-family: "Dancing Script", cursive; /* Replace with an appropriate font if needed */
    font-size: 60px; /* Adjust the size to match */
    display: flex;
    align-items: center;
    gap: 5px;
    margin-left:50px;
}

.wise {
    color: orangered;
    font-weight: bold;
    font-size: 30px;
}

.globe {
    color: white;
    font-weight: bold;
    font-size: 30px;
}

        /* Subscription Button Styling */
        .subscription-button {
            position: fixed;
            bottom: 20px; /* Distance from the bottom */
            right: 20px; /* Distance from the right */
            width: 60px;
            height: 60px;
            border-radius: 50%;
            cursor: pointer;
            z-index: 1001;
        }
        .subscription-button:hover {
            transform: scale(1.1);
        }
        .search-bar::placeholder {
    color: black; /* Change this to your desired color */
    font-weight: bold; /* Optional: Add styles like italic or bold */
    opacity: 100px; /* Optional: Adjust the opacity for lighter/darker text */
}
.back-button {
            position: absolute;
            top: 20px; /* Positioned at the top-left of the container */
            left: 20px;
            width: 50px; /* Increased size */
            height: 50px; /* Increased size */
            cursor: pointer;
            position: fixed;
            margin-top:-7px;
        }

        .back-button img {
            width: 70%;
            height: 70%;
            border-radius: 50%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            margin-top:5px;
        }

        /* Tooltip styling */
        .back-button:hover::after {
            content: "Wesite Icon";
            position: absolute;
            top: 60px; /* Below the button */
            left: 50%;
            transform: translateX(-50%);
            background-color: #000;
            color: #fff;
            padding: 5px 10px;
            font-size: 12px;
            border-radius: 5px;
            white-space: nowrap;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }
        .mahe {
            background-color: #000;
            color: #fff;
            text-align: center;
            padding: 10px 0;
            width: 100%;
            bottom: 0;
            left: 0;
            box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.2);
            font-size: 14px;
            margin: 0;
        }
    </style>
</head>
<body>
    <!-- Header Section with Navbar and Search -->
    <header>
        <div class="navbar">
            <!-- Left Side: Home -->
            <div class="left-nav">
            <div class="back-button">
                <img src="photo/main-icon.png" alt="Back">
    </div>
                <div class="wiseglobe-text">
                    <span class="wise" id="wise">Travel</span><span class="globe">Planner</span>
                </div>
            </div>
    
            <!-- Center: About Us, Contact Us, Admin Login -->
            <div class="center-nav">
                
            </div>
    
            <!-- Right Side: Login -->
            <div class="right-nav">
            <div class="search-bar-container">
                <input type="text" class="search-bar" placeholder="Search destinations..." oninput="filterDestinations(this)">
                <div class="search-bar-dropdown" id="searchDropdown">
                    <?php foreach ($places as $place): ?>
                        <p data-id="<?php echo $place['id']; ?>" 
                           onclick="selectSuggestion('<?php echo htmlspecialchars($place['place_name']); ?>', <?php echo $place['id']; ?>)">
                            <?php echo htmlspecialchars($place['place_name']); ?>
                        </p>
                    <?php endforeach; ?>
                </div>
            </div>
            
                <button onclick="window.location.href='index.html'">Home</button>
                <button onclick="window.location.href='aboutus.html'">About Us</button>
                <button id="contactBtn">Contact Us</button>
<button onclick="window.location.href='login.html'" class="profile-icon">
                Login
                    </button>
            </div>
        </div>
    </header>
    <section class="hero">
        <div class="hero-content">
            <h2>Welcome to TravelPlanner</h2>
            <h3>Your Ultimate Travel Plan Maker</h3>
        </div>
    </section>
    <!-- Features Section -->
    <section class="features">
        <div class="feature">
            <img src="photo/icon1.png" alt="Icon">
            <h3>Ultimate flexibility</h3>
            <p>You're in control, with free cancellation and payment options to satisfy any plan or budget.</p>
        </div>
        <div class="feature">
            <img src="photo/icon2.png" alt="Icon">
            <h3>Memorable experiences</h3>
            <p>Browse and book tours and activities so incredible, you'll want to tell your friends.</p>
        </div>
        <div class="feature">
            <img src="photo/icon3.png" alt="Icon">
            <h3>Quality at our core</h3>
            <p>High-quality standards. Millions of reviews. A tour company.</p>
        </div>
        <div class="feature">
            <img src="photo/icon4.png" alt="Icon">
            <h3>Award-winning support</h3>
            <p>New plan? No problem. We're here to help, 24/7.</p>
        </div>
        <div class="feature">
        <img src="photo/icon2.png" alt="Icon">
        <h3>Expert Recommendations</h3>
        <p>Get personalized travel recommendations based on your preferences and previous trips.</p>
    </div>
    </section>

    <!-- Famous Destinations Section -->
    <section class="destinations" id="selectSection">
        <h2>Select any Famous Destination</h2>
        <div class="destination-list">
            <?php foreach ($places as $place): ?>
                <div class="destination" onclick="goToDateSelection(<?php echo $place['id']; ?>)">
                    <img src="photo/<?php echo strtolower($place['place_name']); ?>.png" alt="<?php echo htmlspecialchars($place['place_name']); ?>">
                    <p><?php echo htmlspecialchars($place['place_name']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Footer Section -->
    <footer id="footer">
        <div class="contact" id="contactSection">
            <h3>Contact Us</h3>
            <p>Email: tourguide@gmail.com</p>
            <p>Address: 3-29, Gandhi Nagar, Ongole, Andhra Pradesh, India.</p>
            <p>Pin Code: 523263</p>
            <p>Mobile: +91 7319327469</p>
        </div>
        <div class="about" id="aboutSection">
            <h3>Travel Planner</h3>
            <p>Welcome to Wise Globe, your ultimate travel planning companion! We're dedicated to making your travel dreams come true by simplifying the trip planning process. Whether you're a frequent flyer, a family on vacation, or a solo adventurer, we're here to guide you every step of the way.</p>
        </div>
    </footer>
    <p class="mahe">&copy; 2024 All Rights Reserved</p>
    <!-- Subscription Button -->
    <img src="photo/subscription-icon.png" alt="Subscribe" class="subscription-button" onclick="subscribeUser()">

    <!-- JavaScript for Smooth Scroll and Search Dropdown -->
    <script>
        

        document.getElementById("contactBtn").addEventListener("click", function() {
            document.getElementById("contactSection").scrollIntoView({ behavior: "smooth" });
        });

        document.getElementById("selectBtn").addEventListener("click", function() {
            document.getElementById("selectSection").scrollIntoView({ behavior: "smooth" });
        });

        function filterDestinations(input) {
            const filter = input.value.toLowerCase().trim();
            const dropdown = document.getElementById("searchDropdown");
            const options = dropdown.querySelectorAll("p");

            let hasVisibleOptions = false;
            options.forEach(option => {
                const text = option.textContent.toLowerCase();
                if (text.includes(filter)) {
                    option.style.display = "block";
                    hasVisibleOptions = true;
                } else {
                    option.style.display = "none";
                }
            });

            dropdown.style.display = hasVisibleOptions ? "block" : "none";
        }

        document.addEventListener("click", function(event) {
            const searchBar = document.querySelector(".search-bar");
            const dropdown = document.getElementById("searchDropdown");
            if (!searchBar.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.style.display = "none";
            }
        });

        // Pass both id1 and id to dateSelected.html
        function selectSuggestion(suggestion, id) {
            const searchBar = document.querySelector(".search-bar");
            searchBar.value = suggestion;
            const dropdown = document.getElementById("searchDropdown");
            dropdown.style.display = "none";

            // Retrieve 'id1' from the current URL
            const urlParams = new URLSearchParams(window.location.search);
            const id1 = urlParams.get('id1');

            // Pass both id1 and id to the URL
            window.location.href = `dateSelected.html?id1=${id1}&id=${id}`;
        }

        function goToDateSelection(id) {
            // Retrieve 'id1' from the current URL
            const urlParams = new URLSearchParams(window.location.search);
            const id1 = urlParams.get('id1');

            // Pass both id1 and id to the URL
            window.location.href = `dateSelected.html?id1=${id1}&id=${id}`;
        }

        function subscribeUser() {
            alert('It seems to be, you are not logged in. please login or create a account');
            window.location.href = `login.html`;
        }
        function goBack() {
            window.history.back();
        }
    </script>
</body>
</html>

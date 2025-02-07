<?php
$orsApiKey = '5b3ce3597851110001cf62483610f442006c4c388e06a6794b215033'; // OpenRouteService API Key
$unsplashAccessKey = 'CR5cvb2rXf2eimuanDS2UshQbJlp8uIpg17VewjuaOM'; // Unsplash API Access Key

if (isset($_GET['destination'])) {
    $destination = urlencode($_GET['destination']);

    // Geocode Destination
    $nominatimUrl = 'https://nominatim.openstreetmap.org/search';
    $httpOptions = [
        "http" => [
            "header" => "User-Agent: TravelPlanner/1.0 (myemail@example.com)" // Replace with your email or app identifier
        ]
    ];
    $context = stream_context_create($httpOptions);
    $geoUrl = "{$nominatimUrl}?q={$destination}&format=json&limit=1";

    $geoData = json_decode(file_get_contents($geoUrl, false, $context), true);

    if (!empty($geoData)) {
        $destLat = $geoData[0]['lat'];
        $destLon = $geoData[0]['lon'];
        $destDisplayName = $geoData[0]['display_name'];

        // Fetch Images from Unsplash
        $imageApiUrl = "https://api.unsplash.com/search/photos?query={$destination}&client_id={$unsplashAccessKey}";
        $imageData = json_decode(file_get_contents($imageApiUrl), true);
    } else {
        echo "Could not geocode the destination.";
        exit;
    }
} else {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Travel Planner</title>
        <style>
            body {
                font-family: 'Arial', sans-serif;
                background: linear-gradient(to right, #6a11cb, #2575fc);
                color: white;
                margin: 0;
                padding: 0;
            }
            header {
                background-color: rgba(0, 123, 255, 0.9);
                color: white;
                padding: 20px;
                text-align: center;
                border-bottom: 2px solid #0056b3;
            }
            header h1 {
                margin: 0;
                font-size: 36px;
            }
            form {
                max-width: 600px;
                margin: 40px auto;
                background-color: rgba(255, 255, 255, 0.8);
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
            }
            label, input[type="text"] {
                width: 100%;
                margin-bottom: 15px;
            }
            input[type="text"] {
                padding: 12px;
                font-size: 16px;
                border: 1px solid #ccc;
                border-radius: 8px;
            }
            input[type="submit"] {
                background-color: #28a745;
                color: white;
                padding: 12px 20px;
                border: none;
                border-radius: 8px;
                cursor: pointer;
                font-size: 16px;
                width: 100%;
            }
            input[type="submit"]:hover {
                background-color: #218838;
            }
            .destination-info {
                text-align: center;
                margin-top: 30px;
                padding: 20px;
                background-color: rgba(255, 255, 255, 0.9);
                border-radius: 8px;
                box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
                margin-bottom: 40px;
            }
            .destination-info h2 {
                font-size: 24px;
                color: #333;
            }
            .destination-info p {
                font-size: 16px;
                color: #555;
            }
            .image-container {
                margin-top: 40px;
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
                gap: 20px;
                padding: 20px;
            }
            .image-container img {
                width: 300px;
                height: 169px; /* 16:9 aspect ratio */
                object-fit: cover;
                border-radius: 8px;
                box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
            }
        </style>
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    </head>
    <body>

        <header>
            <h1>Explore Your Destination</h1>
        </header>

        <form method="GET" action="">
            <label for="destination">Search a Destination:</label>
            <input type="text" id="destination" name="destination" placeholder="e.g., Eiffel Tower" required>
            <input type="submit" value="Search">
        </form>

    </body>
    </html>
    <?php
    exit;
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Explore <?php echo htmlspecialchars($destination); ?></title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
</head>
<body>

<header>
    <h1>Explore <?php echo htmlspecialchars($destDisplayName); ?></h1>
</header>

<div class="destination-info">
    <h2>Location: <?php echo htmlspecialchars($destDisplayName); ?></h2>
    <p>Latitude: <?php echo $destLat; ?> | Longitude: <?php echo $destLon; ?></p>
</div>

<div class="image-container">
    <h2>Images of <?php echo htmlspecialchars($destination); ?></h2>
    <?php
    if (!empty($imageData['results'])) {
        foreach ($imageData['results'] as $image) {
            echo "<img src='{$image['urls']['small']}' alt='Image of {$destination}' />";
        }
    } else {
        echo "<p>No images found for this location.</p>";
    }
    ?>
</div>

</body>
</html>

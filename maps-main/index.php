<?php
// Fetch `id` parameter from the URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$place = isset($_GET['place']) ? htmlspecialchars($_GET['place'], ENT_QUOTES, 'UTF-8') : 'Unknown Place';  // Default if not set

$host = 'localhost';
$dbname = 'project'; // Database name
$user = 'root'; // Your database username
$password = ''; // Your database password

// Initialize variables
$place_name = 'Unknown Destination';

try {
    // Connect to the database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query to fetch the place_name based on id
    $stmt = $pdo->prepare("SELECT place_name FROM destinations1 WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    // Fetch result
    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $place_name = $row['place_name'];
    } else {
        $place_name = 'Place not found';
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <script src="https://api.mapbox.com/mapbox-gl-js/v1.12.0/mapbox-gl.js"></script>
  <link href="https://api.mapbox.com/mapbox-gl-js/v1.12.0/mapbox-gl.css" rel="stylesheet" />
  <script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-directions/v4.1.0/mapbox-gl-directions.js"></script>
  <link
    rel="stylesheet"
    href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-directions/v4.1.0/mapbox-gl-directions.css"
    type="text/css"
  />
  <title>Google Maps Clone</title>
  <style>
    body {
      margin: 0;
    }

    #map {
      height: 100vh;
      width: 100vw;
    }
    
  </style>
</head>
<body>

  <div id="map"></div>

  <script>

    mapboxgl.accessToken = "pk.eyJ1Ijoic3ViaGFtcHJlZXQiLCJhIjoiY2toY2IwejF1MDdodzJxbWRuZHAweDV6aiJ9.Ys8MP5kVTk5P9V2TDvnuDg";

    // Destination place name fetched from PHP
    const destinationPlaceName = "<?php echo htmlspecialchars($place_name, ENT_QUOTES, 'UTF-8'); ?>" + ", " + "<?php echo htmlspecialchars($place, ENT_QUOTES, 'UTF-8'); ?>";

    // Initialize the map
    const map = new mapboxgl.Map({
      container: "map",
      style: "mapbox://styles/mapbox/streets-v11",
      center: [-2.24, 53.48], // Default center
      zoom: 15
    });

    // Add navigation controls
    const nav = new mapboxgl.NavigationControl();
    map.addControl(nav);

    // Add directions control
    const directions = new MapboxDirections({
      accessToken: mapboxgl.accessToken
    });

    map.addControl(directions, "top-left");

    // Set the destination place in the directions control
    directions.setDestination(destinationPlaceName);

    // Geocode the destination to get coordinates for highlighting
    fetch(`https://api.mapbox.com/geocoding/v5/mapbox.places/${encodeURIComponent(destinationPlaceName)}.json?access_token=${mapboxgl.accessToken}`)
      .then(response => response.json())
      .then(data => {
        if (data.features && data.features.length > 0) {
          const coordinates = data.features[0].geometry.coordinates;
          
          // Add a circle to highlight the destination
          map.addLayer({
            id: 'highlight-destination',
            type: 'circle',
            source: {
              type: 'geojson',
              data: {
                type: 'Feature',
                geometry: {
                  type: 'Point',
                  coordinates: coordinates
                }
              }
            },
            paint: {
              'circle-radius': 10,
              'circle-color': '#FF0000',  // Red color to highlight the destination
              'circle-opacity': 0.7
            }
          });

          // Optionally, zoom to the destination location
          map.flyTo({
            center: coordinates,
            zoom: 15,
            essential: true
          });
        }
      })
      .catch(error => {
        console.error("Error fetching geocode data: ", error);
      });
  </script>
</body>
</html>

<?php
// Check if the user has entered a destination
if (isset($_GET['destination'])) {
    // Get the destination from the query string
    $destination = urlencode($_GET['destination']);

    // Wikipedia API URL to fetch information (including introduction and history)
    $wikipediaApiUrl = "https://en.wikipedia.org/w/api.php?action=query&prop=extracts&exintro&explaintext&titles={$destination}&format=json";

    // Fetch the Wikipedia article using file_get_contents
    $response = file_get_contents($wikipediaApiUrl);

    // Debug: Show the raw response from Wikipedia API
    // Uncomment the following line for debugging
    // echo "<pre>" . print_r($response, true) . "</pre>";

    // Decode the JSON response
    $data = json_decode($response, true);

    // Check if the data exists and contains the extract for the page
    if (isset($data['query']['pages'])) {
        $pages = $data['query']['pages'];
        $pageId = key($pages); // Get the first page ID

        if ($pageId != -1) {
            $historyText = $pages[$pageId]['extract']; // Extract the article's content

            // If the extract is empty, set a default message
            if (empty($historyText)) {
                $historyText = "No information available about this place.";
            }
        } else {
            // If the page ID is -1, the place does not exist on Wikipedia
            $historyText = "Could not find an article for this place.";
        }
    } else {
        // If the response doesn't contain pages, display an error
        $historyText = "Error fetching data. Please check the destination name and try again.";
    }
} else {
    // Default message if no destination is entered
    $historyText = "Please enter a destination to get information.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wikipedia Information</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background: linear-gradient(to right, #00c6ff, #0072ff);
            color: #fff;
        }
        h1, h2 {
            text-align: center;
        }
        form {
            text-align: center;
            margin: 20px;
        }
        input[type="text"] {
            padding: 10px;
            width: 300px;
            margin-right: 10px;
            font-size: 16px;
        }
        input[type="submit"] {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
        }
        #info {
            margin: 30px auto;
            max-width: 800px;
            background: rgba(0, 0, 0, 0.7);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }
        #info p {
            line-height: 1.6;
            font-size: 18px;
        }
        .error {
            color: #ff6347;
            font-size: 18px;
        }
    </style>
</head>
<body>

    <h1>Wikipedia Information Search</h1>
    <form method="GET" action="">
        <label for="destination">Enter the destination (e.g., Eiffel Tower, Taj Mahal):</label><br>
        <input type="text" id="destination" name="destination" required placeholder="Enter a place">
        <input type="submit" value="Search">
    </form>

    <div id="info">
        <h2>Information About <?php echo isset($_GET['destination']) ? htmlspecialchars($_GET['destination']) : 'Your Search'; ?></h2>
        <p><?php echo $historyText; ?></p>
    </div>

</body>
</html>

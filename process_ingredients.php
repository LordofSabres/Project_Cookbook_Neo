<?php
// Assume you have a database connection stored in $conn
require './connect.php';
$conn = Connect();

session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['UserID'])) {
    // Redirect to the login page if not logged in
    header("Location: login.php");
    exit();
}

// Get user ID from the session
$userID = $_SESSION['UserID'];

// Process the form data when the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Extract form data
    $ingredientCategories = $_POST["ingredientCategory"]; // Categories array
    $ingredientItems = $_POST["ingredientItems"]; // Items array

    // Initialize arrays to track duplicate items and new items added
    $existingItems = [];
    $newItems = [];

    // Prepare statement for inserting new items into Ingredients table
    $insertIngredientStmt = $conn->prepare("INSERT INTO Ingredients (Name, Category) VALUES (?, ?)");

    // Prepare statement for inserting new items into InventoryIngredients table
    $insertInventoryStmt = $conn->prepare("INSERT INTO InventoryIngredients (UserID, IngredientID) VALUES (?, ?)");

    // Process each category and its corresponding items
    for ($i = 0; $i < count($ingredientCategories); $i++) {
        $category = $ingredientCategories[$i];
        $itemNames = array_map('trim', explode(',', $ingredientItems[$i])); // Split item names by comma

        foreach ($itemNames as $itemName) {
            $itemName = trim($itemName); // Remove leading/trailing whitespace

            // Convert item name to lowercase for storage in the database
            $itemNameLower = strtolower($itemName);

            // Convert item name to uppercase for display
            $itemNameUpper = strtoupper($itemName);

            // Check if the item already exists in the user's inventory for the specific category
            $checkQuery = "SELECT COUNT(*) AS count FROM InventoryIngredients WHERE UserID = ? AND IngredientID = (SELECT IngredientID FROM Ingredients WHERE LOWER(Name) = ? AND Category = ?)";
            $checkStmt = $conn->prepare($checkQuery);
            $checkStmt->bind_param("iss", $userID, $itemNameLower, $category);
            $checkStmt->execute();
            $checkResult = $checkStmt->get_result();
            $checkRow = $checkResult->fetch_assoc();
            $count = $checkRow['count'];
            $checkStmt->close();

            if ($count > 0) {
                // Item already exists in the user's inventory, add it to the existingItems array
                $existingItems[] = $itemNameUpper;
                continue; // Skip inserting duplicate items
            }

            // Check if the item exists in Ingredients table
            $itemID = null;
            $checkItemQuery = "SELECT IngredientID FROM Ingredients WHERE LOWER(Name) = ? AND Category = ?";
            $checkItemStmt = $conn->prepare($checkItemQuery);
            $checkItemStmt->bind_param("ss", $itemNameLower, $category);
            $checkItemStmt->execute();
            $checkResult = $checkItemStmt->get_result();

            if ($checkResult->num_rows == 0) {
                // Item does not exist in Ingredients table, insert it
                $insertIngredientStmt->bind_param("ss", $itemNameLower, $category);
                $insertIngredientStmt->execute();
                $newItems[] = $itemNameUpper; // Track new items added
                $itemID = $insertIngredientStmt->insert_id; // Get the generated ID
            } else {
                // Item exists in Ingredients table, get its ID
                $row = $checkResult->fetch_assoc();
                $itemID = $row["IngredientID"];
            }

            $checkItemStmt->close();

            // Insert into InventoryIngredients
            $insertInventoryStmt->bind_param("ii", $userID, $itemID);
            $insertInventoryStmt->execute();
        }
    }

    // Display a message for duplicate items
    if (!empty($existingItems)) {
        echo "The following items were skipped as they already exist in the inventory: " . implode(", ", $existingItems) . "<br>";
    }

    // Display a message for new items added
    if (!empty($newItems)) {
        echo "The following new items were added to the inventory: " . implode(", ", $newItems) . "<br>";
    }

    // Successful insertion
    echo "Inventory updated successfully.";
    $conn->close();
    header("location: inventory.php"); // Redirect to the inventory page
    exit;
}
?>

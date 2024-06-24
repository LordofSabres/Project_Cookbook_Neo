<?php
// Start the session
session_start();

// Redirect to login page if user is not logged in
if (!isset($_SESSION['UserID'])) {
    header("Location: login.php");
    exit();
}

// Include database connection
require './connect.php';
$conn = Connect();

// Check if the RecipeID and Name are provided
if (isset($_GET['RecipeID']) && isset($_GET['Name'])) {
    $RecipeID = $_GET['RecipeID'];
    $name = $_GET['Name'];
    
    // Get UserID from session
    $UserID = $_SESSION['UserID'];

    // Properly escape the name parameter
    $escapedName = $conn->real_escape_string($name);

    // Check if the recipe exists in the global cookbook
    $checkQuery = "SELECT * FROM Recipes WHERE RecipeID = $RecipeID AND Name = '$escapedName'";
    $result = $conn->query($checkQuery);

    if ($result->num_rows > 0) {
        // Fetch recipe details from the global cookbook
        $row = $result->fetch_assoc();
        $RecipeName = $conn->real_escape_string($row['Name']);
        $RecipeInstructions = $conn->real_escape_string($row['Instructions']);
        
        // Start transaction
        $conn->begin_transaction();

        try {
            // Insert the recipe into the user's cookbook
            $addRecipeQuery = "INSERT INTO Recipes (OwnerID, Name, Instructions) VALUES ($UserID, '$RecipeName', '$RecipeInstructions')";
            if (!$conn->query($addRecipeQuery)) {
                throw new Exception("Error: Unable to add recipe to your Personal Cookbook.");
            }
            // Get the new RecipeID
            $newRecipeID = $conn->insert_id;

            // Copy ingredients
            $ingredientsQuery = "SELECT * FROM RecipeIngredients WHERE RecipeID = $RecipeID";
            $ingredientsResult = $conn->query($ingredientsQuery);
            while ($ingredient = $ingredientsResult->fetch_assoc()) {
                $IngredientID = $ingredient['IngredientID'];
                $Quantity = $conn->real_escape_string($ingredient['Quantity']);
                $Unit = $conn->real_escape_string($ingredient['Unit']);
                $addIngredientQuery = "INSERT INTO RecipeIngredients (RecipeID, IngredientID, Quantity, Unit) VALUES ($newRecipeID, $IngredientID, '$Quantity', '$Unit')";
                if (!$conn->query($addIngredientQuery)) {
                    throw new Exception("Error: Unable to add ingredients to your Personal Cookbook.");
                }
            }

            // Copy equipment
            $equipmentQuery = "SELECT * FROM RecipeEquipment WHERE RecipeID = $RecipeID";
            $equipmentResult = $conn->query($equipmentQuery);
            while ($equipment = $equipmentResult->fetch_assoc()) {
                $EquipmentID = $equipment['EquipmentID'];
                $addEquipmentQuery = "INSERT INTO RecipeEquipment (RecipeID, EquipmentID) VALUES ($newRecipeID, $EquipmentID)";
                if (!$conn->query($addEquipmentQuery)) {
                    throw new Exception("Error: Unable to add equipment to your Personal Cookbook.");
                }
            }

            // Commit transaction
            $conn->commit();
            echo "<p>Recipe successfully added to your Personal Cookbook!</p>";
        } catch (Exception $e) {
            // Rollback transaction on error
            $conn->rollback();
            echo "<p>" . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p>Error: Recipe not found in the Global Cookbook.</p>";
    }
} else {
    echo "<p>Error: RecipeID or Name not provided.</p>";
}

// Close database connection
$conn->close();
header("Location: main.php");
?>
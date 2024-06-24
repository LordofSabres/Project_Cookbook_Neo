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

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Include menubar.php at the top of the page -->
    <?php include 'menubar.php'; ?>
</head>

<body>
    <?php
    // Get user ID from session
    $UserID = $_SESSION['UserID'];

    echo "<h2>Welcome to the Global Cookbook</h2>";

    // Fetch public recipes from the Recipes table with ingredients, quantities, measurements, and equipment
    $sql = "SELECT R.RecipeID, R.Name,
            GROUP_CONCAT(CONCAT(RI.Quantity, ' ', RI.Unit, ' of ', I.Name) SEPARATOR '\n') AS Ingredients,
            GROUP_CONCAT(DISTINCT E.Name) AS Equipment, R.Instructions
            FROM Recipes R
            LEFT JOIN RecipeIngredients RI ON R.RecipeID = RI.RecipeID
            LEFT JOIN Ingredients I ON RI.IngredientID = I.IngredientID
            LEFT JOIN RecipeEquipment RE ON R.RecipeID = RE.RecipeID
            LEFT JOIN Equipment E ON RE.EquipmentID = E.EquipmentID
            WHERE R.Status = 'Public'
            GROUP BY R.RecipeID";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo '<table class="table">';
        echo '<thead><tr><th>Name</th><th>Ingredients</th><th>Equipment</th><th>Instructions</th><th>Add to Personal Cookbook</th></tr></thead><tbody>';
        while ($row = $result->fetch_assoc()) {
            $id = $row["RecipeID"];
            $name = $conn->real_escape_string($row['Name']);
            $ingredients = $conn->real_escape_string($row['Ingredients']);
            $equipment = $conn->real_escape_string($row['Equipment']);
            
            // Check if the recipe with the same name, ingredients, and equipment is already in the user's personal cookbook
            $checkQuery = "
                SELECT R.RecipeID
                FROM Recipes R
                LEFT JOIN RecipeIngredients RI ON R.RecipeID = RI.RecipeID
                LEFT JOIN Ingredients I ON RI.IngredientID = I.IngredientID
                LEFT JOIN RecipeEquipment RE ON R.RecipeID = RE.RecipeID
                LEFT JOIN Equipment E ON RE.EquipmentID = E.EquipmentID
                WHERE R.OwnerID = $UserID 
                AND R.Name = '$name'
                GROUP BY R.RecipeID
                HAVING 
                    GROUP_CONCAT(CONCAT(RI.Quantity, ' ', RI.Unit, ' of ', I.Name) SEPARATOR '\n') = '$ingredients'
                    AND GROUP_CONCAT(DISTINCT E.Name) = '$equipment'
            ";
            $checkResult = $conn->query($checkQuery);
            $isAlreadyAdded = ($checkResult && $checkResult->num_rows > 0);

            echo '<tr>';
            echo '<td>' . $row['Name'] . '</td>';
            echo '<td>' . nl2br($row['Ingredients']) . '</td>';
            echo '<td>' . $row['Equipment'] . '</td>';
            echo '<td>' . $row['Instructions'] . '</td>';

            if ($isAlreadyAdded) {
                // If the recipe is already in the user's cookbook, display a message
                echo '<td>Already added</td>';
            } else {
                // If the recipe is not in the user's cookbook, display the "Add to Personal Cookbook" button
                echo '<td><a href="add_to_cookbook.php?RecipeID=' . $id . '&Name=' . urlencode($row['Name']) . '" class="btn btn-primary">Add to Personal Cookbook</a></td>';
            }
            echo '</tr>';
        }
        echo '</tbody></table>';
    } else {
        echo "<p>No public recipes available in the Global Cookbook.</p>";
    }

    // Close database connection
    $conn->close();
    ?>

    <br><br>
    <button onclick="window.location.href='main.php'">Personal Cookbook</button>

    <!-- Footer -->
    <footer>
        <?php include 'footer.php'; ?>
    </footer>

    <!-- SCRIPTS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script src="web_background.js" type="text/javascript"></script>
    <!-- END OF SCRIPTS -->
</body>

</html>

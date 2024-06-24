<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="web_background.js" type="text/javascript"></script>
    <?php
    // Include menubar.php at the top of the page
    include 'menubar.php';
    ?>

</head>

<body>

<h1>Add Ingredients to Inventory</h1>

<form action="process_ingredients.php" method="post">
    <div id="ingredients">
        <label for="ingredientCategory">Ingredient Category:</label>
        <select id="ingredientCategory" name="ingredientCategory[]" required>
            <option value="Dairy">Dairy</option>
            <option value="Fruits">Fruits</option>
            <option value="Vegetables">Vegetables</option>
            <option value="Carbs">Carbs</option>
            <option value="Preserves">Preserves</option>
            <option value="Meat">Meat</option>
            <option value="Seafood">Seafood</option>
            <option value="Eggs">Eggs</option>
            <option value="Condiments">Condiments</option>
            <option value="Spices">Spices</option>
        </select>
        <label for="ingredientItems">Ingredients for Category above (Separated by commas):</label>
        <input type="text" id="ingredientItems" name="ingredientItems[]" required>
    </div>

        <br><br>
        
        <button onclick="addCategoryIngredients()">Add Category</button>
        <button type="submit">Add to Inventory</button>
    </form>

    <?php include 'footer.php'; ?>

</body>

</html>

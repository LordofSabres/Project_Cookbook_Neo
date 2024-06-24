function redirectToLogin() {
    window.location.href = "login.php";
}

function redirectToSignUp() {
    window.location.href = "signup.php";
}

function redirectToAddRecipe() {
    window.location.href = "addrecipe.php";
}

function redirectToInventory() {
    window.location.href = "inventory.php";
}

function redirectToGlobal() {
    window.location.href = "globalcookbook.php";
}

function redirectToAddIngredients() {
    window.location.href = "add_ingredients.php";
}

function redirectToAddEquipment() {
    window.location.href = "add_equipment.php";
}

function redirectToEditRecipe() {
    window.location.href = "editrecipe.php";
}

function redirectToEditInventory() {
    window.location.href = "editinventory.php";
}

function redirectToMealGenerator() {
    window.location.href = "mealgenerate.php";
}

function redirectToMain() {
    window.location.href = "main.php";
}

function openModal() {
    document.getElementById('loginModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('loginModal').style.display = 'none';
}

// function activatePrank() {
//     var music = new Audio('./nevergonnagiveyouup.MP3');
//     music.play();

//      // Create HTML elements
//      var prankTitle = document.createElement('h1');
//      prankTitle.textContent = 'APRIL FOOLS';
 
//      var lineBreak1 = document.createElement('br');
 
//      var prankSubtitle = document.createElement('h2');
//      prankSubtitle.textContent = 'Cook Zillion COMING SOON This Summer';
 
//      var lineBreak2 = document.createElement('br');
 
//      // Append elements to the body or any desired container
//      document.body.appendChild(prankTitle);
//      document.body.appendChild(lineBreak1);
//      document.body.appendChild(prankSubtitle);
//      document.body.appendChild(lineBreak2);
// }

function authenticateUser() {
    // In a real-world scenario, you would perform server-side authentication here.
    // This example uses hardcoded credentials for demonstration purposes.
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;

    if (username === 'demo' && password === 'password') {
        alert('Login successful!');
        closeModal();
        // Redirect to the main page or perform other actions after successful login.
        return false; // Prevent form submission
    } else {
        alert('Invalid username or password. Please try again.');
        return false; // Prevent form submission
    }
}

// function addIngredient() {
//     var ingredientDiv = document.createElement('div');
//     ingredientDiv.className = 'ingredient';
//     ingredientDiv.innerHTML =
//         '<select name="ingredientCategory[]" required>' +
//         '<option value="Dairy">Dairy</option>' +
//         '<option value="Fruits">Fruits</option>' +
//         '<option value="Vegetables">Vegetables</option>' +
//         '<option value="Carbs">Carbs</option>' +
//         '<option value="Preserves">Preserves</option>' +
//         '<option value="Meat">Meat</option>' +
//         '<option value="Seafood">Seafood</option>' +
//         '<option value="Condiments">Condiments</option>' +
//         '<option value="Spices">Spices</option>' +
//         '</select>' +
//         '<input type="text" name="ingredientName[]" class="autocomplete" required>' +
//         '<input type="text" name="ingredientQuantity[]" placeholder="Quantity" required>' +
//         '<select name="ingredientMeasurement[]" required>' +
//         '<option value="tsp">tsp</option>' +
//         '<option value="tbsp">tbsp</option>' +
//         '<option value="cup">cup</option>' +
//         '<option value="floz">fl oz</option>' +
//         '<option value="pint">pint</option>' +
//         '<option value="quart">qt</option>' +
//         '<option value="gal">gal</option>' +
//         '<option value="mililiter">mL</option>' +
//         '<option value="liter">L</option>' +
//         '<option value="oz">oz</option>' +
//         '<option value="lb">lb</option>' +
//         '<option value="g">g</option>' +
//         '<option value="kg">kg</option>' +
//         '<option value="pc">piece</option>' +
//         '<option value="pinch">pinch</option>' +
//         '<option value="dash">dash</option>' +
//         '<option value="drop">drop</option>' +
//         '<option value="stick">stick</option>' +
//         '</select>' +
//         '<button type="button" onclick="removeElement(this)">Remove</button>';

//     document.getElementById('ingredients').appendChild(ingredientDiv);
// }

function addIngredient() {
    var ingredientDiv = document.createElement('div');
    ingredientDiv.className = 'ingredient';
    ingredientDiv.innerHTML =
        '<select name="ingredientCategory[]" required>' +
        '<option value="Dairy">Dairy</option>' +
        '<option value="Fruits">Fruits</option>' +
        '<option value="Vegetables">Vegetables</option>' +
        '<option value="Carbs">Carbs</option>' +
        '<option value="Preserves">Preserves</option>' +
        '<option value="Meat">Meat</option>' +
        '<option value="Seafood">Seafood</option>' +
        '<option value="Eggs">Eggs</option>' +
        '<option value="Condiments">Condiments</option>' +
        '<option value="Spices">Spices</option>' +
        '</select>' +
        '<input type="text" name="ingredientName[]" class="autocomplete-ingredients" required>' +
        '<input type="text" name="ingredientQuantity[]" placeholder="Quantity" required>' +
        '<select name="ingredientMeasurement[]" required>' +
        '<option value="tsp">tsp</option>' +
        '<option value="tbsp">tbsp</option>' +
        '<option value="cup">cup</option>' +
        '<option value="floz">fl oz</option>' +
        '<option value="pint">pint</option>' +
        '<option value="quart">qt</option>' +
        '<option value="gal">gal</option>' +
        '<option value="mililiter">mL</option>' +
        '<option value="liter">L</option>' +
        '<option value="oz">oz</option>' +
        '<option value="lb">lb</option>' +
        '<option value="g">g</option>' +
        '<option value="kg">kg</option>' +
        '<option value="pc">piece</option>' +
        '<option value="pinch">pinch</option>' +
        '<option value="dash">dash</option>' +
        '<option value="drop">drop</option>' +
        '<option value="stick">stick</option>' +
        '</select>' +
        '<button type="button" onclick="removeElement(this)">Remove</button>';

    document.getElementById('ingredients').appendChild(ingredientDiv);

    // Apply autocomplete to the newly added input field
    $(ingredientDiv).find('.autocomplete-ingredients').autocomplete({
        source: function(request, response) {
            $.ajax({
                url: "autocomplete_ingredients.php",
                dataType: "json",
                data: {
                    term: request.term
                },
                success: function(data) {
                    response(data);
                }
            });
        },
        minLength: 2 // Minimum characters before autocomplete kicks in
    });
}

function addEquipment() {
    var equipmentDiv = document.createElement('div');
    equipmentDiv.className = 'equipment';
    equipmentDiv.innerHTML = '<input type="text" name="equipmentName[]" class="autocomplete-equipment" required>' +
        '<button type="button" onclick="removeElement(this)">Remove</button>';

    document.getElementById('equipment').appendChild(equipmentDiv);

    // Apply autocomplete to the newly added input field
    $(equipmentDiv).find('.autocomplete-equipment').autocomplete({
        source: function(request, response) {
            $.ajax({
                url: "autocomplete_equipment.php",
                dataType: "json",
                data: {
                    term: request.term
                },
                success: function(data) {
                    response(data);
                }
            });
        },
        minLength: 2 // Minimum characters before autocomplete kicks in
    });
}

function removeElement(button) {
    button.parentNode.parentNode.removeChild(button.parentNode);
}

function getCurrentYear() {
    return new Date().getFullYear();
  }

  $(function() {
    $(".autocomplete-ingredients").autocomplete({
        source: function(request, response) {
            $.ajax({
                url: "autocomplete_ingredients.php",
                dataType: "json",
                data: {
                    term: request.term
                },
                success: function(data) {
                    response(data);
                }
            });
        },
        minLength: 2 // Minimum characters before autocomplete kicks in
    });
});


$(function() {
    $(".autocomplete-equipment").autocomplete({
        source: function(request, response) {
            $.ajax({
                url: "autocomplete_equipment.php",
                dataType: "json",
                data: {
                    term: request.term
                },
                success: function(data) {
                    response(data);
                }
            });
        },
        minLength: 2 // Minimum characters before autocomplete kicks in
    });
});

// function viewRecipe(id, name, ingredients, equipment, instructions) {
//     // document.getElementById('recipeName').innerText = name;
//     // document.getElementById('recipeIngredients').innerHTML = ingredients;
//     // document.getElementById('recipeEquipment').innerText = equipment;
//     // document.getElementById('recipeInstructions').innerHTML = instructions;
//     // var modal = new bootstrap.Modal(document.getElementById('recipeModal'));
//     // modal.show();

//     console.log("ID:", id);
//     console.log("Name:", name);
//     console.log("Ingredients:", ingredients);
//     console.log("Equipment:", equipment);
//     console.log("Instructions:", instructions);
// }

// function viewRecipe() {
//     alert("You pressed me!");
// }

$(document).ready(function () {
    $(document).on('click', '.addCategoryBtn', function () {
        $(this).parent().append('<br><label for="itemCategory">Item Category:</label>' +
            '<input type="text" name="itemCategory[]" required>');
    });
});

function addCategoryIngredients() {
    var ingredientDiv = document.createElement('div');
    ingredientDiv.className = 'ingredient';
    ingredientDiv.innerHTML =
        '<br><br>' +
        '<select name="ingredientCategory[]" required>' +
        '<option value="Dairy">Dairy</option>' +
        '<option value="Fruits">Fruits</option>' +
        '<option value="Vegetables">Vegetables</option>' +
        '<option value="Carbs">Carbs</option>' +
        '<option value="Preserves">Preserves</option>' +
        '<option value="Meat">Meat</option>' +
        '<option value="Seafood">Seafood</option>' +
        '<option value="Eggs">Eggs</option>' +
        '<option value="Condiments">Condiments</option>' +
        '<option value="Spices">Spices</option>' +
        '</select>' +
        '<label for="ingredientItems">Ingredients for Category above (Separated by commas):</label>' +
        '<input type="text" id="ingredientItems" name="ingredientItems[]" required>';

    document.getElementById('ingredients').appendChild(ingredientDiv);

}
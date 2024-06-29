<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require './connect.php';
$conn = Connect();

// Initialize variables
$first_name = $last_name = $email = $username = $password = "";
$firstNameErr = $lastNameErr = $emailErr = $usernameErr = $passwordErr = "";
$signupSuccess = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate first name
    if (empty($_POST["FirstName"])) {
        $firstNameErr = "First name is required";
    } else {
        $first_name = test_input($_POST["FirstName"]);
    }

    // Validate last name
    if (empty($_POST["LastName"])) {
        $lastNameErr = "Last name is required";
    } else {
        $last_name = test_input($_POST["LastName"]);
    }

    // Validate email
    if (empty($_POST["Email"])) {
        $emailErr = "Email is required";
    } else {
        $email = test_input($_POST["Email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }
    }

    // Validate username
    if (empty($_POST["Username"])) {
        $usernameErr = "Username is required";
    } else {
        $username = test_input($_POST["Username"]);
    }

    // Validate password
    if (empty($_POST["Password"])) {
        $passwordErr = "Password is required";
    } else {
        $password = test_input($_POST["Password"]);
        if (strlen($password) < 8) {
            $passwordErr = "Password must be at least 8 characters";
        }
    }

    // If all fields are valid, proceed with database insertion
    if (empty($firstNameErr) && empty($lastNameErr) && empty($emailErr) && empty($usernameErr) && empty($passwordErr)) {
        // Check if username is already taken
        $check_query = "SELECT * FROM users WHERE Username = '" . mysqli_real_escape_string($conn, $username) . "'";
        $check_result = mysqli_query($conn, $check_query);

        if (mysqli_num_rows($check_result) > 0) {
            $usernameErr = "Username is already taken";
        } else {
            // Insert new user into database
            if (function_exists('password_hash')) {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            } else {
                // Fallback to crypt() if password_hash() is not available
                $salt = bin2hex(random_bytes(22)); // Generate a random salt
                $hashed_password = crypt($password, '$2a$12$' . $salt);
            }

            $insert_query = "INSERT INTO users (FirstName, LastName, Email, Username, Password) 
                             VALUES ('" . mysqli_real_escape_string($conn, $first_name) . "', 
                                     '" . mysqli_real_escape_string($conn, $last_name) . "', 
                                     '" . mysqli_real_escape_string($conn, $email) . "', 
                                     '" . mysqli_real_escape_string($conn, $username) . "', 
                                     '" . mysqli_real_escape_string($conn, $hashed_password) . "')";

            if (mysqli_query($conn, $insert_query)) {
                $signupSuccess = "Signup successful! You can now <a href='login.php'>login</a>.";
                // Optionally redirect or perform other actions after successful signup
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        }
    }
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup Page</title>
    <style>
        .error {color: #FF0000;}
        .success {color: #00FF00;}
    </style>
</head>
<body>

<h2>Signup</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="FirstName">First Name:</label>
    <input type="text" id="FirstName" name="FirstName" value="<?php echo isset($_POST['FirstName']) ? $_POST['FirstName'] : ''; ?>">
    <span class="error"><?php echo $firstNameErr; ?></span>

    <br>

    <label for="LastName">Last Name:</label>
    <input type="text" id="LastName" name="LastName" value="<?php echo isset($_POST['LastName']) ? $_POST['LastName'] : ''; ?>">
    <span class="error"><?php echo $lastNameErr; ?></span>

    <br>

    <label for="Email">Email:</label>
    <input type="email" id="Email" name="Email" value="<?php echo isset($_POST['Email']) ? $_POST['Email'] : ''; ?>">
    <span class="error"><?php echo $emailErr; ?></span>

    <br>

    <label for="Username">Username:</label>
    <input type="text" id="Username" name="Username" value="<?php echo isset($_POST['Username']) ? $_POST['Username'] : ''; ?>">
    <span class="error"><?php echo $usernameErr; ?></span>

    <br>

    <label for="Password">Password:</label>
    <input type="password" id="Password" name="Password">
    <span class="error"><?php echo $passwordErr; ?></span>

    <br>

    <input type="submit" value="Signup">
</form>

<span class="success"><?php echo $signupSuccess; ?></span>

</body>
</html>

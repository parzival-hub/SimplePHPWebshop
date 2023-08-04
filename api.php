<?php
include 'functions.php';
session_start();

//Start admin area
if ($_SESSION["role"] === 'admin') {

    // Hinzufügen von Produkten
    if (isset($_POST["addProduct"])) {
        $name = sanitize_input($_POST["name"]);
        $desc = sanitize_input($_POST["description"]);
        $quantity = sanitize_input($_POST["quantity"]);
        $image_path = basename(sanitize_input($_POST["image"]));

        if (!empty($name) && !empty($desc) && !empty($quantity) && !empty($image_path)) {
            if (is_numeric($quantity) && $quantity >= 0) {
                addProduct($name, $desc, $quantity, "/webshop/uploads/" . $image_path);
            } else {
                $_SESSION["adderror"] = "Invalid Quantity.";
            }
        } else {
            $_SESSION["adderror"] = "Please fill out all fields.";
        }
        header("Location:admin_products.php");
        exit();
    } // Löschen von Produkten
    else if (isset($_POST["delete"])) {
        deleteProduct(sanitize_input($_POST["delete"]));
        header("Location:admin_products.php");
        exit();
    } else if (isset($_POST["restock"])) {
        restock();
        header("Location:admin_products.php");
        exit();
    } else if (isset($_FILES["fileToUpload"])) {
        uploadFile($_FILES["fileToUpload"]);
        header("Location:admin_products.php");
        exit();
    } // Löschen von Uploads
    else if (isset($_POST["delete_image"])) {
        deleteImage(sanitize_input($_POST["delete_image"]));
        header("Location:admin_products.php");
        exit();
    }

    // Hinzufügen von Benutzern
    if (isset($_POST["addUser"])) {
        $unsafe_username = $_POST["username"];
        $unsafe_email = $_POST["email"];

        //Wird später gehasht
        $password = $_POST["password"];
        $role = sanitize_input($_POST["role"]);
        $username = sanitize_input($unsafe_username);
        $email = sanitize_input($unsafe_email);

        if ($unsafe_email != $email) {
            $error = "Email contains unallowed characters.";
        }

        if ($unsafe_username != $username) {
            $error = "Username contains unallowed characters.";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Incorrect Email format.";
        }

        if (!in_array($role, ["user", "admin"])) {
            $error = "Incorrect role.";
        }

        if (empty($error)) {
            create_user($username, $password, $email, $role);
            header("Location:admin_users.php");
            exit();
        } else {
            $_SESSION["error"] = $error;
            header("Location:admin_users.php");
            exit();
        }
    } // Löschen von Benutzer
    else if (isset($_POST["delete_id"])) {
        deleteUser(sanitize_input($_POST["delete_id"]));
        header("Location:admin_users.php");
        exit();
    }
} //Ende Admin Area

//Nicht admin, aber logged in
if (isset($_SESSION["role"])) {

    if (isset($_POST["in_cart"])) {
        if (isset($_POST["product_id"])) {
            $product_id = sanitize_input($_POST["product_id"]);
            deleteProductCart($product_id);
            unset($_POST["delete"]);
            header("Location:cart.php");
            exit();
        } else if (isset($_POST["buy"])) {
            buyCart();
            header('Location: thanks_for_buying.php', true, 301);
            exit();
        }
    }

    if (isset($_POST["add_to_cart"]) && isset($_POST["product_id"])) {
        if (isset($_SESSION["user_id"])) {
            $product_id = sanitize_input($_POST["product_id"]);
            $quantity = sanitize_input($_POST["quantity"]);
            if (is_numeric($quantity) && $quantity > 0) {
                addToCart($product_id, $quantity);
                header("Location: cart.php");
                exit();
            }
        } else {
            header("Location: login.php", true, 302);
            exit();
        }

    }

    //Change user data
    if (isset($_POST["change_user"])) {
        if (isset($_POST["email"])) {
            $email = sanitize_input($_POST["email"]);
            changeUser($email, "email");
        } else if (isset($_POST["username"])) {
            $username = sanitize_input($_POST["username"]);
            if (userExists($username)) {
                $_SESSION["error"] = "This username is taken";
                header("Location:user_profile.php");
                exit();
            }
            changeUser($username, "username");
            $_SESSION["username"] = $username;
        } else if (isset($_POST["password"])) {
            changeUser($_POST["password"], "password");
        }
        header("Location:user_profile.php");
        exit();
    }
} //Ende Logged in

// NICHT logged in
if (!isset($_SESSION["role"])) {

    //Login User
    if (isset($_POST["username"]) && isset($_POST["password"])) {
        $pass = $_POST["password"];
        $name = sanitize_input($_POST["username"]);

        if (empty($name) || empty($pass)) {
            $_SESSION["error"] = "Please fill out all fields";
            header("Location: login.php");
            exit();
        }

        $user = loginUser($name, $pass);
        if ($user !== "None") {
            $_SESSION["username"] = $user["username"];
            $_SESSION["role"] = $user["role"];
            $_SESSION["user_id"] = $user["id"];
            header("Location: index.php");
            exit();
        } else {
            $_SESSION["error"] = "Username or password do not match our records";
            header("Location: login.php");
            exit();
        }

    }

    //Create user
    if (isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["password2"]) && isset($_POST["email"])) {

        if (empty($_POST["username"]) && empty($_POST["password"]) && empty($_POST["password2"]) && empty($_POST["email"])) {
            $_SESSION["error"] = "Please fill out all fields.";
            header("Location:register.php");
            exit();
        }

        $unsafe_username = $_POST["username"];
        $unsafe_email = $_POST["email"];
        //Wird später gehasht
        $password = $_POST["password"];
        $password2 = $_POST["password2"];

        $username = sanitize_input($unsafe_username);
        $email = sanitize_input($unsafe_email);

        if ($unsafe_email != $email) {
            $error = "Email contains unallowed characters.";
        }

        if ($unsafe_username != $username) {
            $error = "Username contains unallowed characters.";
        }

        if ($password != $password2) {
            $error = "Passwords do not match.";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Incorrect E-Mail format.";
        }

        if (empty($error)) {
            create_user($username, $password, $email, "user");
            header("Location:index.php");
            exit();
        }
    }
}

if (isset($_POST["solution"]) && isset($_POST["id"])) {
    solve_challenge(sanitize_input($_POST["id"]), hash_hmac("sha512", $_POST["solution"], "FJk!br!5"));
}

if (isset($_POST["reset"])) {
    reset_challenges();
}

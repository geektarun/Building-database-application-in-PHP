<?php

if ( isset($_POST['logout'] ) ) {
    header("Location: index.php");
    return;
}

$salt = 'XyZzy12*_';
$stored_hash= '1a52e17fa899cf40fb04cfc42e6352f1'; // Pw is php123

$failure = false;  // If we have no POST data

if ( isset($_POST['who']) && isset($_POST['pass']) ) 
{
    if ( strlen($_POST['who']) < 1 || strlen($_POST['pass']) < 1 ) 
    {
        $failure = "Email and password are required";
    } 
    else 
    {
        $pass = htmlentities($_POST['pass']);
        $email = htmlentities($_POST['who']);

        if ((strpos($email, '@') === false)) 
        {
            $failure = "Email must have an at-sign (@)";
        }
        else
        {
            $check = hash('md5', $salt.$pass);
            if ( $check == $stored_hash ) 
            {
                // Redirect the browser to autos.php
                error_log("Login success ".$email);
                header("Location: autos.php?name=".urlencode($email));
                return;
            } 
            else 
            {
                error_log("Login fail ".$pass." $check");
                $failure = "Incorrect password";
            }
        }
    }
}

// Fall through into the View
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Login Page 537a67c4</title>
    </head>
    <body>
        <div>
            <h1>Please Log In</h1>
                <?php
                    
                    if ( $failure !== false ) 
                    {
                        echo(
                            '<p>'.
                                htmlentities($failure).
                            "</p>\n"
                        );
                    }
                ?>
            <form method="post">
                <div>
                    <label for="email">Email:</label>
                    <div>
                        <input type="text" name="who" id="email">
                    </div>
                </div>
                <div >
                    <label  for="pass">Password:</label>
                    <div >
                        <input  type="password" name="pass" id="pass">
                    </div>
                </div>
                <div >
                    <div >
                        <input type="submit" value="Log In">
                        <input type="submit" name="logout" value="Cancel">
                    </div>
                </div>
            </form>
            <p>
                For a password hint, view source and find a password hint
                in the HTML comments.
                
            </p>
        </div>
    </body>
</html>
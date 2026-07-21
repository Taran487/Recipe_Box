<?php
// $hash = password_hash('taran@123$',PASSWORD_DEFAULT); // hash function
//     echo'<br />' .$hash;
session_start(); //START SESSION
$isValid = true;
$fieldMessage = "";
$usernameError = "";
$passwordError = "";
$errorMessage = "";
$username = "";

//IF USER CHECKED REMEMBER ME BOX THEN COOKIE IS SAVED
if(isset($_COOKIE['remember_username'])){
    $username = $_COOKIE['remember_username'];
}

//PRESERVE THE USERNAME AFTER A FAILED LOGIN ATTEMPT
if(isset($_POST['username']))
    {
        $username = $_POST['username'];
    }

/** Firstly check if user already logged in & did not logged out it will directly redirect him to DASHBOARD  */
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

/** LOGIN FORM SUBMISSION */
if(isset($_POST['username']) && isset($_POST['password'])) // This line Checks if the user submitted the login form 
    { 
        if(count($_POST) > 0)
            {
                if(empty($_POST['username']) && empty($_POST['password']))
                    {
                        $isValid = false;
                       $fieldMessage = "Username and Password are required";
                    }
                elseif(empty($_POST['username']) )
                    {
                        $isValid = false;
                        $usernameError = "Username is required";
                    }
                elseif(empty($_POST['password']))
                    {
                        $isValid = false;
                        $passwordError = "Password is required";
                    }
                if($isValid == true)
                    {
                        include 'connection.php'; //INCLUDED DATABASE CONNECTION FILE

                        $sql = "SELECT user_id, username, password_hash FROM users WHERE username = ?";

                        /** PREPARE SQL QUERY TEMPLATE */
                        if($stmt = $conn->prepare($sql))
                            {
                                /* BIND PARAMETER TO SQL STATEMENT */
                                $stmt->bind_param("s", $_POST['username']);// Now php replaces ? with the user's username AND here (s) means string is the data type of variable username

                                $stmt->execute();//Now MySQL actually execute the statement.

                                $result = $stmt->get_result();// The result is received from db and stored in variable $result.

                                $user = $result->fetch_assoc();// Fetch the data row as an associative array

                                if(empty($user)) //Check if the associative array is empty means username does not exist.
                                    {
                                        $isValid = false;
                                        $errorMessage = "Invalid username or password";//Define error message
                                    }
                                else
                                    {   

                                        $isPasswordVarified = password_verify($_POST['password'],$user['password_hash']);//Use Built-in function to verify if entered password matches hash password in DB

                                        if($isPasswordVarified == true) // if entered password is true
                                            {
                                                // stored id and username in global $_SESSION array
                                                $_SESSION['user_id']=$user['user_id']; 
                                                $_SESSION['username']=$user['username'];

                                                //Remember Me
                                                if(isset($_POST['remember']))
                                                    {   
                                                        //if user checked remember me then cookie saved for 30 days
                                                        setcookie("remember_username", $user['username'], time() + (60 * 60 * 24 * 30), "/");
                                                    }
                                                else
                                                    {   
                                                        //if user does not check the remember me box then user name will not be prefilled there
                                                        setcookie("remember_username", "", time()-3600, "/");
                                                    }

                                                header("Location: dashboard.php"); //Redirect to dashboard page
                                                exit;
                                                
                                            }
                                        else
                                            {
                                                $isValid = false;
                                                $errorMessage = "invalid username or password"; //Define error message
                                            }
                                    }
                            } 
                        else
                            {
                                echo'<br />';
                                echo "ERROR:" .$sql."<br>".$conn->error;
                            }
                    }
            }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Login</title>
    </head>
    <body>
        <h1>Login</h1>

        <?PHP 
        if($isValid == false){
        }
        ?>

        <div style="color:blue;">
            <?php echo $fieldMessage; ?>
            <?php echo $usernameError; ?>
            <?php echo $passwordError; ?>
        </div>

        <div style="color:red;">
            <?php echo $errorMessage; ?>
        </div>

        <div>
            <form action="" method="post">
                <div>
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>">
                </div>

                <div>
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password">
                </div>
                <div>
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Remember Me</label>
                </div>

                <button type="submit">Submit</button>
            </form>
        </div>
    </body>
</html>
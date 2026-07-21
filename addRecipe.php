<?php 
session_start();
if(!isset($_SESSION['user_id'])){
    echo 'No authenticated session exists';
    header('Location: login.php');
    exit;
}
include 'connection.php';

$message = "";

//INSERT RECIPE
if(isset($_POST['title']) && isset($_POST['ingredients']) && isset($_POST['instructions']))
    {
        if(empty($_POST['title']) || empty($_POST['ingredients']) || empty($_POST['instructions']))
            {
                $message = "All fields are required";
            }
        else
            {
                $user_id = $_SESSION['user_id'];
                $category_id = $_POST['category_id'];
                $title = $_POST['title'];
                $ingredients = $_POST['ingredients'];
                $instructions = $_POST['instructions'];

                //INSERT RECIPE INTO TABLE IN DB
                $sql =  "INSERT INTO recipes(user_id, category_id, title, ingredients, instructions)
                                        VALUES (?, ?, ?, ?, ?)";
                
                /** PREPARE SQL QUERY */
                $stmt = $conn->prepare($sql);

                $stmt->bind_param("iisss", $user_id, $category_id, $title, $ingredients, $instructions);
                if($stmt->execute()){
                    $message ="New recipe added successfully";
                }
                else{
                    $message = "Error:".$conn->error;
                }
               
            }
    }


            //SELECT CATEGORIES FROM DB
            $sql =  "SELECT category_id,category_name FROM categories";
            
            /** PREPARE SQL QUERY */
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();
 ?>

<!DOCTYPE html>
<html>
<body style="background-color:#FFF8F0">
    <div class="main"  >

        <!-- BACK NAVIGATION LINK-->    
        <a href="dashboard.php" style="color:black; font-weight:bold; font-size:15px; text-decoration:none; margin-left:20px;">Back</a>
        
        <!-- LOGOUT BUTTON -->
        <form action="logout.php" style="position: absolute; top:2px; right:2px;">
            <br />
            <br />
            <button type="submit" style="border-radius: 9px; background-color:#dc3545; color:white; width:100px; height:40px; font-weight:bold; font-size:16px; border:none; cursor:pointer;">Logout</button>
        </form>
        
        <h1 style="color: #D35400; text-align:center;">Add Your Recipe</h1>
        
        <div style="color: green; font-weight:bold;">
            <?php echo $message; ?>
        </div>
        
        <!-- ADD RECIPE FORM-->
        <form action="" method="post" style="text-align:center;"> 
            <label style="color: #2C3E50;">Category</label><br>

            <select name="category_id" style="width: 200px; height:40px; text-align:center;">
                <?php 
                
                    while($row = $result->fetch_assoc())
                        {
                            echo "<option value = '".$row['category_id']."'>";
                            echo htmlspecialchars($row['category_name']);
                            echo "</option>";
                        }

                ?>
            </select>
            <br>
            <br>
            <label style="color: #2C3E50;">Recipe Title</label><br>
            <input type="text" name="title" style="width: 250px; height:40px; text-align:center; " >
            <br> 
            <br>
            <label style="color: #2C3E50;">ingredients</label><br>
            <textarea name="ingredients" rows="5" cols="50"></textarea>
            <br> 
            <br>
            <label style="color: #2C3E50;">Instructions</label><br>
            <textarea name="instructions" rows="5" cols="50"></textarea>
            <br>
            <br>
            <button type="submit" style = "background-color:#27AE60; color:white; border:none; width:140px; height:45px; font-weight:bold; font-size:16px; cursor:pointer;border-radius:8px;">Add Recipe</button>
        </form>

        

    </div>
</body>
</html>
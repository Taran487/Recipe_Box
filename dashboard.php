<?php 
session_start();
if(!isset($_SESSION['user_id'])){
    echo 'No authenticated session exists';
    header('Location: login.php');
    exit;
}
 ?>

<!DOCTYPE html>
<html>
<body style="background-color:#FFF8F0">
    <div class="main">

        <h1 style="color: #2E7D32; text-align:center;">Welcome, <?php echo htmlspecialchars($_SESSION['username'])  ?> ! </h1>
        
        <h2 style="color: #D35400;">Find Your Recipes Here</h2>
        
        <a href="addRecipe.php" style="color:green; font-weight:bold; font-size:22px; text-decoration:none;">Add New Recipe</a>
        
        <?php
            include 'connection.php';
            $user_id = $_SESSION['user_id']; 
            
            //GET DATA FROM THREE DIFF. TABLES RECIPES, CATEGORIES, USERS FROM DB
            $sql =  "SELECT users.username, 
                    recipes.title,
                    recipes.ingredients,
                    recipes.instructions,
                    categories.category_name
                    FROM recipes
                    JOIN categories
                    ON recipes.category_id = categories.category_id
                    JOIN users
                    ON recipes.user_id = users.user_id
                    WHERE recipes.user_id = ? ";
            
            /** PREPARE SQL QUERY */
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();

            while($row = $result->fetch_assoc())
                {
                    echo "<div style='background:wheat; padding:15px; margin:10px; border-radius:10px; margin-bottom:2px;'>";
                    
                    echo "Created by: ".htmlspecialchars($row['username']);


                    echo "<h3>".htmlspecialchars($row['title'])."</h3>";

                    echo "<p style='color: #D35400;'><strong>Category:</strong>".htmlspecialchars($row['category_name'])."</p>";

                    echo "<p style='color: #2C3E50;'><strong style='color: #D35400;'>Ingredients:</strong><br>".nl2br(htmlspecialchars($row['ingredients']))."</p>";

                    echo "<p style='color: #2C3E50;'><strong style='color: #D35400;'>Instructions:</strong><br>".nl2br(htmlspecialchars($row['instructions']))."</p>";

                    echo "</div>";
                }

        ?>

        <!-- LOGOUT BUTTON -->
        <form action="logout.php" style="position: absolute; top:2px; right:2px;">
            <br />
            <br />
            <button type="submit" style="border-radius: 9px; background-color:#dc3545; color:white; width:100px; height:40px; font-weight:bold; font-size:16px; border:none; cursor:pointer;">Logout</button>
        </form>

    </div>
</body>
</html>
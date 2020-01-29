<html>
<head>
  <title>Create a Post</title>
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
</head>
<body>
  <h1>Yangjun Bie's Web Journal</h1>
  <div class="create-post">
    <h2>Create a Post</h2>
    
    <form action="" method="post">
      <label for="title">Title</label>
      <input name="title"></input>
      <label for="body">Post Body</label>
      <textarea name="body"></textarea>
      <label for="password">Secret Password</label>
      <input type="password" name="password"></input>
      <input type="submit" name="submit" value="Create Post"></input>
    </form>
    <?php
      //echo password_hash("webdevelopment", PASSWORD_DEFAULT);
      $hash = '$2y$10$WIhcAjP7FtUAGwDJG6909uuCpZFWvgwiYBaHOPe/Qd7NPg0EFS9rK';
    /* Attempt MySQL server connection. Assuming you are running MySQL
    server with default setting (user 'root' with no password) */
      if(isset($_POST["submit"])){
        $servername = "localhost";
        $username = "root";
        $password = "root";
        $dbname = "exercise3";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $title = $_POST["title"];
        $slug = strtolower($title);
        $slug = preg_replace("/[^a-z0-9_\s-]/", "", $slug);
        $slug = preg_replace("/[\s-]+/", " ", $slug);
        $slug = preg_replace("/[\s_]/", "_", $slug);
        $body = $_POST["body"];

        $sql = "INSERT INTO posts (id, slug, title, body)
                VALUES (NULL,'$slug','$title','$body')";
        
        if(password_verify($_POST["password"], $hash)){
          if($conn->query($sql) === true){
            echo "Posts inserted successfully.";
          } else{
            echo "ERROR: Could not able to execute $sql. " . $mysqli->error;
          }
        } else {
          echo "Error: Password not right!";
        }
      }
    ?>
  </div>
</body>
</html>

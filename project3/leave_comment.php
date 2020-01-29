<html>
<head>
  <title>Leave a Comment</title>
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
</head>
<body>


  <h1>Yangjun Bie's Web Journal</h1>
  <?php
    $servername = "localhost";
    $username = "root";
    $password = "root";
    $dbname = "exercise3";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $curr_id = $_GET['post_id'];
    $first_sql = "SELECT posts.slug, posts.title, posts.body AS post_body, comments.id, comments.body AS comment_body, comments.author 
                FROM posts LEFT JOIN comments ON posts.id=comments.post_id WHERE posts.id=$curr_id ORDER BY comments.id";
    $result = $conn->query($first_sql);
    $row = $result->fetch_assoc();
  ?>
  <div class="leave-comment">
    <h2>
      Leave a Comment on
      <a href="weblog.php#<?php echo $rows["slug"] ?>"><?php echo $rows["title"] ?></a>
    </h2>
      
    <div class="post-body">
      <?php echo $rows["post_body"] ?>
    </div>
    <?php
      if ($row["comment_body"]==NULL)
        $num_of_comments = 0;
      else
        $num_of_comments = $result->num_rows;
    ?>
    <h3><?php echo $num_of_comments ?> Comments</h3>
    <div class="comment-block">
    <?php
      if ($num_of_comments > 0) {?>
        <div class="comment">
          <div class="comment-body">
            <?php echo $row["comment_body"] ?>
          </div>
          <div class="comment-author">
            <?php echo $row["author"] ?>
          </div>
        </div>
        <?php
        while($row = $result->fetch_assoc()) {?>
            <div class="comment">
              <div class="comment-body">
                <?php echo $row["comment_body"] ?>
              </div>
              <div class="comment-author">
                <?php echo $row["author"] ?>
              </div>
            </div>
        <?php }
      } 
    ?>
    </div>


    <form method="post">
      <label for="body">Comment</label>
      <textarea name="body"></textarea>
      <label for="name">Your name</label>
      <input name="name"></input>
      <input type="hidden" name="post_id" value=<?php echo $curr_id ?>></input>
      <input type="submit" name="submit" value="Leave Comment"></input>
    </form>
    <?php
      if(isset($_POST["submit"])){
        $body = $_POST["body"];
        $author = $_POST["name"];
        $post_id = $curr_id;

        $sql = "INSERT INTO comments (id, post_id, body, author)
                VALUES (NULL,'$post_id','$body','$author')";
        
        if($conn->query($sql) === true){
          echo "Comments inserted successfully.";
        } else{
          echo "ERROR: Could not able to execute $sql. " . $mysqli->error;
        }
        header('Location: '.$_SERVER['REQUEST_URI']);
      }
    ?>

  </div>
</body>
</html>

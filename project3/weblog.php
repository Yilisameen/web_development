<html>
<head>
  <title>Exercise 3 - A Web Journal</title>
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
</head>
<body>
  <div class="compose-button">
    <a href="create_post.php" title="create post">
      <i class="material-icons">create</i>
    </a>
  </div>

  <h1>Yangjun Bie's Web Journal</h1>
  
  <?php
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
    // echo "Connected successfully";

    $sql = "SELECT posts.id AS id, posts.slug, posts.title, posts.body AS post_body, comments.id AS comments_id, comments.body AS comment_body, comments.author FROM posts LEFT JOIN comments ON posts.id=comments.post_id ORDER BY posts.id DESC, comments.id";
    $result = $conn->query($sql);
  ?>

  <div id="posts">
    <?php
      $curr_post_id = -1;
      $comment_arr = array();
      $allcomments = array();
    ?>
    <?php
      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          if ($row["id"] != $curr_post_id) {
            if(count($allcomments) > 0) {?>
              <h3><?php echo count($allcomments)?> Comments</h3>
              <div class="comment-block">
    <?php
                foreach ($allcomments as $comment) {?>
                  <div class="comment">
                    <div class="comment-body">
                      <?php echo htmlspecialchars($comment["body"]) ?>
                    </div>
                    <div class="comment-author">
                      <?php echo htmlspecialchars($comment["author"]) ?> 
                    </div>
                  </div>
    <?php              
                }?>
                <a href="leave_comment.php?post_id=<?php echo $curr_post_id ?>">
                  <i class="material-icons">create</i>
                  Leave a comment
                </a>
              </div>
    <?php
            }
            //no comments
            if ($row["comment_body"]==NULL) {?>
              <div class="post" id=<?php echo $row["id"]?>>
                <h2 class=post-title id=<?php echo $row["slug"]?>>
                  <?php echo htmlspecialchars($row["title"]) ?>
                  <a href="#<?php echo $row["slug"]?>">
                    <i class="material-icons">link</i>
                  </a>
                </h2>
                <div class="post-body">
                  <?php echo htmlspecialchars($row["post_body"]) ?>
                </div>
                <h3>0 Comments</h3>
                <div class="comment-block">
                  <a href="leave_comment.php?post_id=<?php echo $row["id"]?>">
                    <i class="material-icons">create</i>
                    Leave a comment
                  </a>
                </div>
              </div>
    <?php
            }
            //have comments
            else {?>
              <div class="post" id=<?php echo $row["id"]?>>
                <h2 class=post-title id=<?php echo $row["slug"]?>>
                    <?php echo htmlspecialchars($row["title"]) ?>
                    <a href="#<?php echo $row["slug"]?>">
                      <i class="material-icons">link</i>
                    </a>
                </h2>
                <div class="post-body">
                  <?php echo htmlspecialchars($row["post_body"]) ?>
                </div>
              </div>
    <?php
              $allcomments = array();
              $comment_arr = array();
              $comment_arr['body'] = $row["comment_body"];
              $comment_arr['author'] = $row["author"];
              $allcomments[] = $comment_arr; 
              $curr_post_id = $row["id"];
            }
          }
          else {
            $comment_arr = array();
            $comment_arr['body'] = $row["comment_body"];
            $comment_arr['author'] = $row["author"];
            $allcomments[] = $comment_arr; 
          }
        }
        if(count($allcomments) > 0) {?>
          <h3><?php echo count($allcomments)?> Comments</h3>
          <div class="comment-block">
<?php
            foreach ($allcomments as $comment) {?>
              <div class="comment">
                <div class="comment-body">
                  <?php echo htmlspecialchars($comment["body"]) ?>
                </div>
                <div class="comment-author">
                  <?php echo htmlspecialchars($comment["author"]) ?> 
                </div>
              </div>
<?php              
            }?>
            <a href="leave_comment.php?post_id=<?php echo $curr_post_id ?>">
              <i class="material-icons">create</i>
              Leave a comment
            </a>
          </div>
<?php
        }
      }
      else {
        echo "0 results";
      }
    ?>
  </div>
</body>
</html>
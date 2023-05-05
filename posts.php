<?php 

    include "connection.php";
    session_start();
    // avoid location error
    ob_start();

    $user_id = $_SESSION['user_id'];
    $account_id = $_SESSION["account_id"];
    $uname = $_SESSION['uname'];
    $role = $_SESSION['role'];

    function getPosts($account_id) {
        $conn = connection();
        $sql = "SELECT posts.post_id, posts.post_title, categories.category_name, posts.date_posted, posts.post_message
                FROM posts INNER JOIN categories ON posts.category_id = categories.category_id 
                WHERE posts.account_id = '$account_id' ORDER BY posts.post_id";

        if ($result = $conn->query($sql)){
            return $result;
        } else{
            die('Error :'. $conn->error);
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Post</title>
</head>
<body>

    <?php 
        if ($role =="A") {
            include "admin-menu.php";
            createNavbar($uname);
        } else {
            include "user-menu.php";
            createNavbar($uname);
        } 
    ?>

    <!-- success=green, primary=blue warning=yellow danger=red info=cyan secondary=gray-->
    <div class="p-3 bg-primary text-white">
        <i class="fa-solid fa-pen-nib display-3"></i>
        <span class="lead display-3">Post</span>
    </div>

    <div class="container">
        <br>
        <div class="text-end">
            <a href="add-post.php" class="mt-5 mb-3 btn btn-outline-dark">
                <i class="fa-solid fa-pen-to-square"></i> Add Post
            </a>
        </div>
        
        
        <table class="table table-striped table-sm text-center">
            <thead class="table-dark">
                <td>Post ID</td>
                <td>Title</td>
                <td>Category</td>
                <td>Date Posted</td>
                <td></td>
            </thead>
            <tbody>
                <?php 
                    $datail_All_list = [];
                    $alldata = getPosts($account_id); 
                    while ($row_list = $alldata->fetch_assoc()): 
                        $detail_list = [] ;?>
                        <tr>
                            <td><?= $row_list['post_id']?></td>
                            <td><?= $row_list['post_title']?></td>
                            <td><?= $row_list['category_name']?></td>
                            <td><?= $row_list['date_posted']?></td>
                            <form action="" method="post">
                                <td>
                                    <button type="submit" name="detail" value="<?= $row_list['post_id']?>" class="btn btn-outline-dark pt-1 pb-1">Details</button>
                                </td>
                            </form>
                        </tr>
                        
                <?php 
                        $detail_list = [$row_list['post_title'], $uname, $row_list['date_posted'], $row_list['category_name'], $row_list['post_message']];
                        $detail_All_list[$row_list['post_id']] = $detail_list;
                    endwhile; ?>
            </tbody>
        </table>
    </div>
    
</body>
</html>

<?php 
    if (isset($_POST["detail"])) {
        $post_id = $_POST["detail"];
        $_SESSION["post_id"] = $post_id;
        $_SESSION["datails_list"] = $detail_All_list[$post_id];
        header("location:post-details.php");
    }
?>
<?php 

    include "connection.php";
    session_start();
    // avoid location error
    ob_start();

    $uname = $_SESSION['uname'];
    // $uname ="Dai";
    // $id = "1";
    // $title = "sample";
    // $category = "python";
    // $date = "2022-03-28";
    // $row_list = [$id, $title, $category, $date_posted, ""];

    function getPosts() {
        $conn = connection();
        $sql = "SELECT posts.post_id, posts.post_title, posts.date_posted, categories.category_name, posts.post_message
                FROM posts INNER JOIN categories ON posts.category_id = categories.category_id ORDER BY posts.post_id";
        if ($result = $conn->query($sql)){
            return $result;
        } else{
            die('Error :'. $conn->error);
        }
    }

    function getNumbers() {
        $conn = connection();
        $sql = "SELECT * FROM posts";
        $result = $conn->query($sql);
        $posts_No = $result->num_rows;

        $sql = "SELECT * FROM categories";
        $result = $conn->query($sql);
        $categories_No = $result->num_rows;

        $sql = "SELECT * FROM users";
        $result = $conn->query($sql);
        $users_No = $result->num_rows;

        $No_List = [$posts_No, $categories_No, $users_No];
        return $No_List;
    }

    $No_List = getNumbers();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Dashboard</title>
</head>
<body>

    <?php 
        include "admin-menu.php";
        createNavbar($uname);
    ?>

    <!-- success=green, primary=blue warning=yellow danger=red info=cyan secondary=gray-->
    <div class="p-3 bg-danger text-white">
        <i class="fa-solid fa-user-gear display-3"></i>
        <span class="lead display-3">Dashboard</span>
    </div>

    <br>

    <div class="container w-75 mx-auto">
        <div class="row">
            <div class="col-8 ">
                <div class="row mb-3">
                    <div class="col">
                        <a href="add-post.php" class="btn btn-primary form-control">
                            <i class="fa-solid fa-circle-plus"></i> Add Post
                        </a>
                        <!-- <button class="btn btn-primary form-control" type="submit">
                        <i class="fa-solid fa-circle-plus"></i> Add Post
                        </button> -->
                    </div>
                    <div class="col">
                        <a href="categories.php" class="btn btn-success form-control">
                            <i class="fa-solid fa-folder-plus"></i> Add Category
                        </a>
                        <!-- <button class="btn btn-success form-control" type="submit">
                        <i class="fa-solid fa-folder-plus"></i> Add Category
                        </button> -->
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col form-control border-0">
                        <form action="" method="post">
                            <table class="table table-striped">
                                <thead class="table-dark">
                                    <td>#</td>
                                    <td>Title</td>
                                    <td>Category</td>
                                    <td>Date Posted</td>
                                    <td></td>
                                </thead>
                                <tbody class="table table-striped">
                                    <?php 
                                        $datail_All_list = [];
                                        $alldata = getPosts(); 
                                        while ($row_list = $alldata->fetch_assoc()): 
                                            $detail_list = []?>
                                            <tr>
                                                <td><?= $row_list['post_id']?></td>
                                                <td><?= $row_list['post_title']?></td>
                                                <td><?= $row_list['category_name']?></td>
                                                <td>
                                                    <?= $row_list['date_posted']?>
                                                </td>
                                                <td>
                                                    <button type="submit" name="detail" value="<?= $row_list['post_id']?>" class="btn btn-outline-dark pt-1 pb-1">Details</button>
                                                </td>
                                            </tr>
                                    <?php 
                                            $detail_list = [$row_list['post_title'], $uname, $row_list['date_posted'], $row_list['category_name'], $row_list['post_message']];
                                            $detail_All_list[$row_list['post_id']] = $detail_list;
                                        endwhile; ?>
                                </tbody>
                            </table>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-4">
                <div class="row mb-3">
                    <div class="col">
                        <a href="users.php" class="btn btn-warning text-white form-control">
                            <i class="fa-solid fa-user-plus"></i> Add User
                        </a>
                        <!-- <button class="btn btn-warning text-white form-control" type="submit">
                        <i class="fa-solid fa-user-plus"></i> Add User
                        </button> -->
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <div class="bg-primary text-center text-white form-control">
                            <p>Posts</p>
                            <p>
                                <i class="fa-solid fa-pencil"></i> <?= $No_List[0]?>
                            </p>
                            <a class="btn btn-primary border border-white pt-1 pb-1" href="posts.php"> VIEW</a>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <div class="bg-success text-center text-white form-control">
                            <p>Category</p>
                            <p>
                                <i class="fa-solid fa-folder"></i> <?= $No_List[1]?>
                            </p>
                            <a class="btn btn-success border border-white pt-1 pb-1" href="categories.php"> VIEW</a>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <div class="bg-warning text-center text-white form-control">
                            <p>users</p>
                            <p>
                                <i class="fa-solid fa-users"></i> <?= $No_List[2]?>
                            </p>
                            <a class="btn btn-warning border border-white text-white pt-1 pb-1" href="users.php"> VIEW</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
    
</body>
</html>

<?php 
    if (isset($_POST["detail"])) {
        $post_id = $_POST["detail"];
        $_SESSION["post_id"] = $post_id;
        $selected_datails_list = $detail_All_list[$post_id];

        function getDetailuname($post_id) {
            $conn = connection();
            $sql = "SELECT accounts.username FROM accounts INNER JOIN posts ON posts.account_id = accounts.account_id 
                    WHERE post_id = '$post_id'";
            $result = $conn->query($sql);
            $result2 = $result->fetch_assoc();
            return $result2["username"];
            // $detail_list = [$row_list['post_title'], $uname, $row_list['date_posted'], $row_list['category_name'], $row_list['post_message']];
        }
    
        $selected_datails_list[1] = getDetailuname($post_id);

        $_SESSION["datails_list"] = $selected_datails_list;

        header("location:post-details.php");
    }
?>
<?php  
    include "connection.php";
    session_start();
    // avoid location error
    ob_start();

    $post_id = $_SESSION['post_id'];
    $user_id = $_SESSION['user_id'];
    $account_id = $_SESSION["account_id"];
    $uname = $_SESSION['uname'];
    $role = $_SESSION['role'];

    // Array ( [0] => post_title [1] => uname [2] => date_posted [3] => category_name [4] => post-message )
    $datails_list = $_SESSION["datails_list"];

    $datails_list[2] = date("Y-m-d", strtotime($datails_list[2]));

    function getCategories() {
        $conn = connection();
        $sql = "SELECT * FROM categories";
        if ($result = $conn->query($sql)){
            return $result;
        } else{
            die('Error :'. $conn->error);
        }
    }

    function getAccounts() {
        $conn = connection();
        $sql = "SELECT * FROM accounts";
        if ($result = $conn->query($sql)){
            return $result;
        } else{
            die('Error :'. $conn->error);
        }
    }

    // $update_post_list = [$title, $message, $date, $category_id, $account_id];
    function updatePost($list, $post_id, $role) {
        $conn = connection();
        $sql = "UPDATE posts SET 
                post_title = '$list[0]',
                post_message = '$list[1]', 
                date_posted = '$list[2]',
                category_id = '$list[3]',
                account_id = '$list[4]'
                WHERE post_id = '$post_id'";
        if ($conn->query($sql)){
            if ($role =="A") {
                header("location:dashboard.php");
            } else {
                header("location:posts.php");
            } 
        } else{
            die('Error :'. $conn->error);
        }
    }

    function reset_auto_inclement() {
        // put auto_inclement in order
        $conn = connection();
        $sql0 = "SET @i = 0";
        $sql1 = "UPDATE posts SET post_id = (@i := @i + 1)";
        $conn->query($sql0);
        $conn->query($sql1);
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
    <title>Add-post</title>
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

    <div class="p-3 bg-primary text-white">
        <i class="fa-solid fa-pen-nib display-3"></i>
        <span class="lead display-3">Post</span>
    </div>
    <div class="container p-5">
        <form action="" method="post">
            <div class="card w-50 mx-auto">
                <div class="card-header">
                    <p class="text-center">
                        <i class="fa-regular fa-pen-to-square display-6"></i>
                        <span class="lead display-6">Update Post</span>
                    </p>
                </div>
            
                <div class="card-body">

                        <div class="mb-3">
                            <input type="text" name="title" id="title" class="form-control border-0 border-bottom rounded-0" value=<?= $datails_list[0]?>>
                        </div>

                        <div class="mb-3">
                            <input type="date" name="date" id="date" class="form-control border-0 border-bottom rounded-0" value=<?= $datails_list[2]?>>
                        </div>

                        <select name="category" id="" class="form-select mb-3 text-grey border-0 border-bottom rounded-0">
                            <option value="" hidden>CATEGORY</option>
                            <?php $alldata = getCategories(); 
                                while ($row_list = $alldata->fetch_assoc()): 
                                    if ($row_list['category_name'] == $datails_list[3]) { ?>
                                        <option value="<?= $row_list['category_id']?>" selected><?= $row_list['category_name']?></option>
                            <?php   } else {  ?>        
                                    <option value="<?= $row_list['category_id']?>"><?= $row_list['category_name']?></option>
                            <?php } 
                                endwhile; ?>
                            
                        </select>
                        
                        <div class="mb-3">
                            <textarea name="message" id="message" col="30" rows="5" class="form-control"><?= $datails_list[4]?></textarea>
                        </div>

                        <div class="mb-3">
                            <div class="input-group">
                                <span class="input-group-text">Author :</span>
                                <select name="accounts" id="" class="form-select">
                                    <?php $alldata = getAccounts(); 
                                        while ($row_list = $alldata->fetch_assoc()): 
                                            print_r($row_list);
                                            if ($row_list['username'] == $datails_list[1]) { ?>
                                                <option value="<?= $row_list['account_id']?>" selected><?= $row_list['username']?></option>
                                    <?php   } else {  ?>        
                                            <option value="<?= $row_list['account_id']?>"><?= $row_list['username']?></option>
                                    <?php } 
                                        endwhile; ?>
                                </select>
                            </div>
                        </div>
                        <!-- </div>
                            <input type="text" name="author" id="author" class="form-control border-0 border-bottom rounded-0">
                        </div> -->
                </div>

                <div class="card-footer">
                    <button type="submit" name="post" class="btn btn-primary px-5 text-white form-control">UPDATE</button>
                </div>
            </div>
        </form>
    </div>

</body>
</html>

<?php 
    if (isset($_POST["post"])) {
        $title = $_POST["title"];
        $date = $_POST["date"];
        $category_id = $_POST["category"];
        $message = $_POST["message"];
        $account_id = $_POST["accounts"];

        $update_post_list = [$title, $message, $date, $category_id, $account_id];
        updatePost($update_post_list, $post_id, $role);
        // reset_auto_inclement($account_id);
    }
?>
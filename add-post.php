<?php  
    include "connection.php";
    session_start();
    // avoid location error
    ob_start();

    $user_id = $_SESSION['user_id'];
    $account_id = $_SESSION["account_id"];
    $uname = $_SESSION['uname'];
    $role = $_SESSION['role'];

    function getCategories() {
        $conn = connection();
        $sql = "SELECT * FROM categories";
        if ($result = $conn->query($sql)){
            return $result;
        } else{
            die('Error :'. $conn->error);
        }
    }

    function addPost($list) {
        $conn = connection();
        $sql = "INSERT INTO posts (post_title, post_message, date_posted, category_id, account_id) 
                    VALUES ('$list[0]', '$list[1]', '$list[2]', '$list[3]', '$list[4]')";
        if ($conn->query($sql)){
            header("location:posts.php");
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
                        <span class="lead display-6">Add Post</span>
                    </p>
                </div>
            
                <div class="card-body">

                        <div class="mb-3">
                            <input type="text" name="title" id="title" class="form-control border-0 border-bottom rounded-0" placeholder="title">
                        </div>

                        <div class="mb-3">
                            <input type="date" name="date" id="date" class="form-control border-0 border-bottom rounded-0" placeholder="dd/mm/yyyy">
                        </div>

                        <select name="category" id="" class="form-select mb-3 text-grey border-0 border-bottom rounded-0">
                            <option value="" hidden>CATEGORY</option>
                            <?php $alldata = getCategories(); 
                                while ($row_list = $alldata->fetch_assoc()): ?>
                                    <option value="<?= $row_list['category_id']?>"><?= $row_list['category_name']?></option>
                            <?php endwhile; ?>
                            
                        </select>
                        
                        <div class="mb-3">
                            <textarea name="message" id="message" col="30" rows="5" placeholder="MASSAGE" class="form-control"></textarea>
                        </div>

                        <div class="mb-3">
                            <div class="input-group">
                                <span class="input-group-text">Author :</span>
                                <input type="text" name="author" id="author" class="form-control bg-white" value="<?= $uname?>" disabled>
                            </div>
                        </div>
                        <!-- </div>
                            <input type="text" name="author" id="author" class="form-control border-0 border-bottom rounded-0">
                        </div> -->
                </div>

                <div class="card-footer">
                    <button type="submit" name="post" class="btn btn-primary px-5 text-white form-control">POST</button>
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

        $add_post_list = [$title, $message, $date, $category_id, $account_id];
        addPost($add_post_list);
        reset_auto_inclement();
    }
?>
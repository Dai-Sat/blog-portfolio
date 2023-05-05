<?php  
    include "connection.php";
    session_start();
    // avoid location error
    ob_start();

    // $user_id = $_SESSION['user_id'];
    $post_id = $_SESSION["post_id"];
    $uname = $_SESSION['uname'];
    $role = $_SESSION['role'];
 
    $datails_list = $_SESSION["datails_list"];
    $datails_list[2] = date("F d, Y", strtotime($datails_list[2]));

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
    <div class="container p-5 w-50 mx-auto">
        <div class="row">
            <div class="col">
                <div class="">
                    <a href="posts.php" class="btn border border-0">
                        <h4><i class="fa-solid fa-chevron-left"></i></h4>
                    </a>
                </div>
            </div>

            <div class="col">
                <div class="text-end">
                    <a href="edit-post.php" class="btn border border-0">
                        <h4><i class="fa-solid fa-pen"></i> Edit</h4>
                    </a>
                </div>
            </div>

        </div>

        <div class="bg-light">
            <div class="p-3">
                <p class="display-5"><?= $datails_list[0] ?><p>
                <p class="">
                    <span> By : <?= $datails_list[1] ?> </span> &nbsp;
                    <span> <?= $datails_list[2] ?> </span> &nbsp;
                    <span> <?= $datails_list[3] ?> </span>
                <p>
                <p><?= $datails_list[4] ?><p>

            </div>
        </div>

    </div>

</body>
</html>


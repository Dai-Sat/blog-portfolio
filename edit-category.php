<?php  
    include "connection.php";
    session_start();
    // avoid location error
    ob_start();

    $uname = $_SESSION["uname"];

    function updateC_name($category_name, $category_id) {
        $conn = connection();
        $sql = "UPDATE categories SET category_name = '$category_name' WHERE category_id = '$category_id'";
        if ($conn->query($sql)){
            header("location:categories.php");
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

    <div class="p-3 bg-success text-white">
        <i class="fa-solid fa-folder display-3"></i>
        <span class="lead display-3">Category</span>
    </div>
    <div class="container p-5">
        <form action="" method="post">
            <div class="card w-50 mx-auto">
                <div class="card-header">
                    <p class="text-center">
                        <i class="fa-regular fa-pen-to-square display-6"></i>
                        <span class="lead display-6">Update Category</span>
                    </p>
                </div>
            
                <div class="card-body">
                    <div class="mb-3">
                        <input type="text" name="c_name" class="form-control border-0 border-bottom rounded-0" 
                               value= <?= $_SESSION["selected_category_name"]?> >
                    </div>
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
        $c_name = $_POST["c_name"];

        updateC_name($c_name, $_SESSION["selected_category_id"]);
    }
?>
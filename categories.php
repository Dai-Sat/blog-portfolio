<?php 

    include "connection.php";
    session_start();
    // avoid location error
    ob_start();

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

    function addCategory($category_name) {
        $conn = connection();
        $sql = "INSERT INTO categories (category_name) VALUES('$category_name')";
        if ($conn->query($sql)){
            header("refresh:0");
        } else{
            die('Error :'. $conn->error);
        }
    }

    function delete($category_id) {
        $conn = connection();
        $sql = "DELETE FROM categories WHERE category_id = '$category_id'";
        if ($conn->query($sql)){
            header("refresh:0");
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
    <title>Category</title>
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
    <div class="p-3 bg-success text-white">
        <i class="fa-solid fa-folder display-3"></i>
        <span class="lead display-3">Category</span>
    </div>


    <div class="container p-5">
        <form action="" method="post">
            <span class="lead">Add Category </span>
            <input type="text" name="category" id="" class="w-30 d-inline" placeholder="">
            <button type="submit" name="add" class="btn btn-success text-white mx-2 pt-1 pb-1">ADD</button>
        </form>
    
        <table class="table table-striped table-sm text-center mt-5">
            <thead class="table-dark text-uppercase">
                <td>Category ID</td>
                <td>Category Name</td>
                <td></td>
                <td></td>
            </thead>
            <tbody>
                <?php $alldata = getCategories(); 
                    while ($row_list = $alldata->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row_list['category_id']?></td>
                            <td><?= $row_list['category_name']?></td>
                            <form action="" method="post">
                                <td>
                                    <button type="submit" name="update" value="<?= $row_list['category_id']?>" class="btn btn-warning text-white d-block mx-auto pt-1 pb-1">Update</button>
                                </td>
                                <td>
                                    <button type="submit" name="delete" value="<?= $row_list['category_id']?>" class="btn btn-danger text-white d-block mx-auto pt-1 pb-1">Delete</button>
                                </td>
                            </form>
                        </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    
</body>
</html>

<?php 
    if (isset($_POST["add"])) {
        addCategory($_POST["category"]);
        reset_auto_inclement();
    }

    if (isset($_POST["update"])) {
        $_SESSION["selected_category_id"] = $_POST["update"];

        function getC_name($c_id) {
            $conn = connection();
            $sql = "SELECT category_name FROM categories WHERE category_id = '$c_id'";
            if ($result = $conn->query($sql)){
                $result2 = $result->fetch_assoc();
                return $result2["category_name"];
            } else{
                die('Error :'. $conn->error);
            }
        }

        $_SESSION["selected_category_name"] = getC_name($_POST["update"]);

        header("location:edit-category.php");
    }

    if (isset($_POST["delete"])) {
        delete($_POST["delete"]);
    }
?>
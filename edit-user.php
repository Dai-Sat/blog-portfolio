<?php 

    include "connection.php";
    session_start();
    // avoid location error
    ob_start();

    $user_id = $_SESSION['selected_user_id'];
    $account_id = $_SESSION['selected_account_id'];
    $uname = $_SESSION['uname'];
    $role = $_SESSION['role'];
  

    function getUsers($user_id) {
        $conn = connection();
        $sql = "SELECT * FROM users INNER JOIN accounts ON users.account_id = accounts.account_id WHERE users.user_id = '$user_id'";

        if ($result = $conn->query($sql)) {
            $result2 = $result->fetch_assoc();
            return $result2;
        } else{
            die('Error :'. $conn->error);
        }
    }

    $data_list = getUsers($user_id);

    function passwordCheck($account_id, $password) {
        $conn = connection();
        $sql = "SELECT * FROM accounts WHERE account_id = '$account_id'";
        $pass_flg = False;

        $result =  $conn->query($sql);
        if ($result->num_rows == 1) {
            $account = $result->fetch_assoc();
            if (password_verify($password, $account['password'])) {
                    $pass_flg = True;
            } else {
                echo '<div class="alert alert-danger text-danger mt-2">Password has no match in records</div>';
            }
        } else {
            die('Error : '.$conn->error);
        }
        return $pass_flg;
    }

    function fileSave($user_id, $tmp_path, $filepath) {
        $conn = connection();

        if (move_uploaded_file($tmp_path, $filepath)) {
            $sql = "UPDATE users SET avatar = '$filepath' WHERE user_id = '$user_id'";
            if ($conn->query($sql)){
                return;
            } else{
                die('Error: '. $conn->error);
            }
        } else {
            die('Error: File cannot save.');
        }
    }

    // $update_list = [$fname, $lname, $address, $number, $uname, $password];
    function updatePost($update_list, $user_id, $account_id) {
        $conn = connection();
        $sql1 = "UPDATE users SET 
                first_name = '$update_list[0]',
                last_name = '$update_list[1]', 
                contact_number = '$update_list[3]',
                address = '$update_list[2]'
                WHERE user_id = '$user_id'";

        $update_list[5] = password_hash($update_list[5], PASSWORD_DEFAULT);
        $sql2 = "UPDATE accounts SET 
                username = '$update_list[4]',
                password = '$update_list[5]'
                WHERE account_id = '$account_id'";

        if ($conn->query($sql1)){
            if ($conn->query($sql2)){
                header("location:users.php");
            } else {
                die('Error :'. $conn->error);
            }
        } else {
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
    <title>Profile</title>
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
    <div class="p-3 bg-warning text-white">
        <i class="fa-solid fa-user-pen display-3"></i>
        <span class="lead display-3">User</span>
    </div>

    <!-- activity-6 -->
    <div class="container ps-5 pe-5">
        <div class="card mt-5 p-3">
            <form action="" method="post" enctype="multipart/form-data">
                <div class="row">
                    <!-- profile -->
                    <div class="col-8">
                        <div class="row mt-3">
                            <div class="col">
                                <label for="fname"">First Name</label>
                                <input type="text" name="fname" id="fname" class="form-control mt-2" value="<?= $data_list['first_name']?>">
                            </div>
                            <div class="col">
                                <label for="lname">Last Name</label>
                                <input type="text" name="lname" id="lname" class="form-control mt-2" value="<?= $data_list['last_name']?>">
                            </div>
                        </div>
        
                        <div class="row mt-3">
                            <div class="col-8">
                                <label for="address">Address</label>
                                <input type="address" name="address" id="address" class="form-control mt-2" value="<?= $data_list['address']?>">
                            </div>
                            <div class="col-4">
                                <label for="number">Contact Number</label>
                                <input type="tel" name="number" id="number" class="form-control mt-2" value="<?= $data_list['contact_number']?>">
                                
                            </div>
                        </div>
                        
                        <div class="row mt-3">
                            <div class="col">
                                <label for="username">Username</label>
                                <input type="text" name="uname" id="uname" class="form-control mt-2" value="<?= $data_list['username']?>">
                            </div>
                        </div>
        
                        <div class="row mt-3">
                            <div class="col">
                                <label for="password">Password</label>
                                <input type="password" name="password" id="password" class="form-control mt-2" placeholder="Enter password to confirm" required>
                            </div>
                        </div>
                        
                        <button type="submit" name="update" class="btn btn-secondary px-5 mt-4 form-control text-white">UPDATE</button>

                    </div>

                    <!-- images -->
                    <div class="col-4">
                        <div class="display-1 text-center mt-3">
                            <?php 
                            if (empty($data_list['avatar']) == False) {
                                echo '<img src="'.$data_list['avatar'].'" alt="" class="border rounded p-1" style="width:320px; height:320px; object-fit:contain;">';
                            } else {
                                echo '<div class = "border rounded" style="width:320px; height:320px;>';
                                echo '<i class="fa-regular fa-user"></i>';
                                echo '</div>';
                            }
                            ?>
                        </div>

                        <br>

                        <div class="input-group">
                            <input type="file" class="form-control" name="selected_file">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
</body>
</html>

<?php 
    if(isset($_POST['update'])) {
        $password = $_POST['password'];
        $pass_flg = passwordCheck($user_id, $password);

        if ($pass_flg == True) {
            
            $upload_files = "./upload_files/";
            $filename = $_FILES['selected_file']['name'];
            $filepath = $upload_files.$filename;
            if (empty($filename) == False) {
                $tmp_path = $_FILES['selected_file']['tmp_name'];
                fileSave($user_id, $tmp_path, $filepath);
            }
            
            $fname = $_POST['fname'];
            $lname = $_POST['lname'];
            $address = $_POST['address'];
            $number = $_POST['number'];
            $uname = $_POST['uname'];
            $update_list = [$fname, $lname, $address, $number, $uname, $password];
            updatePost($update_list, $user_id, $account_id);
        }
        
    }
?>
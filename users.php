<?php 

    include "connection.php";
    session_start();
    // avoid location error
    ob_start();

    // $user_id = $_SESSION['user_id'];
    // $account_id = $_SESSION["account_id"];
    $uname = $_SESSION['uname'];
    $role = $_SESSION['role'];

    function getUsers() {
        $conn = connection();
        $sql = "SELECT users.account_id AS ac_id, users.first_name, users.last_name, users.contact_number, users.address, accounts.username 
                FROM users INNER JOIN accounts ON users.account_id = accounts.account_id ORDER BY users.account_id";

        if ($result = $conn->query($sql)){
            return $result;
        } else{
            die('Error :'. $conn->error);
        }
    }

    function addaccounts($add_list) {
        $add_list[1] = password_hash($add_list[1] , PASSWORD_DEFAULT);

        $conn = connection();
        $sql1 = "INSERT INTO accounts (username, password) VALUES('$add_list[0]', '$add_list[1]')";
        $sql2 = "SELECT account_id FROM accounts WHERE username = '$add_list[0]'";

        if($conn->query($sql1)) {
            if ($data = $conn->query($sql2)->fetch_assoc()) {
                return $data['account_id'];
            } else {
                die('Error : '. $conn->error);
            }
        } else {
            die('Error : '. $conn->error);
        }
    }

    function addUsers($add_list) {
        // $add_list = [$fname, $lname, $number, $address, $account_id];

        $conn = connection();
        $sql = "INSERT INTO users (first_name, last_name, contact_number, address, account_id) 
                 VALUES('$add_list[0]', '$add_list[1]', '$add_list[2]', '$add_list[3]', '$add_list[4]')";

        if($conn->query($sql)){
            header("location:users.php");
        } else {
            die('Error : '. $conn->error);
        }
    }

    function delete($account_id) {
        $conn = connection();
        $sql1 = "DELETE FROM users WHERE account_id = '$account_id'";
        $sql2 = "DELETE FROM accounts WHERE account_id = '$account_id'";
        if ($conn->query($sql1)) {
            if ($conn->query($sql2)) {
                header("refresh:0");
            } else {
                die('Error :'. $conn->error);
            }
        } else {
            die('Error :'. $conn->error);
        }
    }

    function reset_auto_inclement() {
        // put auto_inclement in order
        $conn = connection();
        $sql0 = "SET @i = 0";
        $sql1 = "UPDATE users SET user_id = (@i := @i + 1)";
        $sql2 = "UPDATE accounts SET account_id = (@i := @i + 1)";
        $sql3 = "UPDATE users SET account_id = (@i := @i + 1)";
        $conn->query($sql0);
        $conn->query($sql1);
        $conn->query($sql0);
        $conn->query($sql2);
        $conn->query($sql0);
        $conn->query($sql3);
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
    <title>User</title>
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

    <form action="" method="post">
        <div class="container p-5">
            <div class="card w-50 mx-auto">
                <div class="card-body">
                    <p class="text-start display-6 fw-bold">Add User</p> 

                    <div class="row mt-2">
                        <div class="col">
                            <input type="text" name="fname" id="firstname" class="form-control d-inline mt-2" placeholder="First Name" required>
                        </div>
                        <div class="col">
                            <input type="text" name="lname" id="lastname" class="form-control d-inline mt-2" placeholder="Last Name" required>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col">
                            <input type="text" name="number" id="number" class="form-control d-inline" placeholder="Contact Number" required>
                        </div>
                        <div class="col">
                            <input type="address" name="address" id="address" class="form-control d-inline" placeholder="Address" required>
                        </div>
                    </div>
                    
                    <div class="row mt-2">
                        <div class="col">
                            <input type="text" name="uname" id="username" class="form-control" placeholder="Username" required>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col">
                            <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                        </div>
                    </div>

                    <button type="submit" name="add" class="btn btn-warning px-5 mt-3 form-control text-white">ADD</button>
                
                </div>
            </div>
        </div>
    </form>
        
    <form action="" method="post">
        <div class="container">
            <table class="table  table-striped table-sm">
                <thead class="table-dark text-uppercase">
                    <td>Account ID</td>
                    <td>Full name</td>
                    <td>Contact Number</td>
                    <td>Address</td>
                    <td>User name</td>
                    <td></td>
                    <td></td>
                </thead>
                <tbody>
                    <?php $alldata = getUsers(); 
                        while ($row_list = $alldata->fetch_assoc()):?>
                            <tr>
                                <td><?= $row_list['ac_id']?></td>
                                <td><?= $row_list['first_name']." ".$row_list['last_name'] ?></td>
                                <td><?= $row_list['contact_number']?></td>
                                <td><?= $row_list['address']?></td>
                                <td><?= $row_list['username']?></td>
                                <td>
                                    <button type="submit" name="update" value="<?= $row_list['ac_id']?>" class="btn btn-warning text-white d-block mx-auto pt-1 pb-1">Update</button>
                                </td>
                                <td>
                                    <button type="submit" name="delete" value="<?= $row_list['ac_id']?>" class="btn btn-danger text-white d-block mx-auto pt-1 pb-1">Delete</button>
                                </td>
                            </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </form>
    
</body>
</html>

<?php 
    if (isset($_POST["add"])) {
        $fname = $_POST["fname"] ;
        $lname = $_POST["lname"] ; 
        $number = $_POST["number"] ; 
        $address = $_POST["address"] ; 
        $uname = $_POST["uname"] ; 
        $password = $_POST["password"] ;

        $add_account_list = [$uname, $password];
        $account_id = addAccounts($add_account_list);

        $add_user_list = [$fname, $lname, $number, $address, $account_id];
        addUsers($add_user_list);

        reset_auto_inclement();
    }

    if (isset($_POST["update"])) {

        function getUser_id($account_id) {
            $conn = connection();
            $sql = "SELECT user_id FROM users WHERE account_id = '$account_id'";
    
            if ($result = $conn->query($sql)) {
                $result2 = $result->fetch_assoc();
                return $result2["user_id"];
            } else{
                die('Error :'. $conn->error);
            }
        }

        $_SESSION['selected_account_id'] = $_POST["update"];
        $_SESSION['selected_user_id'] = getUser_id($_POST["update"]);
        
        header ("location:edit-user.php");

    }

    if (isset($_POST["delete"])) {
        
        delete($_POST["delete"]);
        reset_auto_inclement();

    }
?>
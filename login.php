<?php 
    include 'connection.php';
    session_start();
    // avoid location error
    ob_start();

    function Login($uname, $password) {
        $conn = connection();

        $sql1 = "SELECT * FROM accounts WHERE username = '$uname'";
        $result =  $conn->query($sql1);
        if ($result->num_rows == 1) {
            $account = $result->fetch_assoc();
            if (password_verify($password, $account['password'])) {

                $_SESSION["account_id"] = $account['account_id'];
                $account_id = $_SESSION["account_id"];
                $sql1 = "SELECT user_id FROM users WHERE account_id = '$account_id'";
                $result = $conn->query($sql1);
                $result2 = $result->fetch_assoc();
                $user_id = $result2["user_id"];
                $_SESSION['user_id'] = $user_id;
                $_SESSION['uname'] = $uname;

                if ($account['role'] == 'A') {
                    $_SESSION['role'] = 'A';
                    header("location:dashboard.php");
                } else {
                    $_SESSION['role'] = 'U';
                    header("location:profile.php");
                }
            } else {
                $message = 'Error : Password has no match in records';
            }
        } else {
            $message = 'Error : Username has no match in records';
        }
        echo '<div class="alert alert-danger text-danger">'.$message.'</div>';
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
    <title>login</title>
</head>
<body>
    <div class="container p-5">
        <form action="" method="post">
            <div class="card w-50 mx-auto">
                <div class="card-header">
                    <p class="text-center lead display-6">LOGIN</p>
                </div>
            
                <div class="card-body">
                        <div class="mb-3">
                            <input type="text" name="uname" id="uname" class="form-control border-0 border-bottom rounded-0" placeholder="USERNAME">
                        </div>

                        <div class="mb-3">
                            <input type="password" name="password" id="password" class="form-control border-0 border-bottom rounded-0" placeholder="PASSWORD">
                        </div>
                </div>

                <div class="card-footer">

                    <button type="submit" name="btn_enter" class="btn btn-success px-5 mt-3 text-white form-control">ENTER</button>
                    
                    <div class="row">
                        <div class="col">
                            <a href="register.php">
                                <p class="lead text-decoration-underline text-center text-dark mt-3">Create an Account</p>
                            </a>
                        </div>
                        <!-- <div class="col">
                            <a href="#">
                                <p class="lead text-decoration-underline text-center text-dark mt-3">Recover Account</p>
                            </a>
                        </div> -->
                    </div>

                </div>
            </div>
        </form>
    </div>

</body>
</html>

<?php 
    if (isset($_POST["btn_enter"])) {
        $uname = $_POST["uname"];
        $password = $_POST["password"];
        Login($uname, $password);
    }

?>
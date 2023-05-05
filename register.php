
<?php
    include 'connection.php';
    // avoid location error
    ob_start();

    function reset_auto_inclement() {
        // put auto_inclement in order
        $conn = connection();
        $sql0 = "SET @i = 0, @j = 0";
        $sql1 = "UPDATE users SET user_id = (@i := @i + 1), account_id = (@j := @j + 1)";
        $sql2 = "UPDATE accounts SET account_id = (@i := @i + 1)";
        $conn->query($sql0);
        $conn->query($sql1);
        $conn->query($sql0);
        $conn->query($sql2);
    }
?>

<!doctype html>
<html lang="en">

<head>
  <title>Sign up</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
</head>

<body>

    <form action="" method="post">
      <div class="card mt-5 mx-auto w-25">
         <div class="card-header">
            <h5 class="text-black text-center">Create your account</h5>
         </div>
         <div class="card-body">
            <div class="mb-3">
                <label for="" class="mb-2">First Name</label>
                <input type="text" name="fname" class="form-control" required autofocus>
            </div>

            <div class="mb-3">
                <label for="" class="mb-2">Last Name</label>
                <input type="text" name="lname" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="" class="mb-2">Address</label>
                <input type="text" name="address" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="" class="mb-2">Contact Number</label>
                <input type="tel" name="number"  class="form-control" required>
                <!-- haw to set format → pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" -->
            </div>

            <div class="mb-3">
                <label for="" class="mb-2">User Name</label>
                <input type="text" name="uname" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="" class="mb-2">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="" class="mb-2">Confirm Password</label>
                <input type="password" name="c-password" class="form-control" required>
            </div>

            <br>
            
            <form action="" method="post">
                <button type="submit" name="btn_sign_up" class="btn btn-success form-control">Sign Up</button>
            </form>
            
            <div class="text-center mt-2">
                <label for="">
                    Already have an account ?<a href="login.php">Log in</a>
                </label>
            </div>
         </div>
      </div>
   </form>

</body>

</html>

<?php
    if (isset($_POST['btn_sign_up'])) {
        if ($_POST['password'] != $_POST['c-password']) {
            echo '<div class="alert alert-danger text-danger">
                  Password and Confirm Password do not match.
              </div>';
        } else {
            $conn = connection();
            $fname = $_POST['fname'];
            $lname = $_POST['lname'];
            $address = $_POST['address'];
            $number = $_POST['number'];
            $uname = $_POST['uname'];
            // $pass_hash = hash("sha256", $_POST['password']);
            $pass_hash = password_hash($_POST['password'] , PASSWORD_DEFAULT);

            $sql1 = "INSERT INTO accounts (username, password) VALUES('$uname', '$pass_hash')";
            $sql2 = "INSERT INTO users (first_name, last_name, contact_number, address) 
                    VALUES('$fname', '$lname', '$number', '$address')";

            if($conn->query($sql1)){
                if ($conn->query($sql2)) {
                    header("location:login.php");
                    reset_auto_inclement();
                } else {
                    die('Error : '. $conn->error);
                }
            } else {
                die('Error : '. $conn->error);
            }
        }
    }    
?>
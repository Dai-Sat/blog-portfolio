
<?php function createNavbar($uname) { ?>
    <header>
    <nav class="navbar navbar-expand navbar-dark bg-dark">
        
        <a class="navbar-brand ms-3 href="">Bloggen</a>
        
        <div class="collapse navbar-collapse" id="">
            <ul class="navbar-nav me-auto ms-3">
                <li class="nav-item">
                    <a class="nav-link active" href="posts.php">My Posts</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="add-post.php">Add Posts</a>
                </li>
            </ul>

            <ul class="navbar-nav ms-auto me-3">
                <li class="nav-item">
                    <a class="nav-link active" href="profile.php">
                    <i class="fa-solid fa-user"></i> Welcome <?= $uname?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="logout.php">
                        <i class="fa-solid fa-user"></i> Log out
                    </a>
                </li>
            </ul>
        </div>
    </nav>
    </header>

<?php } ?>
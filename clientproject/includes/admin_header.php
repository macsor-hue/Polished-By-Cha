<header class="admin_header"> 
    <div class="container">
        <a href="#" >
            <img src="resources/style/photos/header_icon.png" alt="Image of Logo" class="logo-img">
            <h2 id="brand_name">Polished By Cha</h2>
        </a>
    </div>
    <nav class="admin_navbar1">
        <a href="features/coolstuff/users.php">User Account</a>
        <a href="crud/table/table.php">View scheduling</a>
        <a href="features/coolstuff/salesreport.php">Sales report</a>
        <form action="code.php" method="POST">
            <input type="hidden" name="action" value="admin">
            <button type="submit">Admin</button>
        </form>
    </nav>
    <nav class="admin_navbar2">
        <form action="code.php" method="post">
                <input type="hidden" name="action" value="logout">
                <button type="submit">Logout</button>
            </form>
    </nav>
</header>





                    
                       
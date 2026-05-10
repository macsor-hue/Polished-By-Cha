<header class="admin_header">
    <nav class="admin_navbar">
        <div class="container">
            <a href="#" >
                <img src="../../resources/style/photos/header_icon.png" alt="Image of Logo" class="logo-img">
                <h2 id="brand_name">Polished By Cha</h2>
            </a>
        </div>
    </nav>
    <nav class="admin_navbar">
        <div class="containerBtn">
            <a href="/clientproject/main.php">HOME</a>
            <a href="/clientproject/features/coolstuff/users.php">ACCOUNT</a>
            <a href="/clientproject/crud/table/table.php">SCHEDULES</a>
            <a href="/clientproject/features/coolstuff/salesreport.php">SALES</a>
        </div>
    </nav>
    <nav class="admin_navbar">
        <div class="containerActn">
            <form action="/clientproject/code.php" method="POST">
                <input type="hidden" name="action" value="admin">
                <button type="submit" class="adminBtn">ADMIN</button>
            </form>
            <form action="/clientproject/code.php" method="post"
                onsubmit="return confirm('Are you sure you want to logout?');">
                <input type="hidden" name="action" value="logout">
                <button type="submit" class="logoutBtn">LOGOUT</button>
            </form>
        </div>
    </nav>
</header>





                    
                       
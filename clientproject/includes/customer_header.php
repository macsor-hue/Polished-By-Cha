<header class="user_header"> 
    <div class="container">
        <a href="#" >
            <img src="resources/style/photos/header_icon.png" alt="Image of Logo" class="logo-img">
            <h2 id="brand_name">Polished By Cha</h2>
        </a>
    </div>
    <nav class="user_navbar">
        <div class="containerBtn">
            <a href="main.php">HOME</a>
            <a href="features/coolstuff/customer_update.php">ACCOUNT</a>
            <a href="crud/table/custable.php">APPOINMENTS</a>
        </div>
    </nav>
    <nav class="user_navbar">
        <div class="containerActn">
            <form action="code.php" method="post"
                onsubmit="return confirm('Are you sure you want to logout?');">
                <input type="hidden" name="action" value="logout">
                <button type="submit" class="logoutBtn">LOGOUT</button>
            </form>
        </div>
    </nav>
</header>
</header>  
                     
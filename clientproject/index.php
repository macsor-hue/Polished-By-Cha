<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <!-- Stylesheets -->
    <link rel="stylesheet" href="resources/style/landing.css">
    <title>Polished By Cha</title>
    <link rel="icon" type="image/x-icon" href="resources/style/photos/header_icon.png"> 
</head>
<body>
    <!-- HEADER INCLUDE -->
    <?php include 'includes/header.php'; ?>

    <!-- SECTION: HOME
         Landing banner with brand name, tagline,
         and a call-to-action button to register -->
    <section id="home" class="home">
        <div class="home_content">
            <h1>Welcome to </h1>
            <h1 class="desc_brand">Polished By Cha!</h1>
            <h2>life isn't perfect, but your nails can be</h2>
            <div class="landing_button">
                <a href="main.php">REGISTER AND BOOK AN APPOINTMENT NOW</a>
            </div>
        </div>
    </section>

    <!-- SECTION: SERVICES
         Displays all available nail services with
         icons, names, prices, and avail buttons -->
    <section id="services">
        <div class="services_title">
            <h1>Services</h1>
            <p>Discover the perfect service for your style and self-care</p>
        </div>
        <div class="services_flex">
            <div class="services_container">
                <div class="services_info">

                    <!-- Service: Plain Gel Polish -->
                    <div class="services_in">
                        <img src="resources/style/photos/plain_gel.png" alt="Plain Gel Photo" id="nails" class="plain_gel">
                        <h1>Plain Gel Polish</h1>
                        <h3>Php150.00</h3>
                        <a href="main.php" class="availBTN">AVAIL</a>
                    </div>
                    <!-- Service: Nail Art Gel Polish -->
                    <div class="services_in">
                        <img src="resources/style/photos/nail_gel.png" alt="Nail Art Gel Photo" id="nails" class="nail_gel">
                        <h1>Nail Art Gel Polish</h1>
                        <h3>Php250.00</h3>
                        <a href="main.php" class="availBTN">AVAIL</a>
                    </div>

                    <!-- Service: Plain Nail Extension -->
                    <div class="services_in">
                        <img src="resources/style/photos/plain_nail.png" alt="Plain Nail Photo" id="nails" class="plain_nail">
                        <h1>Plain Nail Extension</h1>
                        <h3>Php300.00</h3>
                        <a href="main.php" class="availBTN">AVAIL</a>
                    </div>

                    <!-- Service: Nail Art Extension -->
                    <div class="services_in">
                        <img src="resources/style/photos/nail_extension.png" alt="Nail Art Extension Photo" id="nails" class="nail_extension">
                        <h1>Nail Art Extension</h1>
                        <h3>Php350.00 - Php400.00</h3>
                        <a href="main.php" class="availBTN">AVAIL</a>
                    </div>

                    <!-- Service: Gel Polish / Nail Extension Removal -->
                    <div class="services_in">
                        <img src="resources/style/photos/gel_remove.png" alt="Gel/Nail Removal Photo" id="nails" class="gel_remove">
                        <h1>Gel polish/Nail Extension Removal</h1>
                        <h3>Php100.00</h3>
                        <a href="main.php" class="availBTN">AVAIL</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- SECTION: ABOUT US
         Displays salon location, contact number,
         and email address -->
    <section id="about">
        <div class="about_title">
            <h1>About Us</h1>
            <p>Get to know us more</p>
        </div>
        <div class="about">
            <div class="about_flex">
                <div class="about_container">

                    <!-- Contact & Location Info -->
                    <div class="about_content">
                        <div class="about_info">
                            <p>📍 Located at Blumentritt Street, Tigpalas, San Miguel, Bulacan</p><br>
                            <p>📞 0907-754-1010</p><br>
                            <p>📧 krishajoy183@gmail.com</p>
                        </div>
                    </div>
                    <div class="about_content">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- FOOTER INCLUDE -->
    <?php include 'includes/footer.php'; ?>
    
</body>
</html>

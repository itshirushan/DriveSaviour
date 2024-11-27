<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DriveSaviour</title>
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" type="dp" href="../../img/ss.png">
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <link rel="icon" type="image/png" href="../../img/logo.jpg">
</head>
<body>
    
<section class="wrapper">
        <header>
            <div class="container">
                <div class="logo">
                    <a href="#"><img src="../../img/ss.png" alt="Logo"></a>
                    <a href="#"><img class="logo2" src="../../img/1s.png" alt="Logo"></a>
                </div>
                <nav>
                    <ul id="menu">
                    <li><a href="../home/home.php">Home</a></li>
                        <li><a href="../about/about.php">About Us</a></li>
                        <li><a href="../products/product.php">Products</a></li>
                        <li><a href="../contact/contact.php">Contact Us</a></li>
                        <li><a href="../Breakdown/breakdown.php">Breakdowns</a></li>
                        <li><a href="../Login/login.php" onclick="showPreloader(event, '../Login/login.php')">Log Out</a></li>
                    <!-- User Icon -->
                    <div class="user-icon">
                        <a href="../profile/profile.php"><img src="../../img/profile.png" alt="profile image" class="small-icon"></a>
                    </div>
                        <li>
                            <!-- Dark Mode Toggle -->
                            <input type="checkbox" id="darkmode-toggle" style="display: none;">
                            <label for="darkmode-toggle" class="darkmode-toggle-label">
                                <svg version="1.1" class="sun" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 496 496">
                                    <!-- SVG content for Sun -->
                                    <rect x="152.994" y="58.921" transform="matrix(0.3827 0.9239 -0.9239 0.3827 168.6176 -118.5145)" width="40.001" height="16"/>
                                    <rect x="46.9" y="164.979" transform="matrix(0.9239 0.3827 -0.3827 0.9239 71.29 -12.4346)" width="40.001" height="16"/>
                                    <rect x="46.947" y="315.048" transform="matrix(0.9239 -0.3827 0.3827 0.9239 -118.531 50.2116)" width="40.001" height="16"/>
                                    <rect x="164.966" y="409.112" transform="matrix(-0.9238 -0.3828 0.3828 -0.9238 168.4872 891.7491)" width="16" height="39.999"/>
                                    <rect x="303.031" y="421.036" transform="matrix(-0.3827 -0.9239 0.9239 -0.3827 50.2758 891.6655)" width="40.001" height="16"/>
                                    <rect x="409.088" y="315.018" transform="matrix(-0.9239 -0.3827 0.3827 -0.9239 701.898 785.6559)" width="40.001" height="16"/>
                                    <rect x="409.054" y="165.011" transform="matrix(-0.9239 0.3827 -0.3827 -0.9239 891.6585 168.6574)" width="40.001" height="16"/>
                                    <rect x="315.001" y="46.895" transform="matrix(0.9238 0.3828 -0.3828 0.9238 50.212 -118.5529)" width="16" height="39.999"/>
                                    <path d="M248,88c-88.224,0-160,71.776-160,160s71.776,160,160,160s160-71.776,160-160S336.224,88,248,88z M248,392c-79.4,0-144-64.6-144-144s64.6-144,144-144s144,64.6,144,144S327.4,392,248,392z"/>
                                    <rect x="240" width="16" height="72"/>
                                    <rect x="62.097" y="90.096" transform="matrix(0.7071 0.7071 -0.7071 0.7071 98.0963 -40.6334)" width="71.999" height="16"/>
                                    <rect y="240" width="72" height="16"/>
                                    <rect x="90.091" y="361.915" transform="matrix(-0.7071 -0.7071 0.7071 -0.7071 -113.9157 748.643)" width="16" height="71.999"/>
                                    <rect x="240" y="424" width="16" height="72"/>
                                    <rect x="361.881" y="389.915" transform="matrix(-0.7071 -0.7071 0.7071 -0.7071 397.8562 960.6281)" width="71.999" height="16"/>
                                    <rect x="424" y="240" width="72" height="16"/>
                                    <rect x="389.911" y="62.091" transform="matrix(0.7071 0.7071 -0.7071 0.7071 185.9067 -252.6357)" width="16" height="71.999"/>
                                </svg>
                                <svg version="1.1" class="moon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 49.739 49.739">
                                    <!-- SVG content for Moon -->
                                    <path d="M25.068,48.889c-9.173,0-18.017-5.06-22.396-13.804C-3.373,23.008,1.164,8.467,13.003,1.979l2.061-1.129l-0.615,2.268c-1.479,5.459-0.899,11.25,1.633,16.306c2.75,5.493,7.476,9.587,13.305,11.526c5.831,1.939,12.065,1.492,17.559-1.258v0c0.25-0.125,0.492-0.258,0.734-0.391l2.061-1.13l-0.585,2.252c-1.863,6.873-6.577,12.639-12.933,15.822C32.639,48.039,28.825,48.888,25.068,48.889z M12.002,4.936c-9.413,6.428-12.756,18.837-7.54,29.253c5.678,11.34,19.522,15.945,30.864,10.268c5.154-2.582,9.136-7.012,11.181-12.357c-5.632,2.427-11.882,2.702-17.752,0.748c-6.337-2.108-11.473-6.557-14.463-12.528C11.899,15.541,11.11,10.16,12.002,4.936z"/>
                                </svg>
                            </label>
                        </li>
                    </ul>
                    <div class="menu-toggle" id="menu-toggle">
                        <div class="bar"></div>
                        <div class="bar2"></div>
                        <div class="bar"></div>
                    </div>
                </nav>
            </div>
        </header>

    </section>

    <script>
    document.getElementById("menu-toggle").addEventListener("click", function() {
    var menu = document.getElementById("menu");
    var toggleButton = document.getElementById("menu-toggle");
    menu.classList.toggle("show");
    toggleButton.classList.toggle("show");
});


document.addEventListener('DOMContentLoaded', function () {
    const darkModeToggle = document.getElementById('darkmode-toggle');
    const body = document.body;

    darkModeToggle.addEventListener('change', function () {
        if (darkModeToggle.checked) {
            body.classList.add('dark-mode');
        } else {
            body.classList.remove('dark-mode');
        }
    });

    // Set initial state based on saved preference
    if (localStorage.getItem('darkMode') === 'enabled') {
        darkModeToggle.checked = true;
        body.classList.add('dark-mode');
    }

    darkModeToggle.addEventListener('change', function () {
        if (darkModeToggle.checked) {
            localStorage.setItem('darkMode', 'enabled');
        } else {
            localStorage.setItem('darkMode', 'disabled');
        }
    });
});
    </script>


</body>
</html>

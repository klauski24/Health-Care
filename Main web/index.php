<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Healthcare</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <?php  session_start();?>
    <header>
        <div class="logo-header">
            <img src="./Picture/logo.png" alt="Health Care Logo">
        </div>
        <div class="menu-header">
            <nav class="nav-header">
                <a href="index.php?page_layout=mainweb">Home</a>
                <a href="index.php?page_layout=chitiet">Services</a>
                <a href="index.php?page_layout=chitiet">Doctors</a>
                <a href="index.php?page_layout=about">About Us</a>
                <a href="index.php?page_layout=chitiet">Contact</a>
            </nav>
            <nav class="nav-auth">
                <a href="#">Register now</a>
                <button>Login</button>
                <i class="fa fa-search" aria-hidden="true"></i>
                <i class="fa fa-bars" aria-hidden="true"></i>
            </nav>
        </div>
    </header>
        <?php  
            if(isset($_GET['page_layout'])){
                switch($_GET['page_layout']){
                    case 'mainweb':
                        include "mainweb.php";
                        break;
                    case 'services':
                        include "services.php";
                        break;
                    case 'doctors':
                        include "doingu.php";
                        break;
                    case 'about':
                        include "about.php";
                        break;
                    case 'contact':
                        include "contact.php";
                        break;
                    default:
                        include "mainweb.php";
                        break;
                    }
    } else {
        include "mainweb.php";
    }
    ?>
    </header>
    <?php include "footer.php"; ?>
</body>
</html>
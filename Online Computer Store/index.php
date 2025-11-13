<?php 
session_set_cookie_params([
    'lifetime' => 0,       // expires when browser closes
    'path' => '/',
    'secure' => true,      // only over HTTPS
    'httponly' => true,    // JS cannot access it
    'samesite' => 'Strict' // strong CSRF protection
]);

session_start(); 
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KAH TECH - Home</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>
    <?php
    include 'header.php';
    include 'includes/dbh.inc.php';
    ?>

    <?php
    $query = "SELECT * FROM homepage WHERE id=1";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    ?>

    <main>


        <div class="firstContainer">




            <div class="cardContainer">


                <div class="wrapper">
                    <i id="left" class="fa-solid fa-angle-left"></i>
                    <ul class="carousel">
                        <li class="card">
                            <div class="img"><img src="../Image/<?php echo $row['image1']; ?>" alt="Image 1"></div>
                        </li>
                        <li class="card">
                            <div class="img"><img src="../Image/<?php echo $row['image2']; ?>" alt="Image 2"></div>

                        </li>
                        <li class="card">
                            <div class="img"><img src="../Image/<?php echo $row['image3']; ?>" alt="Image 3"></div>
                        </li>
                        <li class="card">
                            <div class="img"><img src="../Image/<?php echo $row['image4']; ?>" alt="Image 4"></div>
                        </li>
                    </ul>
                    <i id="right" class="fa-solid fa-angle-right"></i>
                </div>
            </div>


            <div class="customizeContainer">
                <div class="customizeDescription">
                    <p class="headDescription">CUSTOMIZE YOUR COMPUTER</p>
                    <p class="description">Personal computers should transcend machines, they must be tailored experiences designed for you.</p>
                    <?php
                    if (isset($_SESSION['ID'])) { ?>
                        <a href="customizePc.php" class="customizeButton">
                            <p>CUSTOMIZE NOW!</p>
                        </a>
                    <?php } else { ?>
                        <a href="login.php" class="customizeButton">
                            <p>CUSTOMIZE NOW!</p>
                        </a>
                    <?php } ?>

                </div>
                <div class="customizeImage">
                    <img class="customizeImg" src="../Image/CUSTOMIZE.png">
                </div>
            </div>



            <div class="aboutUsContainer" id="about_us">
                <div class="aboutUsBox">
                    <div class="aboutUsHeader">
                        <p>ABOUT US</p>
                    </div>
                    <hr class="hr">
                    <div class="aboutUsDescription">
                        <p><?php echo $row['aboutus_description'] ?></p>
                    </div>
                    <div class="aboutUsImage">
                        <img class="aboutUsimg" src="../Image/<?php echo $row['image5'] ?>">
                    </div>
                </div>
            </div>

            <div class="missionContainer">
                <div class="missionBox">
                    <div class="aboutUsHeader">
                        <p>OUR MISSION</p>
                    </div>
                    <hr class="hr">
                    <div class="aboutUsDescription">
                        <p><?php echo $row['mission_description'] ?></p>
                    </div>
                    <div class="aboutUsImage">
                        <img class="aboutUsimg" src="../Image/<?php echo $row['image6'] ?>">
                    </div>
                </div>
            </div>

            <div class="WWDContainer">
                <div class="WWDHeader">
                    <P>WHAT WE DO</P>
                </div>
                <hr class="hr">
                <div class="WWDContent">
                    <div class="WWD_IMG_DES">
                        <div class="WWD_img_container">
                            <img class="WWD_img_preview" src="../Image/<?php echo $row['image7'] ?>">
                        </div>
                        <div class="WWD_DES">

                            <p class="WWD_DES_Input"><?php echo $row['whatwedo1'] ?></p>
                        </div>
                    </div>
                    <div class="WWD_IMG_DES">
                        <div class="WWD_img_container">
                            <img class="WWD_img_preview" src="../Image/<?php echo $row['image8'] ?>">
                        </div>
                        <div class="WWD_DES">

                            <p class="WWD_DES_Input"><?php echo $row['whatwedo2'] ?></p>
                        </div>
                    </div>
                    <div class="WWD_IMG_DES">
                        <div class="WWD_img_container">
                            <img class="WWD_img_preview" src="../Image/<?php echo $row['image9'] ?>">
                        </div>
                        <div class="WWD_DES">

                            <p class="WWD_DES_Input"><?php echo $row['whatwedo3'] ?></p>
                        </div>
                    </div>
                    <div class="WWD_IMG_DES">
                        <div class="WWD_img_container">
                            <img class="WWD_img_preview" src="../Image/<?php echo $row['image10'] ?>">
                        </div>
                        <div class="WWD_DES">

                            <p class="WWD_DES_Input"><?php echo $row['whatwedo4'] ?></p>
                        </div>
                    </div>

                </div>
            </div>




        </div>
    </main>

    <?php
    include 'footer.php';
    ?>

    <script>
        const wrapper = document.querySelector(".wrapper");
        const carousel = document.querySelector(".carousel");
        const firstCardWidth = carousel.querySelector(".card").offsetWidth;
        const arrowBtns = document.querySelectorAll(".wrapper i");
        const carouselChildrens = [...carousel.children];
        let isDragging = false,
            isAutoPlay = true,
            startX, startScrollLeft, timeoutId;

        let cardPerView = Math.round(carousel.offsetWidth / firstCardWidth);

        carouselChildrens.slice(-cardPerView).reverse().forEach(card => {
            carousel.insertAdjacentHTML("afterbegin", card.outerHTML);
        });

        carouselChildrens.slice(0, cardPerView).forEach(card => {
            carousel.insertAdjacentHTML("beforeend", card.outerHTML);
        });

        carousel.classList.add("no-transition");
        carousel.scrollLeft = carousel.offsetWidth;
        carousel.classList.remove("no-transition");

        arrowBtns.forEach(btn => {
            btn.addEventListener("click", () => {
                carousel.scrollLeft += btn.id == "left" ? -firstCardWidth : firstCardWidth;
            });
        });

        const dragStart = (e) => {
            e.preventDefault();
            isDragging = true;
            carousel.classList.add("dragging");
            startX = e.pageX;
            startScrollLeft = carousel.scrollLeft;
        }

        const dragging = (e) => {
            if (!isDragging) return;
            carousel.scrollLeft = startScrollLeft - (e.pageX - startX);
        }

        const dragStop = () => {
            isDragging = false;
            carousel.classList.remove("dragging");
        }

        const infiniteScroll = () => {
            if (carousel.scrollLeft === 0) {
                carousel.classList.add("no-transition");
                carousel.scrollLeft = carousel.scrollWidth - (2 * carousel.offsetWidth);
                carousel.classList.remove("no-transition");
            } else if (Math.ceil(carousel.scrollLeft) === carousel.scrollWidth - carousel.offsetWidth) {
                carousel.classList.add("no-transition");
                carousel.scrollLeft = carousel.offsetWidth;
                carousel.classList.remove("no-transition");
            }
            clearTimeout(timeoutId);
            if (!wrapper.matches(":hover")) autoPlay();
        }

        const autoPlay = () => {
            if (window.innerWidth < 800 || !isAutoPlay) return;
            timeoutId = setTimeout(() => carousel.scrollLeft += firstCardWidth, 2500);
        }

        autoPlay();

        carousel.addEventListener("mousedown", dragStart);
        document.addEventListener("mousemove", dragging);
        document.addEventListener("mouseup", dragStop);
        carousel.addEventListener("scroll", infiniteScroll);
        wrapper.addEventListener("mouseenter", () => clearTimeout(timeoutId));
        wrapper.addEventListener("mouseleave", autoPlay);
    </script>
</body>

</html>
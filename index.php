<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Short Video Platform</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <link rel="stylesheet" href="path/to/font-awesome/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">



</head>

<body>
    <div class="text-center">
        <header class="bg-primary text-light text-center py-3 ">
            <h1>Your Short Video Platform</h1>
        </header>
        <div class="">
            <?php
            session_start();
            ?>
        </div>
    </div>


    <!-- <main class="container  mt-5 bg-success "> -->
    <div class="row  d-flex justify-content-center  p-10  ">
        <div class=" content-main d-flex justify-content-center col-sm-3 pt-2 mb-8">
            <?php
            require 'components.php';
            renderVideoSection();
            ?>
        </div>
        <aside class="col-lg-2 pt-2">
            <?php

            if (isset($_SESSION['user_id'])) {
                require 'db_connection.php';
                $userId = $_SESSION['user_id'];
                $userCardData = getUserCardData($conn, $userId);
                renderUserCard($userCardData['username'], $userCardData['followers_count']);
                echo '<a class="btn btn-primary mr-2" href="upload/upload.php">Upload Video</a>';
                echo '<a class="btn btn-danger mr-2" href="login/logout.php">Logout</a>';
                echo '<a class="btn btn-success mr-2" href="creator_dashboard.php">Creator Dashboard</a>'; // Add this line
            } else {
                echo '<a class="btn btn-primary" href="login/login.html">Login</a>';
            }
            echo '<a class="btn btn-secondary" href="register/register.html">Register</a>';
            ?>
        </aside>
    </div>
    <!-- </main> -->
    <footer class="bg-dark text-light text-center py-3">

    </footer>



    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
        crossorigin="anonymous"></script>





















    <script>




        document.addEventListener("DOMContentLoaded", function () {
            var followButton = document.getElementById("follow-button");
            var isFollowing = false; // Initialize the following state

            followButton.addEventListener("click", function (e) {
                e.preventDefault(); // Prevent the form from submitting and page from reloading

                if (isFollowing) {
                    // If already following, unfollow
                    followButton.innerText = "Follow";
                    isFollowing = false;
                } else {
                    // If not following, follow
                    followButton.innerText = "Unfollow";
                    isFollowing = true;
                }
            });
        });


        $(document).ready(function () {
            // Initialize the carousel
            var currentIndex = 0;
            var items = $('#videoCarousel .carousel-item');

            // Handle the "Next" button click
            $('.carousel-control-next').click(function (event) {
                if ($(event.target).hasClass('carousel-control-next-icon')) {
                    currentIndex++;
                    if (currentIndex >= items.length) {
                        currentIndex = 0;
                    }
                    showItem(currentIndex);
                }
            });

            // Handle the "Previous" button click
            $('.carousel-control-prev').click(function (event) {
                if ($(event.target).hasClass('carousel-control-prev-icon')) {
                    currentIndex--;
                    if (currentIndex < 0) {
                        currentIndex = items.length - 1;
                    }
                    showItem(currentIndex);
                }
            });

            // Function to show the selected item
            function showItem(index) {
                items.removeClass('active');
                items.eq(index).addClass('active');
            }
        });














        document.addEventListener('DOMContentLoaded', function () {
            const descriptions = document.querySelectorAll('.description-toggle');

            descriptions.forEach((desc) => {
                desc.addEventListener('click', function () {
                    const fullDescription = desc.getAttribute('data-full-description');
                    if (desc.innerHTML === fullDescription) {
                        // Description is expanded, so truncate it
                        const truncatedDescription = fullDescription.substr(0, 50);
                        desc.innerHTML = truncatedDescription + '...';
                    } else {
                        // Description is truncated, so show full description
                        desc.innerHTML = fullDescription;
                    }
                });
            });
        });


        $(document).ready(function () {
            $('.comments-section .hidden-comment').hide();

            $('.see-more-link').on('click', function (e) {
                e.preventDefault();
                $(this).closest('.comments-section').find('.hidden-comment').show();
                $(this).hide();
                $(this).siblings('.see-less-link').show();
            });

            $('.see-less-link').on('click', function (e) {
                e.preventDefault();
                $(this).closest('.comments-section').find('.hidden-comment').hide();
                $(this).hide();
                $(this).siblings('.see-more-link').show();
            });
        });


    </script>
</body>

</html>
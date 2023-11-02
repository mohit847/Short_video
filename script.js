document.addEventListener('DOMContentLoaded', function () {
    const descriptions = document.querySelectorAll('.description-toggle');

    descriptions.forEach((desc) => {
        desc.addEventListener('click', function () {
            const fullDescription = desc.getAttribute('data-full-description');
            if (desc.innerHTML === fullDescription) {
                // Description is expanded, so truncate it
                const truncatedDescription = fullDescription.substr(0, 5);
                desc.innerHTML = truncatedDescription + '...';
            } else {
                // Description is truncated, so show full description
                desc.innerHTML = fullDescription;
            }
        });
    });
});


$(document).ready(function () {
    // Initialize the carousel
    $('#videoCarousel').carousel();

    // Handle the "Next" button click
    $('.carousel-control-next').click(function () {
        $('#videoCarousel').carousel('next');
    });

    // Handle the "Previous" button click
    $('.carousel-control-prev').click(function () {
        $('#videoCarousel').carousel('prev');
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



document.addEventListener("DOMContentLoaded", function () {
    const followButton = document.querySelector("#follow-button");

    followButton.addEventListener("click", function () {
        // Check the current button text
        if (followButton.textContent === "Follow") {
            // If it's "Follow," change it to "Unfollow"
            followButton.textContent = "Unfollow";
        } else {
            // If it's "Unfollow," change it to "Follow"
            followButton.textContent = "Follow";
        }
    });
});


document.addEventListener("DOMContentLoaded", function () {
    var likeButtons = document.querySelectorAll('.like-button');

    likeButtons.forEach(function (likeButton) {
        likeButton.addEventListener('click', function (e) {
            e.preventDefault();

            var videoId = likeButton.getAttribute('data-video-id');

            if (likeButton.classList.contains('liked')) {
                alert('You have already liked this video.');
                return;
            }

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'like_video.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        var response = xhr.responseText;
                        if (response === 'Liked') {
                            var likeCountElement = likeButton.querySelector('.like-count');
                            var currentLikeCount = parseInt(likeCountElement.textContent);
                            likeCountElement.textContent = currentLikeCount + 1;

                            likeButton.classList.add('liked');
                        } else if (response === 'AlreadyLiked') {
                            likeButton.classList.add('liked');
                            alert('You have already liked this video.');
                        } else if (response === 'NotLoggedIn') {
                            alert('Please log in to like the video.');
                        } else {
                            alert('Error: ' + response);
                        }
                    } else {
                        console.log('Error: ' + xhr.status);
                    }
                }
            };

            xhr.send('video_id=' + videoId);
        });
    });
});



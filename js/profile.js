// profile.js

function check() {
    $.ajax({
        url: 'http://localhost/vendor/redis.php',
        method: 'GET',
        success: function(response) {
            if (response.data === true) {
                window.location.href = 'profile.html';
            } else {
                window.location.href = 'login.html';
            }
        },
        error: function(error) {
            console.error('Error:', error);
            window.location.href = 'login.html';
        }
    });
}
check();

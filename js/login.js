$(document).ready(function () {
    $('#login').click(function () {

        var username = document.getElementById('username').value;
        var password = document.getElementById('password').value;

        const url = 'php/login.php'; // Replace with your API endpoint URL 
        var data = { // Replace with your JSON data
            username: username,
            password: password
        };

        $.ajax({
            url: url,
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(data),
            success: function (response) {
                var res = $.parseJSON(response);
                if(res.status){
                    $('.toast-body').text(res.message);
                    $('.toast').toast('show');
                    // alert(res.message);
                    localStorage.setItem('userdata',res.userdata);
                    window.location.replace('profile.html');
                }


            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert(errorThrown);
                console.error(errorThrown);
            }
        });

    });
});
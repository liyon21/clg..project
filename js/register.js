$(document).ready(function () {
    $('#register').click(function () {

        const url = 'php/register.php'; // Replace with your API endpoint URL 
        var data = {
            name: $('#Inputtname').val(),
            email: $('#InputEmail1').val(),
            phone: $('#Inputphoneno').val(),
            password: $('#InputPassword').val()
        }

        $.ajax({
            url: url,
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(data),
            success: function (response) {
                var res = $.parseJSON(response);
                if(res.status){
                    alert(res.message)
                    window.location.replace('login.html');
                }


            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("error from server");
                console.error(errorThrown);
            }
        });

    });
});
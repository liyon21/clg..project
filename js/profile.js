
$(document).ready(function () {

    $('#checkaddress').change(function() {
        if (this.checked) {
            $('#paddress').val($('#address').val());
            $('#paddress').prop('disabled', true);
        } else {
            $('#paddress').val('');
            $('#paddress').prop('disabled', false);
        }
    });
    $('#create').click(function () {

        var gender = $('input[name="gender"]:checked').val();

        const url = 'php/profile.php'; // Replace with your API endpoint URL 
        var data = {
            id: localStorage.getItem('profile_id'),
            name: $('#name').val(),
            parent: $('#parent').val(),
            gender: gender,
            dob: $('#dob').val(),
            age: $('#age').val(),
            blood: $('#blood').val(),
            email: $('#email').val(),
            alt_email: $('#altemail').val(),
            mobile: $('#phoneno').val(),
            alt_mobile:  $('#altphone').val(),
            present_address: $('#address').val(),
            permanent_address: $('#paddress').val(),
        
        
        };

        $.ajax({
            url: url,
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(data),
            success: function (response) {
                var res = $.parseJSON(response);
                if(res.status){
                    alert(res.message)
                    window.location.replace('profile.html');
                }


            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("error from server");
                console.error(errorThrown);
            }
        });

    });

    var userdata = JSON.parse(localStorage.getItem('userdata'));
    // this.alert('hi');
    if(!localStorage.getItem('userdata')){
        // this.window.location.replace('login.html');
        this.alert(localStorage.getItem('userdata'));
        return;

    }
    
    $.ajax({
        url: "php/profile.php?email="+ userdata.username,
        method: "GET",
        dataType: "json",
        success: function(response) {
             $('#name').val(response.name)
             $('#parent').val(response.parent)
             
             if(response.gender.length > 0){
                $("input[name=gender][value=" + response.gender + "]"). prop('checked', true);
            
             }
             //  $('#gender').val(response.gender)
             $('#dob').val(response.dob)
             $('#age').val(response.age)
             $('#blood').val(response.bloodgroup)
             $('#email').val(response.email)
             $('#altemail').val(response.alt_email)
             $('#phoneno').val(response.mobile)
             $('#altphone').val(response.alt_mobile)
             $('#address').val(response.present_address)
             $('#paddress').val(response.permanent_address)
             localStorage.setItem('profile_id',response._id["$oid"]);

        },
        error: function(xhr, status, error) {
          console.error("API request failed");
        }
      });


});
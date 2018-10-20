$(document).ready(function() {
    $('#submitLogin').click(function(e) {
        $.ajax({
            type: 'POST',
            url: 'http://localhost/jQuery/login.php',
            data: {
                'username':$('#txtUsername').val(),
                'password':$('#txtPassword').val()
            }
        }).done( (data) => {
            $('#output').html(data);
        }).fail( (data) => {
            $('#output').html("failed");
        });
        e.preventDefault();
    });
});
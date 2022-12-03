$('#btn_reg').click(function(){
    check();
});

var check =()=>{
    if ($('#firstname').val() != "" && $('#lastname').val() != "" && $('#user').val() != "" && $('#pass').val() != ""){
        doRequest();
    }else{
        alert("Please fill-in empty field(s)");
    }
}
var doRequest =()=>{
    $.ajax({
        type: "POST",
        url: "./scr/router.php",
        data: {
            choice:'register',
            firstname:$('#firstname').val(),
            lastname:$('#lastname').val(),
            user:$('#user').val(),
            pass:$('#pass').val()
        },
        success: function(data){
            if (data == "200") {
                alert("Successfully Registered")
                window.location.href = "./index.html";
            } else {
                alert('Username is already registered')
            }
        }, 
        error: function (xhr, ajaxOptions, thrownError) {
            alert(thrownError);
        }
    });
}
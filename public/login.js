$('#btn_login').click(function(){
    check();
}); 

var check =()=>{
    if ($('#username').val() != "" && $('#password').val() != "") {
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
            choice:'login',
            username:$('#username').val(),
            password:$('#password').val()
        },
        success: function(data){
            console.log(data);
            if (data == "200") {
                window.location.href = "./dashboard.html";
            } else if(data == 'admin'){
                window.location.href = "./admindashboard.html";
            }
        }, 
        error: function (xhr, ajaxOptions, thrownError) {
            alert(thrownError);
        }
    });
}
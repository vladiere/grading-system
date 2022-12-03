$('#btn_submit').click(function(){
    check();
}); 

var check =()=>{
    if ($('#studentID').val() != "" && $('#subjects').val() && $('#midterms').val() != "" && $('#finals').val() != "") {
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
            choice:'update',
            subject: $('#subjects').val(),
            studentID:$('#studentID').val(),
            midterms:$('#midterms').val(),
            finals:$('#finals').val()
        },
        success: function(data){
            if (data == "200") {
                console.log(data)
                window.location.href = "./admindashboard.html";
            }else{
                alert(data);
            }
        }, 
        error: function (xhr, ajaxOptions, thrownError) {
            alert(thrownError);
        }
    });
}
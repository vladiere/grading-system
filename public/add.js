$('#btn_add').click(function(){
    check();
}); 

var check =()=>{
    if ($('#studentid').val() != "" && $('#midterms').val() != "" && $('#finals').val() != "") {
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
            choice:'addadmin',
            studentid: $('#studentid').val(),
            midterms:$('#midterms').val(),
            finals:$('#finals').val()
        },
        success: function(data){
            console.log(data)
            if (data == "200") {
                window.location.href = "./admindashboard.html";
            } else if(data == "404") {
                alert('Student ID not registered');
            } else {
                alert(data)
            }
        }, 
        error: function (xhr, ajaxOptions, thrownError) {
            alert(thrownError);
        }
    });
}
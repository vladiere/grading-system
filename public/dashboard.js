$(document).ready(function(){
    doRequest();
});

$('#logout').click(function(){
    logout();
});

var doRequest =()=>{
    $.ajax({
        type: "POST",
        url: "./scr/router.php",
        data: {
            choice:'view'
        },
        success: function(data){
            var json = JSON.parse(data);
            var str = "<table class='table table-responsive text-center text-capitalize'>" +
                        "<thead>"+
                            "<tr>"+
                                "<td>#</td>"+
                                "<td>Subjects</td>"+
                                "<td>Midterm</td>"+
                                "<td>Final</td>"+
                            "</tr>"+
                        "</thead>"
                    
            let ctr = 1;
            let myname = ''
            json.forEach(element => {
                myname = element.lastname + ', ' +element.firstname
                str += "<tr>";
                str += "<td>"+ctr+"</td>";
                str += "<td>"+element.subjects+"</td>";
                str += "<td>"+element.midterms+"</td>";
                str += "<td>"+element.finals+"</td>";
                str += "</tr>";
                ctr++;
            });
            str += "</table>"
            $('#myname').append(myname)
            $('.tbl').append(str);
        }, 
        error: function (xhr, ajaxOptions, thrownError) {
            alert(thrownError);
        }
    });
}

var logout =()=>{
    $.ajax({
        type: "POST",
        url: "./scr/router.php",
        data: {choice:'logout'},
        success: function(data){
            if (data == "200") {
                window.location.href = "./";
            }
        }, 
        error: function (xhr, ajaxOptions, thrownError) {
            alert(thrownError);
        }
    });
}
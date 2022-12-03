function searchFunc() {
    var input, filter, table, tr, td, i;
    input = document.getElementById("search");
    filter = input.value.toUpperCase();
    table = document.getElementById("studentTable");
    tr = table.getElementsByTagName("tr");
  
    for (i = 0; i < tr.length; i++) {
      td = tr[i].getElementsByTagName("td")[1];
      if (td) {
        if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
          tr[i].style.display = "";
        } else {
          tr[i].style.display = "none";
        }
      }
    }
  }


$(document).ready(function(){
    doRequest();

    $(document).on('click', '.dropstud', function() {
        var id = $(this).attr('id');

        
        var currentRow = $(this).closest("tr");
        var subj = currentRow.find("td:eq(4)").text();
        dropStudent(id, subj)
    })

    $(document).on("click", "#studlist", function() {
        $('#studentTable').load(location.href + ' #studentTable')
        displayStudList()
    })

    $(document).on("click", "#back", function() {
        $(location).attr('href', './admindashboard.html')
    })
});

$('#logout').click(function(e){
    e.preventDefault();
    logout();
});

var dropStudent = (id,subj) => {
    $.ajax({
        type: 'POST',
        url: './scr/router.php',
        data: {
            choice: 'dropstud',
            id: id,
            subj, subj
        },
        success: (data) => {
            if(data == "200"){
                $('#studentTable').load(location.href + ' #studentTable')
                doRequest()
                alert('Dropped Student Success')
            }
        },
        error: (xhr, ajaxOptions, thrownError) => {console.log(thrownError)}
    })
}

var displayStudList =()=>{
    $.ajax({
        type: "POST",
        url: "./scr/router.php",
        data: {
            choice:'viewall'
        },
        success: function(data){
            var json = JSON.parse(data); 
            var str = "<table class='table table-responsive text-center text-capitalize ' id='studentTable'>" +
                        "<thead>"+
                            "<tr>"+
                                "<td>#</td>"+
                                "<td>Student ID</td>"+
                                "<td>Firstname</td>"+
                                "<td>Lastname</td>"
                            "</tr>"+
                        "</thead>"
            let ctr = 1;
            json.forEach(element => {
                str += "<tr>";
                str += "<td>"+ctr+"</td>";
                str += "<td>"+element.ID+"</td>";
                str += "<td>"+element.firstname+"</td>";
                str += "<td>"+element.lastname+"</td>"
                str += "</tr>";
                ctr++;
            });
            str += "</table>"
            $('#menu #studlist').attr('id', 'back').text('Back')
            $('.tbl').append(str);
        }, 
        error: function (xhr, ajaxOptions, thrownError) {
            alert(thrownError);
        }
    });
}

var doRequest =()=>{
    $.ajax({
        type: "POST",
        url: "./scr/router.php",
        data: {
            choice:'viewadmin'
        },
        success: function(data){
            var json = JSON.parse(data); 
            var str = "<table class='table table-responsive text-center text-capitalize ' id='studentTable'>" +
                        "<thead>"+
                            "<tr>"+
                                "<td>#</td>"+
                                "<td>Student ID</td>"+
                                "<td>Firstname</td>"+
                                "<td>Lastname</td>"+
                                "<td>Midterms</td>"+
                                "<td>Finals</td>"+
                                "<td>Action</td>"
                            "</tr>"+
                        "</thead>"
            let ctr = 1;
            let subj = ''
            json.forEach(element => {
                subj = element.subjects
                str += "<tr>";
                str += "<td>"+ctr+"</td>";
                str += "<td>"+element.user_id+"</td>";
                str += "<td>"+element.firstname+"</td>";
                str += "<td>"+element.lastname+"</td>"
                str += "<td>"+element.midterms+"</td>";
                str += "<td>"+element.finals+"</td>";
                str += "<td><a type='button' class='btn btn-danger me-2 dropstud' id='"+element.user_id+"'>Drop</a></td>"
                str += "</tr>";
                ctr++;
            });
            str += "</table>"
            $('#subj').append(subj.toUpperCase())
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
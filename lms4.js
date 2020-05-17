$(document).ready(function () {

  $("#refresh").click(function(){
    
    
  });
  $('#tableid').hide();
  $("#find").on('click',function (e) {
    $('tbody').html('');
    e.preventDefault();
    var checkin=$("#checkin").val();
    $.ajax({
      url: "lms4.php",
      type: "POST",
      data: {"checkin":checkin},
      success: function (data) {
        if(data=='noloans'){
          $('.alert-danger').html('');
          $('.alert-danger').append('No Book loans');
          $(".alert-danger").fadeIn();
          $(".alert-danger").fadeOut(5000);
        }
        else if(data!='noloans'){
        data=$.parseJSON(data);
        $.each(data,function(key,value){
          var name=value.fname+' '+value.lname;
					var tablerow= '<tr style={cursor: pointer}><td id="loanid">'+value.Loan_id+'</td><td>'+value.Card_id+'</td><td id="isbn">'+value.Isbn+'</td><td>'+name+'</td><td>'+value.Title+'</td></tr>';
					$("tbody").append(tablerow);
          $("#tableid").show();
					console.log(name);
				});
      }
      }
    });
  });
  $("tbody").on('click','tr', function () {
    var loanid=$(this).children('#loanid').text();
    var isbn=$(this).children('#isbn').text();
    $('.selectcheckin').removeClass('selectcheckin');
    $(this).addClass('selectcheckin');
    $.ajax({
      url: 'lms4.php',
      type: 'POST',
      data: {'loanid': loanid},
      success: function (data) {
        if (data=='nofine') {
          var payfine="<h3>No fine</h3><button id='confirmcheckin' value='"+loanid+"' data-isbn='"+isbn+"'>checkin</button>"
        }
        else {
          var payfine='<h3>'+data+' $</h3><button value="'+loanid+'" id="payconfirmcheckin" data-isbn="'+isbn+'">payfine and checkin</button></div>'
        }
        $("#fineDetails").html('');
        $("#fineDetails").append(payfine);
        $("#myModal").modal();
      }
    });
    // console.log(loanid);

  });
  $('#fineDetails').on('click','#confirmcheckin',function () {
    var checkinid=$(this).attr('value');
    var isbn=$(this).attr('data-isbn');
    $.ajax({
      url: 'lms4.php',
      type: 'POST',
      data: {'checkinid': checkinid, 'isbn': isbn},
      success: function (data) {
        $('#fineDetails').html('');
        $('.alert-info').html('');
        $('.alert-info').append(data);
        $(".alert-info").fadeIn();
        $(".alert-info").fadeOut(3000);
        $('.selectcheckin').remove();
      }
    });
  });
  $('#fineDetails').on('click','#payconfirmcheckin',function () {
    var paycheckinid=$(this).attr('value');
    var isbn=$(this).attr('data-isbn');
    $.ajax({
      url: 'lms4.php',
      type: 'POST',
      data: {'paycheckinid': paycheckinid, 'isbn': isbn},
      success: function (data) {
        $('#fineDetails').html('');
        $('.alert-info').html('');
        $('.alert-info').append(data);
        $(".alert-info").fadeIn();
        $(".alert-info").fadeOut(3000);
        $('.selectcheckin').remove();
      }
    });
  });
});

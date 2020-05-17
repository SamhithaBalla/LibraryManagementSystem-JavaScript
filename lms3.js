$(document).ready(function(){
	

	$("#registerbutton").on('click',function(e){
		e.preventDefault();

		

		var v=$("#userform").serialize();
		
			$.ajax({
				url:"lms3.php",
				type:"POST",
				dataType: "JSON",
				data:v,

				success:function(data){
					if(data=="samessnerror"){
						$("#msginfo").html("Error: User with the same SSN already exists");
						$("#msginfo").css("color","red");

					}
					else if(data=="nullfields"){
						
						$("#msginfo").html("Please fill all the text fields");
						$("#msginfo").css("color","red");
					}
					else{
						//data=$.parseJSON(data);

						
						$.each(data,function(key,value){
							var cardnum=value.card_no;
							var name1=value.fname;
							var name2=value.lname;


							var msginfo1= "Registration Successful. Your Library ID, "+name1+': '+cardnum;

							$("#msginfo").html(msginfo1);
							$("#msginfo").css("color","green");
						});
					}
				},
				error:function(){
					alert("Connection failed");
				}

			});


		

	});


});

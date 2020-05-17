
$(document).ready(function(){
	$("#tableid").hide();
	$("#div3").hide();
	$("#checkoutb").hide();


	$("#searchbutton").click(function(e){
		e.preventDefault();
		$("#tbody0").html("");
		$("#div3").hide();
		$("#checkoutb2").hide();

		var data1=$("#search1").val();


		$.ajax({

			url:"lms.php",
			type:"POST",
			dataType: "JSON",
			data:{index: data1},


			success:function(data){
				

				//console.log("in js",data);
				if(data=="blankerror"){
					alert("Please enter a non-null value to search");
				}

				//data=$.parseJSON(data);
				if(data=="NonameforBook"){
					alert("No books exist for the given input");
				}
				
				var rowid=0;
				$.each(data,function(key,value){
					var bookid=value.ISBN;
					var titles=value.Title;
					var authors=value.fullname;
					var available=value.availability;
					if(available=="Not available"){	
						var tablerow= '<tr><td>'+bookid+'</td><td>'+titles+'</td><td>'+authors+'</td><td>'+available+'</td><td><input type="checkbox" disabled="disabled"/></td></tr>';
					}
					else{
						var tablerow= '<tr><td>'+bookid+'</td><td>'+titles+'</td><td>'+authors+'</td><td>'+available+'</td><td><input type="checkbox"/></td></tr>';
					}	
					$("#tableid").append(tablerow);
					$("#tableid").show();
					rowid++;
				});
				
				$("td").click(function(){
					$("#checkoutb").show();

				});


				$("#checkoutb").click(function(){
					var isbnarray=[];
					$("#tableid").find("tr").each(function(){
						
						if($(this).find('input[type=checkbox]').is(":checked")){

							var sam=$(this).find("td").eq(0).html();

							var title1=$(this).find("td").eq(1).html();
							var author1=$(this).find("td").eq(2).html();

							

							isbnarray.push(sam);
							
						}
					});

					
					if(isbnarray.length>3){
						alert("Sorry, you cannot check out more than 3");
					}
					else{
						$("#div3").show();
						$("#checkoutb").html("Checkout");
						$("#checkoutb").attr("id","checkoutb2");
						
						
						$("#checkoutb2").click(function(){

							var isbnarray1=[];
							$("#tableid").find("tr").each(function(){
						
								if($(this).find('input[type=checkbox]').is(":checked")){

									var samm1=$(this).find("td").eq(0).html();

									var title2=$(this).find("td").eq(1).html();
									var author2=$(this).find("td").eq(2).html();

							

									isbnarray1.push(samm1);
							
								}
							});
							
							var libraryid=$("#libid").val();
							if(libraryid==''){
								alert("Please provide a valid library id");
							}
						 	var length1=isbnarray1.length;
						 	
							$.ajax({
								url:"lms2.php",
								type:"POST",
								dataType: "JSON",
								data:{index1: isbnarray1[0],index2:isbnarray1[1],index3:isbnarray1[2],index4:libraryid,index5:length1},


								success:function(data1){
									//console.log(data1);
									//data=JSON.parse(data);

									if(data1=="nolibid"){
										alert(" Please register to check out");
									}


									if(data1=="Success"){

										alert("Checked Out Successfully!");
									}

									if(data1=="booklimiterror"){
										alert("Can't check out more than 3");		
									}
									
									// else{

									// 	alert("book limit error");
									// }						
								}


							});


						});

					}

					
				});



				



			},
			error:function(){
				alert("Error Loading the file\n");
			}
			

		});


	});






});

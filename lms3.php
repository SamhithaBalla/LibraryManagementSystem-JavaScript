<?php
	session_start();
	
	$conn = new mysqli("localhost","root","","maindb") or die("Unable to connect");



	if ($conn->connect_error) {
		die("Connection Failed: ".$conn->connect_error);

	}


	if($_SERVER["REQUEST_METHOD"]=="POST"){

		

		$t1=$_POST['ssn'];
		$t2=$_POST['fname'];
		$t3=$_POST['lname'];
		$t4=$_POST['mail'];
		$t5=$_POST['address'];
		$t6=$_POST['city'];
		$t7=$_POST['state'];
		$t8=$_POST['phone'];






			if($t1=="" || $t2=="" || $t3=="" || $t4==""|| $t5==""|| $t6==""|| $t7==""|| $t8==""){
				$nullerr="nullfields";
				echo json_encode($nullerr);
			}
			else{
			$sql1="insert into borrower (ssn,fname,lname,email,address,city,state,phone)
			values ('$t1','$t2','$t3','$t4','$t5','$t6','$t7','$t8');"; 					//inserting the fields into database
			$sql2="select * from borrower where ssn='$t1';"; //retrieving lib_id for the new user and  checking whether the given ssn already exists in the database.


			$result2=$conn->query($sql2);
			if($result2->num_rows>0){
				$ssnerr="samessnerror";
				echo json_encode($ssnerr);

			}
			else{
				$result1=$conn->query($sql1);

				$searcharray2=array();

				$result2=$conn->query($sql2);
				
				while($row3=$result2->fetch_assoc()){

				 	$searcharray2[]=$row3;

				}

				echo json_encode($searcharray2);

			}
			 
            }




	}


?>

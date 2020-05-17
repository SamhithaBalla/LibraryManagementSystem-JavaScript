<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	session_start();
	
	$conn = new mysqli("localhost","root","","maindb") or die("Unable to connect");
	
 		$checkoutdate=date("Y-m-d");
		$duedate=Date('Y-m-d', strtotime("+14 days"));
	
	if ($conn->connect_error) {
		die("Connection Failed: ".$conn->connect_error);
		
	}
	
	
	
	if($_SERVER["REQUEST_METHOD"]=="POST"){

		if (isset($_POST['index1'])) {
    		$bookid1 = $_POST['index1'];
		}
		if (isset($_POST['index2'])) {
    		$bookid2 = $_POST['index2'];
		}
		if (isset($_POST['index3'])) {
    		$bookid3 = $_POST['index3'];
		}
		if (isset($_POST['index4'])) {
    		$cardid = $_POST['index4'];
		}
		if (isset($_POST['index5'])) {
    		$length = $_POST['index5'];
		}

		 	$noid="select * from borrower where card_no='$cardid'";
		 	$result=$conn->query($noid);
		 	if(mysqli_num_rows($result)==0)
		 	{
		 		$nolibid="nolibid";
		 		echo json_encode($nolibid);
		 	}

			else{

			
			$sql1="select loan_id from book_loans where card_id='$cardid' and date_in='0000-00-00';"; 
			$result1=$conn->query($sql1);

			$totalbooks=($length)+($result1->num_rows);
			
			if($totalbooks>3){

				$bookerr="booklimiterror";
				echo json_encode($bookerr);
				
			}
			
			
			
			
			else{

				if(isset($bookid1)!=''){
					$sql2="insert into book_loans (Isbn,Card_id,Date_out,Due_date,Date_in)
					values ('$bookid1','$cardid','$checkoutdate','$duedate','0000-00-00'); ";

					$sql3="update book set availability='Not available' where ISBN='$bookid1' ;";

					

					$result2=$conn->query($sql2);

					$result3=$conn->query($sql3);


				}

				if(isset($bookid2)!=''){
					$sql2="insert into book_loans (Isbn,Card_id,Date_out,Due_date,Date_in)
					values ('$bookid2','$cardid','$checkoutdate','$duedate','0000-00-00'); ";

					$sql3="update book set availability='Not available' where ISBN='$bookid2' ;";

					$result2=$conn->query($sql2);

					$result3=$conn->query($sql3);
				}
				if(isset($bookid3)!=''){
					$sql2="insert into book_loans (Isbn,Card_id,Date_out,Due_date,Date_in)
					values ('$bookid3','$cardid','$checkoutdate','$duedate','0000-00-00'); ";

					$sql3="update book set availability='Not available' where ISBN='$bookid3' ;";

					$result2=$conn->query($sql2);

					$result3=$conn->query($sql3);
				}
				
				$sql4="insert into fines 
					select book_loans.loan_id,'$cardid',0,0 from Book_loans where card_id='$cardid' and date_in='0000-00-00' and date_out=CURRENT_DATE() and loan_id not in (select loan_id from `fines`);
					";
				$result4=$conn->query($sql4);

				$succ="Success";
				echo json_encode($succ);
				//echo "Success";
				
			}
			
		
		}
		
	}

?>
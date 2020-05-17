<?php
session_start();
$conn = new mysqli("localhost","root","","maindb") or die("Unable to connect");
if ($conn->connect_error) {
  die("Connection Failed: ".$conn->connect_error);
}
if(isset($_POST['checkin'])){
  $search=$_POST['checkin'];
  $sql1="select book_loans.Loan_id, book_loans.Isbn, book_loans.Card_id, borrower.fname, borrower.lname,book.Title FROM book_loans LEFT JOIN borrower ON book_loans.Card_id=borrower.card_no LEFT JOIN book ON book_loans.Isbn=book.Isbn where (book_loans.Isbn='$search' or book_loans.Card_id='$search' or borrower.fname like '%$search%' or borrower.lname like '%$search%') AND book_loans.Date_in='0000-00-00';";
  $result1=$conn->query($sql1);
  if($result1->num_rows>0){
    while($row1=$result1->fetch_assoc()){
$searcharray[]=$row1;

    }
    //echo $searcharray;
    echo json_encode($searcharray);
  }
  else if($result1->num_rows==0){
    echo "noloans";
  }
};
if (isset($_POST['loanid'])) {
  $loanid=$_POST['loanid'];
  $sql2="SELECT Date_out, Due_date, Card_id, Isbn FROM book_loans WHERE Loan_id='$loanid'";
  $result=$conn->query($sql2);
  if($result->num_rows>0){
    $row1=$result->fetch_assoc();
      $dateout=$row1['Date_out'];
      $duedate=$row1['Due_date'];
      $cardid=$row1['Card_id'];
      $checkindate=date("Y-m-d");
      $dateout=strtotime($dateout);
      $duedate=strtotime($duedate);
      $checkindate=strtotime($checkindate);
      round(($checkindate-$dateout) / 86400);
    //echo $searcharray;
    // echo $dateout.' '.$duedate.' '.$checkindate;
    if(round(($checkindate-$dateout) / 86400)<15){
      echo 'nofine';
    }
    else {
      $fine=round(($checkindate-$duedate) / 86400)*0.25;
      $sql3="SELECT Loan_id FROM fines WHERE Loan_id='$loanid'";
      $result=$conn->query($sql3);
      if($result->num_rows>0){
       $sql4="UPDATE fines SET Fine_amt='$fine' WHERE Loan_id='$loanid'";
      // $conn->query($sql4);
      }
    else {
      $sql4="INSERT INTO fines (Loan_id,Card_id,Fine_amt,Paid) VALUES ('$loanid','$cardid','$fine',0)";
    }
      $conn->query($sql4);
      echo $fine;
        }
  }
}
if (isset($_POST['checkinid'])) {
  $checkinid=$_POST['checkinid'];
  $isbn=$_POST['isbn'];
  $date=date("Y-m-d");
  $sql5="UPDATE book_loans SET Date_in='$date' WHERE Loan_id='$checkinid'";
  $sql6="UPDATE book SET Availability='Available' WHERE Isbn='$isbn'";
  $sql8="DELETE FROM fines WHERE Loan_id='$checkinid'";
  $conn->query($sql5);
  $conn->query($sql6);
  $conn->query($sql8);
  echo "Checked in successfully";
}
if (isset($_POST['paycheckinid'])) {
  $paycheckinid=$_POST['paycheckinid'];
  $isbn=$_POST['isbn'];
  $date=date("Y-m-d");
  $sql5="UPDATE book_loans SET Date_in='$date' WHERE Loan_id='$paycheckinid'";
  $sql6="UPDATE fines SET Paid=1 WHERE Loan_id='$paycheckinid'";
  $sql7="UPDATE book SET Availability='Available' WHERE Isbn='$isbn'";
  $conn->query($sql5);
  $conn->query($sql6);
  $conn->query($sql7);
  echo "Checked in successfully";
}
?>

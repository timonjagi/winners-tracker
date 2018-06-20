<? php
//database connection
if(isset($_POST['submit']))
{
$con= mysqli_connect("localhost","root","","promotion_winners");

if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

//insert into database
$sql = "INSERT INTO winnerslist (fname, lname, idnumber, promotion, show, presenter, prize, telephone) VALUES ('$_POST['firstname']', '$_POST['lastname'], '$_POST['idnumber']', '$_POST['promotion']', '$_POST['show']', '$_POST['presenter']', '$_POST['prizewon']', '$_POST['telephone']');"

if (!mysqli_query($con,$sql))
  {
  die('Error: ' . mysqli_error($con));
  }
echo "1 record added";

mysql_close($connection)
}
?>

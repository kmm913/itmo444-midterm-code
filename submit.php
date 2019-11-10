
<?php include "../inc/dbinfo.inc"; ?>
<html>
<body>
<h1>Sample page</h1>
<?php

  /* Connect to MySQL and select the database. */
  $connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);

  if (mysqli_connect_errno()) echo "Failed to connect to MySQL: " . mysqli_connect_error();

  $database = mysqli_select_db($connection, DB_DATABASE);

  /* Ensure that the EMPLOYEES table exists. */
  VerifyMidtermDataTable($connection, DB_DATABASE);

  /* If input fields are populated, add a row to the EMPLOYEES table. */
  $email = htmlentities($_POST['EMAIL']);
  $phone = htmlentities($_POST['PHONE']);

  if (strlen($email) || strlen($phone)) {
    AddMidtermData($connection, $email, $phone);
  }
?>

<!-- Input form -->
<form action="<?PHP echo $_SERVER['SCRIPT_NAME'] ?>" method="POST">
  <table border="0">
    <tr>
      <td>EMAIL</td>
      <td>PHONE</td>
      <td>FILE</td>
      <td>RAW_URL</td>
      <td>FINISHED_URL</td>
      <td>STATUS_CODE</td>
      <td>SUBSCRIBED</td>
    </tr>
    <tr>
      <td>
        <input type="text" name="EMAIL" />
      </td>
      <td>
      <input type="tel" id="phone" name="PHONE" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" required />
      </td>
      <td>
        <input type="file" name="FILE" accept="image/*" />
      </td>
      <td>
        <input type="url" name="RAW_URL" />
      </td>
      <td>
        <input type="url" name="FINISHED_URL" />
      </td>
      <td>
        <input type="text" name="STATUS_CODE" />
      </td>
      <td>
        <input type="text" name="SUBSCRIBED" />
      </td>
      <td>
        <input type="submit" value="Add Data" />
      </td>
    </tr>
  </table>
</form>

<!-- Display table data. -->
<table border="1" cellpadding="2" cellspacing="2">
  <tr>
    <td>ID</td>
    <td>EMAIL</td>
    <td>PHONE</td>
    <td>FILE</td>
    <td>RAW_URL</td>
    <td>FINISHED_URL</td>
    <td>STATUS_CODE</td>
    <td>SUBSCRIBED</td>
  </tr>

<?php

$result = mysqli_query($connection, "SELECT * FROM midtermData");

while($query_data = mysqli_fetch_row($result)) {
  echo "<tr>";
  echo "<td>",$query_data[0], "</td>",
       "<td>",$query_data[1], "</td>",
       "<td>",$query_data[2], "</td>";
       "<td>",$query_data[3], "</td>";
       "<td>",$query_data[4], "</td>";
       "<td>",$query_data[5], "</td>";
       "<td>",$query_data[6], "</td>";
       "<td>",$query_data[7], "</td>";
  echo "</tr>";
}
?>

</table>

<!-- Clean up. -->
<?php

  mysqli_free_result($result);
  mysqli_close($connection);

?>

</body>
</html>


<?php

/* Add an employee to the table. */
function AddMidtermData($connection, $email, $phone) {
   $n = mysqli_real_escape_string($connection, $email);
   $a = mysqli_real_escape_string($connection, $phone);

   $query = "INSERT INTO EMPLOYEES (NAME, ADDRESS) VALUES ('$n', '$a');";

   if(!mysqli_query($connection, $query)) echo("<p>Error adding employee data.</p>");
}

/* Check whether the table exists and, if not, create it. */
function VerifyMidtermDataTable($connection, $dbName) {
  if(!TableExists("midtermData", $connection, $dbName))
  {
     $query = "CREATE DATABASE midterm;
     USE midterm;
     CREATE TABLE midtermData 
     (id int not null auto_increment, 
     email varchar(200) not null, 
     phone varchar(20) not null, 
     localfilename varchar(255) not null, 
     s3rawurl varchar(255) not null, 
     s3finishedurl varchar(255) not null, 
     statuscode int not null, 
     issubscribed int not null, 
     constraint midterm_pk primary key (id));";

     if(!mysqli_query($connection, $query)) echo("<p>Error creating table.</p>");
  }
}

/* Check for the existence of a table. */
function TableExists($tableName, $connection, $dbName) {
  $t = mysqli_real_escape_string($connection, $tableName);
  $d = mysqli_real_escape_string($connection, $dbName);

  $checktable = mysqli_query($connection,
      "SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_NAME = '$t' AND TABLE_SCHEMA = '$d'");

  if(mysqli_num_rows($checktable) > 0) return true;

  return false;
}
?>                        
                
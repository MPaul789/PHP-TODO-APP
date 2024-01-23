<?php
session_start();

if(!isset($_SESSION ["user"])){
    header("location: login.php");
}
$servername = "localhost";
$username = "root";
$password = "";
$database = "notes";
// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

//Delete operation
if(isset($_GET['delete'])){
  $sno = $_GET['delete'];
 
  $sql = "DELETE FROM `notes` WHERE `Sno` = $sno ";
  $result = mysqli_query($conn, $sql);
  
}
if($_SERVER['REQUEST_METHOD'] == 'POST'){
  if(isset($_POST['snoEdit'])){
   //UPDATE
  
  $Status = $_POST["statusEdit"];
  $sno =$_POST["snoEdit"];

  $sql = "UPDATE `notes` SET `Status` = '$Status' WHERE `notes`.`sno` = $sno ";
  $result =mysqli_query($conn, $sql);
}
  else{
  $Task = $_POST["Task"];
  $Priority = $_POST["priority"];
  $Status = $_POST["status"];

  $sql = "INSERT INTO `notes` ( `Task`, `Priority`, `Status`) VALUES ( '$Task', '$Priority', '$Status')";
  $result =mysqli_query($conn, $sql);

  if($result){
    $insert =True;

  }
  else{
   echo " unsuccessfull " .mysqli_error($conn);
  }
  }
}


 ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Todo</title>
  <link rel="shortcut icon" href="favicon.png">
  <link rel="stylesheet" href="style.css" />
  
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> 
<body>
 


<!-- Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Edit Task</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form action ="/TODO/index.php" method ="POST" >
      <input type="hidden" name= "snoEdit" id="snoEdit">
     <div class="form-group">
      <label for="Task">Task</label>
      <input  type="text" class="form-control" id ="TaskEdit" name="TaskEdit" placeholder="Task Name" disabled />
    </div>
    <div class="form-group">
      <label  for="priority">Priority </label>
      <select class="form-control" id="priorityEdit" name="priorityEdit"  disabled>
        <option disabled="" selected="" value="">Task Priority</option>
        <option value="Normal">Normal</option>
        <option value="urgent">Urgent</option>
        <option value="others">Others</option>
        
      </select>
    </div>
    <div class="form-group">
      <label  for="status">Status</label>
      <select class="form-control" id ="statusEdit" name="statusEdit"  required>
        <option disabled="" selected="" value="">Task Priority</option>
        <option value="Completed">Completed</option>
        <option value="Pending">Pending</option>
        </select>
    </div>
    <button class="btn btn-primary" name="button" type="submit">Update Task</button>
  </form>
      </div>
    </div>
  </div>
</div>
 
<nav class="navbar navbar-inverse fixed-top">
  <div class="container-fluid">
    <div class="navbar-header navbar-sticky">
      <a class="navbar-brand" href="index.php">TODO APP
    </div>
   
    <ul class="nav navbar-nav navbar-right">
      <li><a href="signin1.php"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
      <li><a href="logout.php"><span class="glyphicon glyphicon-log-in"></span> Logout</a></li>
    </ul>
  </div>
</nav>


<form action ="/TODO/index.php" method ="POST" class="container">
    <div class="container-small-unit">
      <label class="label" for="">Task</label>
      <input class="input" type="text" id ="Task" name="Task"placeholder="Task Name" required />
    </div>
    <div class="container-small-unit">
      <label class="label" for="">Priority 
      </label>
      <select class="form-control" id="priority" name="priority"  required>
        <option disabled="" selected="" value="">Task Priority</option>
        <option value="Normal">Normal</option>
        <option value="urgent">Urgent</option>
        <option value="others">Others</option>
        
      </select>
    </div>
    <div class="container-small-unit">
      <label class="label" for="">Status
      </label>
      <select class="form-control " id ="status" name="status"  required>
        <option disabled="" selected="" value="" >Status</option>
        <option value="Completed">Completed</option>
        <option value="Pending">Pending</option>
        </select>
    </div>
    
    <button class= ' btn btn-sm btn-primary' type="submit">Add task</button>
    <!-- <button class="btn btn-primary" name="button" type="submit">Add Task</button> -->
  </form>

  
  <div class="container" id="table">

  <!-- creating the table -->
  <table class="table" id="myTable">
  <thead>
    <tr>
      <th scope="col">sno</th>
      <th scope="col">Task</th>
      <th scope="col">Priority</th>
      <th scope="col">Status</th>
      <th scope="col">Actions</th>
    </tr>
  </thead>
  <tbody>
  <?php 
// To display all the data from the DB
$sql ="SELECT * FROM `notes` " ;
$result= mysqli_query($conn, $sql);

if (isset($_GET['page_no']) && $_GET['page_no']!="") {
	$page_no = $_GET['page_no'];
	} else {
		$page_no = 1;
        }
 
	$total_records_per_page = 3;
    $offset = ($page_no-1) * $total_records_per_page;
	$previous_page = $page_no - 1;
	$next_page = $page_no + 1;
	$adjacents = "2"; 
 
	$result_count = mysqli_query($conn,"SELECT COUNT(*) As total_records FROM notes ");
	$total_records = mysqli_fetch_array($result_count);
	$total_records = $total_records['total_records'];
    $total_no_of_pages = ceil($total_records / $total_records_per_page);
	$second_last = $total_no_of_pages - 1; // total page minus 1
$result = mysqli_query($conn,"SELECT * FROM notes LIMIT $offset, $total_records_per_page");
    

    

$sno = 0;
while($row= mysqli_fetch_assoc($result)){
      $sno =$sno + 1;
      echo "<tr>
      
      <th scope='row'>". $row['sno']."</th>
      <td>". $row['Task']."</td>
      <td>". $row['Priority']."</td>
      <td>". $row['Status']."</td>
      <td> <button class= 'edit btn btn-sm btn-primary' id=".$row['sno'].">Edit</button> <button class= 'delete btn btn-sm btn-primary'id= d".$row['sno'].">Delete</button> </td>
       </tr>";
    }
  




?>
   

    
</tbody> 

</table>
<div style='padding: 10px 20px 0px; border-top: dotted 1px #CCC;'>
<strong>Page <?php echo $page_no." of ".$total_no_of_pages; ?></strong>
</div>
 
<ul class="pagination">
    
	<li <?php if($page_no <= 1){ echo "class='disabled'"; } ?>>
	<a <?php if($page_no > 1){ echo "href='?page_no=$previous_page'"; } ?>>Previous</a>
	</li>
       
    <?php 
	if ($total_no_of_pages <= 10){  	 
		for ($counter = 1; $counter <= $total_no_of_pages; $counter++){
			if ($counter == $page_no) {
		   echo "<li class='active'><a>$counter</a></li>";	
				}else{
           echo "<li><a href='?page_no=$counter'>$counter</a></li>";
				}
        }
	}
	elseif($total_no_of_pages > 10){
		
	if($page_no <= 4) {			
	 for ($counter = 1; $counter < 8; $counter++){		 
			if ($counter == $page_no) {
		   echo "<li class='active'><a>$counter</a></li>";	
				}else{
           echo "<li><a href='?page_no=$counter'>$counter</a></li>";
				}
        }
		echo "<li><a>...</a></li>";
		echo "<li><a href='?page_no=$second_last'>$second_last</a></li>";
		echo "<li><a href='?page_no=$total_no_of_pages'>$total_no_of_pages</a></li>";
		}
 
	 elseif($page_no > 4 && $page_no < $total_no_of_pages - 4) {		 
		echo "<li><a href='?page_no=1'>1</a></li>";
		echo "<li><a href='?page_no=2'>2</a></li>";
        echo "<li><a>...</a></li>";
        for ($counter = $page_no - $adjacents; $counter <= $page_no + $adjacents; $counter++) {			
           if ($counter == $page_no) {
		   echo "<li class='active'><a>$counter</a></li>";	
				}else{
           echo "<li><a href='?page_no=$counter'>$counter</a></li>";
				}                  
       }
       echo "<li><a>...</a></li>";
	   echo "<li><a href='?page_no=$second_last'>$second_last</a></li>";
	   echo "<li><a href='?page_no=$total_no_of_pages'>$total_no_of_pages</a></li>";      
            }
		
		else {
        echo "<li><a href='?page_no=1'>1</a></li>";
		echo "<li><a href='?page_no=2'>2</a></li>";
        echo "<li><a>...</a></li>";
 
        for ($counter = $total_no_of_pages - 6; $counter <= $total_no_of_pages; $counter++) {
          if ($counter == $page_no) {
		   echo "<li class='active'><a>$counter</a></li>";	
				}else{
           echo "<li><a href='?page_no=$counter'>$counter</a></li>";
				}                   
                }
            }
	}
?>
    
	<li <?php if($page_no >= $total_no_of_pages){ echo "class='disabled'"; } ?>>
	<a <?php if($page_no < $total_no_of_pages) { echo "href='?page_no=$next_page'"; } ?>>Next</a>
	</li>
    <?php if($page_no < $total_no_of_pages){
		echo "<li><a href='?page_no=$total_no_of_pages'>Last &rsaquo;&rsaquo;</a></li>";
		} ?>
</ul>
 
 

</div>
  <script>
    edits = document.getElementsByClassName('edit');
    Array.from(edits).forEach((element) => {
      element.addEventListener("click", (e) =>{
        console.log("edit ", );
        tr = e.target.parentNode.parentNode;
        Task = tr.getElementsByTagName("td")[0].innerText;
        priority = tr.getElementsByTagName("td")[1].innerText;
        status= tr.getElementsByTagName("td")[2].innerText;
        console.log(Task, priority, status);
        TaskEdit.value = Task;
        priorityEdit.value = priority;
        statusEdit.value = status;
        snoEdit.value= e.target.id;
        console.log(e.target.id);
        $('#editModal').modal('toggle');
        

      })
    })
    </script>
    <script>

    deletes = document.getElementsByClassName('delete');
    Array.from(deletes).forEach((element) => {
      element.addEventListener("click", (e) =>{
        console.log("delete ", );
        sno = e.target.id.substr(1, );
        
    
        
        if (confirm("Press a button")){
          console.log("yes");
          window.location =`/TODO/index.php?delete=${sno}`;
        }
        else{
          console.log("no");
        }
      })
    })
  </script> 

</body>

</html>
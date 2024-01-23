$num = mysqli_num_rows($result);
//echo $num;

$numberPages=3;
$totalPages=ceil($num/$numberPages);
//echo $totalPages;
//Creating Pagination buttons

for($btn=1;$btn<=$totalPages;$btn++){
  echo '<button style="display:inline;" ><a href="index.php?page='.$btn.' " class="text-light">'.$btn.'</a></button>';
}
if(isset($_GET['page'])){
  $page=$_GET['page'];
  //echo $page;
}else{
  $page=1;
}
$startinglimit=($page-1)* $numberPages;
$query= "SELECT * FROM `notes` LIMIT  " .$startinglimit.','.$numberPages;
$result= mysqli_query($conn, $query);
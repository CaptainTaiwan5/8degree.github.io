<?php
session_start();
echo '<a href="logout.php">登出</a>  <br><br>';
echo '<a href="select.php">回前頁</a>  <br><br>';
if($_SESSION['username']!=null){

}
else{
	echo "<script>alert('您尚未登入'); 
	location.href = 'account.php';</script>";
}
include("mysql.inc.php");
$perpage=10;  // 每頁顯示 10 筆
$sql="SELECT count(留言編號) FROM guestbook";
$result=mysqli_query($conn, $sql);
$count=4 ;
//取得查詢結果的筆數
$totalrow=mysqli_fetch_array($result)[0];
$totalpage=ceil($totalrow/$perpage);  // 計算總頁數

// 根據 $_GET['page'] 參數值決定從第幾頁開始顯示
// 代表頁次的變數 $page 由 1 起算
if(empty($_GET['page']) || !is_numeric($_GET['page'])
    ||  $_GET['page']<1 || $_GET['page']>$totalpage ) 
	$page=1;
else 
	$page=$_GET['page'];

// 根據 $_GET['order'] 參數值決定排序方式
if(empty($_GET['order']) || !is_numeric($_GET['order'])||  $_GET['order']<1 || $_GET['order']>3) {
	$field='留言編號'; // SQL 查詢時的排序參數 
	$order=0;          // 建立頁次連結時使用的參數
}
else if($_GET['order']==1) {
	$field='姓名';
	$order=1;
}
else if($_GET['order']==2) {
    $field='留言';
	$order=2;
}
else if($_GET['order']==3) {
    $field='日期時間';
	$order=3;
}


// 設定查詢 LIMIT 子句的第 1 個參數
$start=($page-1)*$perpage;  	
	
//查詢【students】資料表的記錄
$sql = "SELECT * FROM guestbook ORDER BY $field "."LIMIT $start, $perpage";
echo '<br>';

$result=mysqli_query($conn, $sql);

//取得查詢結果
while($row=mysqli_fetch_array($result)) 
	$data[]=$row;
?>
<!DOCTYPE html>
<html>
<head>
  <title>8度留言資料表</title>
  <meta charset="UTF-8">
  <style>
    table {border:1px solid black; width:1000px;text-align:center}
	.grey {background-color:lightgrey}
	#h1,#h3 {width:20%;color:#FF8888}
  </style>
</head>
<body align="center">
  
<p>目前資料筆數：<?php echo $totalrow;?> </p>
<table align="center">
<tr>
<th id="h1"><a href="<?php $_SERVER['PHP_SELF']?>?order=0">留言編號</a></th>
<th id="h3"><a href="<?php $_SERVER['PHP_SELF']?>?order=1">姓名</a></th>
<th id="h3"><a href="<?php $_SERVER['PHP_SELF']?>?order=2">留言</a></th>
<th id="h3"><a href="<?php $_SERVER['PHP_SELF']?>?order=3">日期時間</a></th>
<?php
// 用迴圈輸出目前頁次的資料
if($page == $totalpage){
	if(($totalrow % $perpage)!=0){
		$count = $totalrow -$start;
	}else{
			$count =$perpage;
	}
}else{
	$count = $perpage;
}
for($i=0;$i<$count;$i++){
  if($i%2==0) echo '<tr class="grey">';  // 雙數行加灰底
  else echo '<tr>';
  echo "<td>{$data[$i]['留言編號']}</td>";
  echo "<td>{$data[$i]['姓名']}</td>";
  echo "<td>{$data[$i]['留言']}</td>";
  echo "<td>{$data[$i]['日期時間']}</td>";
  echo "<td><a href='guestbookDel.php?del={$data[$i]['留言編號']}'>刪除</a></td></tr>";
  echo '</tr>';
}
echo '</table>';

// 輸出直接跳頁的連線
for($i=1;$i<=$totalpage;$i++){
  if($i!=1) echo "&nbsp;";
  if($i==$page) echo $i;
  else
    echo sprintf('<a href="%s?page=%d&order=%d">%d</a>',
                 $_SERVER['PHP_SELF'], $i , $order, $i);  
}
?>
</body>
</html>
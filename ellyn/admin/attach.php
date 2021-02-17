<?php
	include('../includes/myfunctions.php');
	getConnection();
	
	
	
		echo "before";
	if (isset($_POST['send'])){
		
		
	function attachName($id){
		$name = basename($_FILES['attach']['name']);
		$nam = explode('.', $name);
		$ext = $nam[1];
		$fileName=$id.".".$ext;
		return $fileName;
	}
		
	function uploadAttach($id){
		$result = false;
		$target = "attachment/";
		$fileName=attachName($id);
		$target = $target.$fileName; 

		if (move_uploaded_file($_FILES['attach']['tmp_name'], $target)) //or die("CV not uplaoded, CV: ". $cv);
			$result = true; 
		return $result;
	}
		
		
		//extract($_POST);
		echo " inside ";
		$current = getdate();
		$fileName = 'attach'.$current['seconds'].$current['minutes'].$current['hours'].$current['mday'];
		//$nam= attachName($fileName);
		if (uploadAttach($fileName))
			echo "Success!!!";
		else 
			echo "File not uplaoded, File: ". $nam;
		
		
		
		/* $name = basename($_FILES['attach']['name']);
		$nam = explode('.', $name);
		$ext = $nam[1];
		$fileName=$fileName.".".$ext;
		$attch_id="487487";
		$msg_id = "fdjgjhfdsj";
		$target = "attachment/";
		//$fileName=$fName;
		$target = $target.$fileName; 

		if (move_uploaded_file($_FILES['attach']['tmp_name'], $target)){ //or die("CV not uplaoded, CV: ". $cv); 
			$sql = "insert into attachment values ('$attch_id', '$msg_id', '$fileName')";
			$query = mysql_query($sql);	
			echo "Success!!!";
		}else die("CV not uplaoded, CV: ". $cv); */

	
	}
	echo " after";
?>
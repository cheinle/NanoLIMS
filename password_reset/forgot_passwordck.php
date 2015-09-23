<?php
include('../connect_air.php');
//////////////////////////////
$email=$_POST['email'];
// Change the URL below to match your site
#$site_url="http://155.69.28.239/series/dynamic/airmicrobiomes/password_reset/";
$site_url="http://155.69.30.89/series/dynamic/airmicrobiomes/password_reset/";

?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
	<meta charset="utf-8" />
	<title>Login Form</title>
	<meta content="width=device-width, initial-scale=1.0" name="viewport" />
	<meta content="" name="description" />
	<meta content="" name="author" />
	<!-- BEGIN GLOBAL MANDATORY STYLES -->
	<link href="../assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
	<link href="../assets/plugins/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css"/>
	<link href="../assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
	<link href="../assets/css/style-metro.css" rel="stylesheet" type="text/css"/>
	<link href="../assets/css/style.css" rel="stylesheet" type="text/css"/>
	<link href="../assets/css/style-responsive.css" rel="stylesheet" type="text/css"/>
	<link href="../assets/css/themes/default.css" rel="stylesheet" type="text/css" id="style_color"/>
	<link href="../assets/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
	<link rel="stylesheet" type="text/css" href="../assets/plugins/select2/select2_metro.css" />
	<!-- END GLOBAL MANDATORY STYLES -->
	<!-- BEGIN PAGE LEVEL STYLES -->
	<link href="../assets/css/pages/login-soft.css" rel="stylesheet" type="text/css"/>
	<!-- END PAGE LEVEL STYLES -->
	<link rel="shortcut icon" href="favicon.ico" />
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="login">
<?php
$status = "OK";
$msg="";
//error_reporting(E_ERROR | E_PARSE | E_CORE_ERROR);
if(!filter_var($email,FILTER_VALIDATE_EMAIL)){ 
$msg="Your email address is not correct<BR>"; 
$status= "NOTOK";}


echo "<br><br>";
if($status=="OK"){
	$stmt1 = $dbc->prepare("SELECT user_id FROM users WHERE user_id = ?");
	$stmt1 -> bind_param('s', $email);
				
  	if ($stmt1->execute()){
    	$stmt1->bind_result($col1);
		$stmt1->store_result();
		$stmt1->fetch();
		$no = $stmt1->num_rows;
		$em=$col1;
		#echo "em ".$em;
		
		if ($no == 0) {  echo "<center><font face='Verdana' size='2' color=red><b>No Password</b><br> Sorry Your address is not there in our database . Please check with admin<BR><BR></center>"; exit;}
		#echo time();

		/// check if activation is pending /////
		$tm=time() - 86400; // Time in last 24 hours
		$stmt2 = $dbc->prepare("SELECT user_id FROM users WHERE user_id = ? and time > ? and status='pending'");
		$stmt2 -> bind_param('si', $email,$tm);
				
  		if ($stmt2->execute()){
	    	$stmt2->bind_result($col1);
			$stmt2->store_result();
			$stmt2->fetch();
			$no = $stmt2->num_rows;
		
			if($no==1){echo "<center><font face='Verdana' size='2' color=red><b>Your password activation Key is already posted to your email address, please check your Email address & Junk mail folder. "; exit;}

			/////////////// Let us send the email with key /////////////
			/// function to generate random number ///////////////
			function random_generator($digits){
				srand ((double) microtime() * 10000000);
				//Array of alphabets
				$input = array ("A", "B", "C", "D", "E","F","G","H","I","J","K","L","M","N","O","P","Q",
				"R","S","T","U","V","W","X","Y","Z");
				
				$random_generator="";// Initialize the string to store random numbers
				for($i=1;$i<$digits+1;$i++){ // Loop the number of times of required digits
					if(rand(1,2) == 1){// to decide the digit should be numeric or alphabet
						// Add one random alphabet 
						$rand_index = array_rand($input);
						$random_generator .=$input[$rand_index]; // One char is added
					}
					else{
						// Add one numeric digit between 1 and 10
						$random_generator .=rand(1,10); // one number is added
					} // end of if else
				} // end of for loop 
	
				return $random_generator;
			} // end of function


			$key=random_generator(10);
			$key=md5($key);
			$tm=time();
			$pending = 'pending';
			//echo "insert into plus_key(userid, pkey,time,status) values('$row->userid','$key','$tm','pending'";

			#$stmt3 = $dbc -> prepare("INSERT INTO users (user_id,pkey,time,status) VALUES (?,?,?,?)");
			$stmt3 = $dbc -> prepare("UPDATE users SET pkey=?, time= ?, status = ? WHERE user_id =?");
			$stmt3 -> bind_param('siss', $key,$tm,$pending,$email);
			$stmt3 -> execute();
			$stmt3 -> close();

			
			$headers4="admin";         ///// Change this address within quotes to your address ///
			$headers = '';
			$headers=$headers."Reply-to: $headers4\n";
			$headers .= "From: $headers4\n"; 
			$headers .= "Errors-to: $headers4\n"; 
			//$headers = "Content-Type: text/html; charset=iso-8859-1\n".$headers;// for html mail un-comment this line
			$site_url=$site_url."activepassword.php?ak=$key&userid=$email";
			 //echo $site_url;
			if(mail("$em","Your Request for login details","This is in response to your request for login details at site_name \n \nLogin ID: $em \n To reset your password, please visit this link( or copy and paste this link in your browser window )\n\n
			\n\n
			$site_url
			\n\n
			<a href='$site_url'>$site_url</a>
			
			 \n\n Thank You \n \n siteadmin","$headers")){echo "<center><font face='Verdana' size='2' ><b>THANK YOU</b> <br>Your password is posted to your email address . Please check your mail after some time. </center>";}
			else{ echo " <center><font face='Verdana' size='2' color=red >There is some system problem in sending login details to your address. Please contact site-admin. <br><br><input type='button' value='Retry' onClick='history.go(-1)'></center></font>";}
			
			} 
			
			else {echo "<center><font face='Verdana' size='2' color=red >$msg <br><br><input type='button' value='Retry' onClick='history.go(-1)'></center></font>";}
			
	}
}
?>
		
</body>
</html>

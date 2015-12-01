<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include ('database_connection.php');
include ('index.php');
include('functions/convert_header_names.php');
?>

<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Update Samples</title>
	</head>
	
	<body class = "update">
		<pre class="update"> <!-- commenting this out gets rid of the large bar-->	
		<?php $p_sample_name = htmlspecialchars($_GET['sample_name']);?>
	<h3>Sample  Updated: <?php echo $p_sample_name;?></h3>
	<p>
	<button type="button"  data-toggle="collapse" data-target="#demo" aria-expanded="true" aria-controls="demo" class='buttonLength'>View Details of Update</button>
	
	</p>
	<?php
		//error checking
		if (isset($_GET['submit'])) {
				if(isset($_GET['oStore_name'])){
					$_SESSION['oStore_name'] = $_GET['oStore_name']; //used for returning user selected option on dropdown if have to return to previous page
				}
				if(isset($_GET['dStore_name'])){
					$_SESSION['dStore_name'] = $_GET['dStore_name'];
				}
				if(isset($_GET['rStore_name'])){
					$_SESSION['rStore_name'] = $_GET['rStore_name'];
				}
			
				$error_check = 'false';
				$submitted = 'false';
				$name_check = 'false';
				$get_array = $_GET;

				//check that all fields are entered properly
				include('functions/field_check.php');
				$error_check = field_check($get_array,'update_sample');
				
				if($error_check == 'true'){
					echo '<script>Alert.render("ERROR: Sample Not Entered. Please check error messages.");</script>';
				}
 
 				//format date and time
				//$start = $_GET['sdate'].' '.$_GET['stime'];
				//$end = $_GET['edate'].' '.$_GET['etime'];
				
				//sanatize user input to make safe for browser
				$p_sample_number = htmlspecialchars($_GET['sample_number']);
				$p_projName = htmlspecialchars($_GET['projName']);
				$p_loc = htmlspecialchars($_GET['loc']);
				$p_rloc = htmlspecialchars($_GET['rloc']);
				//$p_sampler = htmlspecialchars($_GET['sampler']);
				$p_partSamp = NULL;
				$p_dExtKit = htmlspecialchars($_GET['dExtKit']);
				$p_rExtKit = htmlspecialchars($_GET['rExtKit']);
				$p_seqInfo = '0';
				$p_anPipe = '0';
				$p_barcode = htmlspecialchars($_GET['barcode']);
				//$p_start = htmlspecialchars($start);
				//$p_end = htmlspecialchars($end);
				$p_sType = htmlspecialchars($_GET['sType']);
				$p_path = NULL;
				$p_dConc = htmlspecialchars($_GET['dConc']);
   				$p_dInstru = htmlspecialchars($_GET['dInstru']);
   				$p_dVol = htmlspecialchars($_GET['dVol']);
				$p_dVol_quant = htmlspecialchars($_GET['dVol_quant']);
				$p_d_extr_date = htmlspecialchars($_GET['d_extr_date']);
				$p_rConc = htmlspecialchars($_GET['rConc']);				
				$p_rInstru = htmlspecialchars($_GET['rInstru']);	
				$p_rVol = htmlspecialchars($_GET['rVol']);	
				$p_rVol_quant = htmlspecialchars($_GET['rVol_quant']);
				$p_r_extr_date = htmlspecialchars($_GET['r_extr_date']);
				$p_notes = htmlspecialchars($_GET['notes']);
				$p_fRate = htmlspecialchars($_GET['fRate']);
				$p_fRate_eod = htmlspecialchars($_GET['fRate_eod']);
				$p_dData = NULL;
				$p_media = htmlspecialchars($_GET['media']);
				$p_sampling_height = htmlspecialchars($_GET['sampling_height']);
				
				if(! ($p_sType == 'A' || $p_sType == 'BC' || $p_sType == 'F' || $p_sType == 'UI') ){//this is here to set if the flow rate was left empty...should check if flowrate was empty
						if($p_fRate == ''){$p_fRate = '0';}
						if($p_fRate_eod == ''){$p_fRate_eod = '0';}
				}
				
				//check if non-required fields are clear(ed)
				if ($p_dExtKit == '0') {$p_dExtKit = NULL;} 
				if ($p_rExtKit == '0') {$p_rExtKit = NULL;}
				if ($p_seqInfo == '0') {$p_seqInfo = NULL;}
				if ($p_anPipe == '0') {$p_anPipe = NULL;}
				if ($p_barcode == '') {$p_barcode = NULL;}
				//if ($p_start == '') {$p_start = NULL;}
				//if ($p_end == '') {$p_end = NULL;}
				if ($p_sType == '') {$p_sType = NULL;}
				if ($p_path == '') {$p_path = NULL;}
   				if ($p_dInstru == '0') {$p_dInstru = NULL;} 				
				if ($p_rInstru == '0') {$p_rInstru = NULL;} 
				if ($p_dConc == '') {$p_dConc = NULL;}  
   				if ($p_dVol == '') {$p_dVol = NULL;} 
				if ($p_dVol_quant == '') {$p_dVol_quant = NULL;} 
				if ($p_d_extr_date == '') {$p_d_extr_date = NULL;} 
				if ($p_rConc == '') {$p_rConc = NULL;} 				
				if ($p_rVol == '') {$p_rVol = NULL;} 
				if ($p_rVol_quant == '') {$p_rVol_quant = NULL;}
				if ($p_r_extr_date == '') {$p_r_extr_date = NULL;} 
				if ($p_notes == '') {$p_notes = NULL;}
				if ($p_fRate == '') {$p_fRate = NULL;}
				if ($p_fRate_eod == '') {$p_fRate_eod = NULL;}
				if ($p_dData == '0') {$p_dData = NULL;}

						
				//check and process collector info
				include_once("functions/check_collector_names.php");
				$array=$get_array['collector'];
				$check = check_collector_names($array,'true');
				if($check['boolean'] == 'false'){
					$error_check = 'true';
				}
				else{
					$p_collName = $check['cat_name'];
				}
				
				
				//check and process DNA Extractor names
				$array2=$_GET['dExtrName'];
				$check2 = check_collector_names($array2,'false');
				if($check2['boolean'] == 'false'){
					$error_check = 'true';
				}
				else{
					$p_dExtrName = $check2['cat_name'];
				}
				
				
				//check and process RNA Extractor names
				$array3=$_GET['rExtrName'];
				$check3 = check_collector_names($array3,'false');
				if($check3['boolean'] == 'false'){
					$error_check = 'true';
				}
				else{
						$p_rExtrName = $check3['cat_name'];
				}
				
				
				//cat user first name and last to create updated by field
				$p_updated_by = $_SESSION['first_name'].' '.$_SESSION['last_name'];
				
				//recreate name,check if new name exists
				$p_orig_sample_name = htmlspecialchars($_GET['sample_name']);
				$p_orig_sample_num = substr($p_orig_sample_name, -3);
				$date = htmlspecialchars($_GET['sdate1']); //just using start date for sampling date
				$regrex_check = '/^(201[4-5])-([0-1][0-9])-([0-3][0-9])$/'; //remove dashes (yyyy-mm-dd)
				$preg_match = preg_match($regrex_check,$date,$matches);
				if($preg_match != 1){
					echo "Date Matching Error. Please Notify Admin";
				}
				$date = $matches[1].'/'.$matches[2].'/'.$matches[3];
				$p_sample_name = $date.$p_projName.$p_sType.$p_sample_number;
				$sample_sort = $p_projName.$p_sample_number;
				
				//since sample naming was different prior to now (2015/01/27), if the project name is Coil
				//then do not auto create the name (or change it if anything is updated)
				if($p_projName == 'Coil'){
					$p_sample_name = $p_orig_sample_name;
				}
				
				$p_orig_project_name;
				//can't substring original project name so have to grab it
				$stmt_pn = $dbc->prepare("SELECT project_name FROM sample WHERE sample_name = ?");
				if(!$stmt_pn){
					$error_check = 'true';
					die('prepare() failed: ' . htmlspecialchars($stmt_pn->error));
				}
				$stmt_pn->bind_param("s",$p_orig_sample_name);
				if ($stmt_pn->execute()){
					$stmt_pn->bind_result($p_name);
					while ($stmt_pn->fetch()) {
						$p_orig_project_name = $p_name;
					}
				}
				else{
					$error_check = 'true';
					die('execute() failed: ' . htmlspecialchars($stmt_pn->error));
				}
				

				//New name check based on project name and sample number
				//check only if new name looks different from old name...only check if different project_name and/or number
				//need to save original sample number and name?;

				if($p_orig_project_name != $p_projName || $p_orig_sample_num != $p_sample_number){
					$stmt1 = $dbc->prepare("SELECT sample_name FROM sample WHERE project_name = ? AND sample_num = ?");
					if(!$stmt1){
						$error_check = 'true';
						die('prepare() failed: ' . htmlspecialchars($stmt1->error));
					}
					$stmt1->bind_param("si",$p_projName,$p_sample_number);
					if ($stmt1->execute()){
						$stmt1->bind_result($name);
						   while ($stmt1->fetch()) {
						   		//echo "Name: {$name}<br>";
						   		echo "ERROR: ".$p_sample_name." cannot be added. Sample number for this project already exisits. Please check name.".'<br>';
								$error_check = 'true';
							}
					} 
					else {
						$error_check = 'true';
						die('execute() failed: ' . htmlspecialchars($stmt1->error));
					}
					$stmt1 -> close();
				}
				
				//calculate total sampling time
				/*$ts1 = strtotime($p_start);
				$ts2 = strtotime($p_end);
				$seconds_diff = $ts2 - $ts1;
				$time = ($seconds_diff/3600);//hours
				$p_time = round($time,2);*/
				
				//check if original sample still exists
				if(isset($_GET['orig_sample_exist'])){
					$p_orig_sample_exist = htmlspecialchars($_GET['orig_sample_exist']);
				}
				else{
					$p_orig_sample_exist = 'true';
				}
				
				//check if DNA sample still exists
				if(isset($_GET['DNA_sample_exist'])){
					$p_DNA_sample_exist = htmlspecialchars($_GET['DNA_sample_exist']);
				}
				else{
					$p_DNA_sample_exist = 'two';
				}
				
				//check if RNA sample still exists
				if(isset($_GET['RNA_sample_exist'])){
					$p_RNA_sample_exist = htmlspecialchars($_GET['RNA_sample_exist']);
				}
				else{
					$p_RNA_sample_exist = 'two';
				}

		
				//grab abbreviated project name to create new ID for sequencing submission
				$seq_id = '';
				$stmt_sid= $dbc->prepare("SELECT seq_id_start FROM project_name WHERE project_name = ?");
				if(!$stmt_sid){
					$error_check = 'true';
					die('prepare() failed: ' . htmlspecialchars($stmt_sid->error));
				}
				$stmt_sid -> bind_param('s', $p_projName);
	  			if ($stmt_sid->execute()){
	  			
	    			$stmt_sid->bind_result($name);
	    			if ($stmt_sid->fetch()){
	        			$seq_id = $name.$p_sample_number;
					}
				} 
				else {
					$error_check = 'true';
	    			die('execute() failed: ' . htmlspecialchars($stmt_sid->error));
					
				}
				$stmt_sid -> close();


///////////////////////////////////////////////////////////////////////////////////////////////////////////////	
//Error Checking Over, Try And Update Tables
///////////////////////////////////////////////////////////////////////////////////////////////////////////////	
			//update info into db
			echo '<div id="demo" class="collapse">';
			if ($error_check == 'false') {
				//check to see if you see any updates
				$updates_check = 'false';
				
				//start to try and update
				try{
					//start transaction
					mysqli_autocommit($dbc,FALSE);
					
					//assume successful unless see error (for commit statment)
					$successfull = 'true';
					
					//get unix timestamp
					$current_time = $_GET['transaction_time']; //sent from page 2
					
					//set timestamp 
					$ts_set_query = 'UPDATE sample SET time_stamp = ? WHERE sample_name = ? AND time_stamp <= ?';
					if($ts_stmt = $dbc ->prepare($ts_set_query )) {                 
	                	$ts_stmt->bind_param('sss',$current_time, $p_orig_sample_name,$current_time);
	
	                    $ts_stmt -> execute();
						$ts_rows_affected = $ts_stmt ->affected_rows;;
						$ts_stmt -> close();
						if($ts_rows_affected >= 0){
							//for testing
							#echo "You updated timestamp".'<br>'; //cleanup
						}
						else{
							$successfull = 'false';
							echo '<script>Alert.render("ERROR: Unable to update sample. Sample may have been modified since you opened this record. Please reload record, review, and try again");</script>';
							throw new Exception("Unable to update sample. Sample may have been modified since your opened this record. Please reload record, review, and try again");	
							//mysqli_error($dbc);
						
						}
					}
					else{
						$successfull = 'false';
						throw new Exception("Unable To Prepare Time Stamp");	
						//mysqli_error($dbc);
					}
					
					
					//if time is later than the time in the db, then update db with your time and finish the transaction
					//else, don't do anything because db has been changed.
					//if selection or form is not empty, update those fields for this sample in samples table
					
					//////////////////////////////////////////////////////////////////
					/****Update Sample Table****/
					//////////////////////////////////////////////////////////////////
					$data = array(
						
						array('field' => 'project_name', 'value' => $p_projName, 'type' => 's'),
						array('field' => 'location_name', 'value' => $p_loc, 'type' => 's'),
						array('field' => 'relt_loc_name', 'value' => $p_rloc, 'type' => 's'),
						array('field' => 'part_sens_name', 'value' => $p_partSamp, 'type' => 's'),
						array('field' => 'collector_name', 'value' => $p_collName, 'type' => 's'),
						array('field' => 'dna_extract_kit_name', 'value' => $p_dExtKit, 'type' => 's'),
						array('field' => 'rna_extract_kit_name', 'value' => $p_rExtKit, 'type' => 's'),
						array('field' => 'sequencing_info', 'value' => $p_seqInfo, 'type' => 's'),
						array('field' => 'analysis_name', 'value' => $p_anPipe, 'type' => 's'),
						array('field' => 'barcode', 'value' => $p_barcode, 'type' => 's'),
						array('field' => 'sample_type', 'value' => $p_sType, 'type' => 's'),
						array('field' => 'particle_ct_csv_file', 'value' => $p_path, 'type' => 's'),
						array('field' => 'd_conc', 'value' => $p_dConc, 'type' => 'd'),
		   				array('field' => 'd_conc_instrument', 'value' => $p_dInstru, 'type' => 's'),
		   				array('field' => 'd_volume', 'value' => $p_dVol, 'type' => 'i'), 
		   				array('field' => 'd_volume_quant', 'value' => $p_dVol_quant, 'type' => 'i'), 
		   				array('field' => 'd_extraction_date', 'value' => $p_d_extr_date, 'type' => 's'), 
						array('field' => 'r_conc', 'value' => $p_rConc, 'type' => 'd'),				
						array('field' => 'r_conc_instrument', 'value' => $p_rInstru, 'type' => 's'),
						array('field' => 'r_volume', 'value' => $p_rVol, 'type' => 'i'),
						array('field' => 'r_volume_quant', 'value' => $p_rVol_quant, 'type' => 'i'),
						array('field' => 'r_extraction_date', 'value' => $p_r_extr_date, 'type' => 's'),
						array('field' => 'notes', 'value' => $p_notes, 'type' => 's'),
						array('field' => 'flow_rate', 'value' => $p_fRate, 'type' => 'd'),
						array('field' => 'flow_rate_eod', 'value' => $p_fRate_eod, 'type' => 'd'),
						array('field' => 'daily_data', 'value' => $p_dData, 'type' => 's'),
						array('field' => 'sample_num', 'value' => $p_sample_number, 'type' => 'i'),
						array('field' => 'updated_by', 'value' => $p_updated_by, 'type' => 's'),
						array('field' => 'sample_sort', 'value' => $sample_sort, 'type' => 's'),
						array('field' => 'media_type', 'value' => $p_media, 'type' => 's'),
						array('field' => 'sampling_height', 'value' => $p_sampling_height, 'type' => 'd'),
						array('field' => 'dExtrName', 'value' => $p_dExtrName, 'type' => 's'),
						array('field' => 'rExtrName', 'value' => $p_rExtrName, 'type' => 's'),
						array('field' => 'seq_id', 'value' => $seq_id, 'type' => 's'),
						array('field' => 'sample_name', 'value' => $p_sample_name, 'type' => 's')//the update of this sample name always needs to be last
					);
					
					$count = count($data);
			
	            	if($count > 0) {
						echo "Update for: ".$p_orig_sample_name.'<br>';
						if($p_orig_sample_name != $p_sample_name){
							echo "New Sample Name: ".$p_sample_name.'<br>';
						}
						
	                	//$errors = false;
	                	$query = 'UPDATE sample SET ';
	                	foreach($data as $id => $values) {
	                    	$query2 = $query.$values['field'].' = ? WHERE sample_name = ? and time_stamp = ?';
	 
	                    	if($stmt = $dbc ->prepare($query2)) {                 
	                        	$types = $values['type'].'s'.'s';//s is for the p_sample_name type, other s is for timestamp
	                        	$stmt->bind_param($types, $values['value'], $p_orig_sample_name, $current_time);
	
	                        	$stmt -> execute();
								$rows_affected = $stmt ->affected_rows;
								$stmt -> close();
								
								//check if add was successful or not. Tell the user
			   					if($rows_affected > 0){
									$value1 = convert_header_names($values['field']);
									echo "You successfully updated ".$value1.'<br>';
									$updates_check = 'true';
								}
								elseif($rows_affected == 0){
									//for testing purposes only
									#echo "No Changes Needed for ".$value1.'<br>';
								}
								else{
									$successfull = 'false';
									echo '<script>Alert.render("ERROR:Your sample has been updated by another user since you began your update. Please refresh your page to view updated information and try again");</script>';
									throw new Exception("rows affected is <= 0. ERROR: Your sample has been updated by another user since you began your update. Please refresh your page to view updated information and try again.");	
									//mysqli_error($dbc);
								}
							
	            	
							}
						 
						}
						//adding commit after it has gone through each possible update.
						//check if sample name was updated, if it was, update sample name for storage info also
					    //database foreign key constraints set. no need to remember original sample name after here (auto updated) 
					    
						//////////////////////////////////////////////////////////////////
						/****Update Storage Info Table****/
						//////////////////////////////////////////////////////////////////
						
						$p_oStore = $_GET['oStore_temp'].','.$_GET['oStore_name'];
						$p_dStore = $_GET['dStore_temp'].','.$_GET['dStore_name'];
						$p_rStore = $_GET['rStore_temp'].','.$_GET['rStore_name'];
						

						$data2 = array( 
							
							array('field' => 'original', 'value' => $p_oStore, 'type' => 's'),
							array('field' => 'orig_sample_exists', 'value' => $p_orig_sample_exist, 'type' => 's'),
							array('field' => 'dna_extr', 'value' => $p_dStore, 'type' => 's'),
							array('field' => 'rna_extr', 'value' => $p_rStore, 'type' => 's'),
							array('field' => 'DNA_sample_exists', 'value' => $p_DNA_sample_exist, 'type' => 's'),
							array('field' => 'RNA_sample_exists', 'value' => $p_RNA_sample_exist, 'type' => 's'),
							array('field' => 'sample_name', 'value' => $p_sample_name, 'type' => 's')//put sample name updates last
						);
						$count2 = count($data2);
	            		if($count2 > 0) {
		                	//$errors = false;
							
		                	$query_pre = 'UPDATE storage_info SET ';
		                	foreach($data2 as $id2 => $values2) {
		                    	$query_si = $query_pre.$values2['field'].' = ? WHERE sample_name = ?';		
								if($stmt_si = $dbc ->prepare($query_si)) {
									$types = $values2['type'].'s';//s is for the p_sample_name type, other s is for timestamp
			                        $stmt_si->bind_param($types, $values2['value'], $p_sample_name); 
				                    $stmt_si -> execute();
									$rows_affected_si = $stmt_si ->affected_rows;
									$stmt_si -> close();
									
									//check if rows were updated
									if($rows_affected_si > 0){
											$value = convert_header_names($values2['field']);
											echo "You successfully updated ".$value.'<br>';
											$updates_check = 'true';
									}
									elseif($rows_affected_si == 0){
										//for testing purposes only
										#$echo "No need to update ".$value.'<br>';
									}
									else{
										$successfull = 'false';
										echo '<script>Alert.render("ERROR:Unable to update sample name in storage info. No Updates were made to sample!!! Please contact admin for assistance.);</script>';
										throw new Exception("rows affected is <= 0. ERROR: You were unable to update ");	
										//mysqli_error($dbc);
									}
									 
								}
								else{
									$successfull = 'false';
									echo '<script>Alert.render("ERROR:Unable to update sample name in storage info. No Updates were made to sample!!! Please contact admin for assistance.);</script>';
									throw new Exception("Prepare statement failure. ERROR: You were unable to update ");	
									//mysqli_error($dbc);
								}
							}
						}
						else{
							$successfull = 'false';
							echo '<script>Alert.render("ERROR:Unable to update sample name in storage info. No Updates were made to sample!!! Please contact admin for assistance.);</script>';
							throw new Exception("Prepare failed. ERROR: You were unable to update ");	
						}
						
					}
					else{
						$successfull = 'false';
						echo '<script>Alert.render("ERROR:An error has occred: Please populate a field to update");</script>';
						throw new Exception("An error has occred: Please populate a field to update");
					}
					
					//////////////////////////////////////////////////////////////////
					/****Update Samplers Table****/
					//////////////////////////////////////////////////////////////////
					
					//repeat for x amount of samplers
					$num_of_samp = $_GET['sampler_num'];
					$earliest_start;
					$latest_end;
					$counter = 0;
					for ($x = 1; $x <= $num_of_samp; $x++) {
						$sampler_name = $_GET['sampler'.$x];
						$counter++;
						
						//calcualate total time
						$start = htmlspecialchars($_GET['sdate'.$x]).' '.htmlspecialchars($_GET['stime'.$x]);
						$end= htmlspecialchars($_GET['edate'.$x]).' '.htmlspecialchars($_GET['etime'.$x]);
			
						//format date/time
						$p_time = '';
						if(($start) && ($end)){
							$ts1 = strtotime($start);
							$ts2 = strtotime($end);
			
							$seconds_diff = $ts2 - $ts1;
							
							$time = ($seconds_diff/3600);
							$p_time = round($time,2);
						}
						
						if(isset($_GET['delete'.$x])){
							//delete the daily data for this date/sensor primary key...can you do this?
							//check if it exists...if it does, delete it
							if(! $stmt_d = $dbc -> prepare("DELETE FROM sample_sampler WHERE sample_name = ? AND sampler_name = ?")){
								$successfull = 'false';
								throw new Exception("Delete Sampler Prepare Failure: No Deleted/Added Sampler Info");
							}
							$stmt_d->bind_param('ss',$p_sample_name,$sampler_name);
						
							if(!$stmt_d->execute()){
								$successfull = 'false';
								throw new Exception("Execution Error: Unable To Delete Sampler Info");
							}
							$rows_affected_d = $stmt_d ->affected_rows;
							//check if add was successful or not. Tell the user
					   		if($rows_affected_d >= 0){
					   			echo 'You DELETED Sampler Info! :'.$sampler_name.'<br>';
								$updates_check = 'true';
							}
							else{
								$successfull = 'false';
								throw new Exception("ERROR: No Deleted/Added Sampler Info");
								
							}
							$stmt_d->close();
						}
						else{
							//search to see if exists,if it exists, update the entry
							//if it does not, insert it
							$exists = 'false';
							$stmt_ck= $dbc->prepare("SELECT sampler_name FROM sample_sampler WHERE sample_name = ? AND sampler_name = ? ");
							if(!$stmt_ck){
								$successfull = 'false';
								throw new Exception("Prepare Error: Unable To Find Matching Sampler Info");
							}
							$stmt_ck -> bind_param('ss', $p_sample_name,$sampler_name);
							if ($stmt_ck->execute()){
								$stmt_ck->bind_result($compare_sample_name);
							    while($stmt_ck->fetch()){
									if($compare_sample_name == $sampler_name){
										$exists = 'true';
									}
								}
							} 
							else{
								$successfull = 'false';
								throw new Exception("Execution Error: Unable To Find Matching Sampler Info");
							}
							$stmt_ck -> close();
							
							
							//if you did not see this entry in the db, insert it. else, update
							if($exists == 'false'){
								$stmt_ins = $dbc -> prepare("INSERT INTO sample_sampler (sample_name,sampler_name,start_date_time,end_date_time,total_date_time) VALUES (?,?,?,?,?)");
								if(!$stmt_ins){
									$successfull = 'false';
									throw new Exception("Prepare Error: Unable To Insert Sampler Info");
								}
								$stmt_ins -> bind_param('ssssd', $p_sample_name,$sampler_name,$start,$end,$p_time);
								if(!$stmt_ins -> execute()){
									$successfull = 'false';
									throw new Exception("Exectution Error: Unable To Insert Sampler Info");
								}
								$rows_affected_ins = $stmt_ins ->affected_rows;
								$stmt_ins -> close();
								
								//check if add was successful or not. Tell the user
						   		if($rows_affected_ins >= 0){
						   			$updates_check = 'true';
								}
								else{
									$successfull = 'false';
									throw new Exception("ERROR: No Sampler Info Added");
								}
							}	
							else{
								$stmt_up = $dbc -> prepare("UPDATE sample_sampler SET start_date_time = ?, end_date_time = ?,total_date_time = ? WHERE sample_name = ? AND sampler_name =?");
								if(!$stmt_up){
									$successfull = 'false';
									throw new Exception("Prepare Error: Unable To Update Sampler Info");
								}
								$stmt_up-> bind_param('ssdss',$start,$end, $p_time,$p_sample_name, $sampler_name);
								if(!$stmt_up -> execute()){
									$successfull = 'false';
									throw new Exception("Execution Error: Unable To Update Sampler Info");
								}
								$rows_affected_up = $stmt_up ->affected_rows;
								$stmt_up -> close();
									
								//check if add was successful or not. Tell the user
							   	if($rows_affected_up >= 0){
									$updates_check = 'true';
								}else{
									$successfull = 'false';
									throw new Exception("An error has occurred: No Updated Sampler Info");
								}
							}
							
							$start = $_GET['sdate'.$x].' '.$_GET['stime'.$x];
							$end = $_GET['edate'.$x].' '.$_GET['etime'.$x];
							
							if($counter == 1){
								$earliest_start = $start;
								$latest_end = $end;
							}
							else{
								//check starts
								if($start < $earliest_start){
									$earliest_start = $start;
								}
								
								//check ends
								if($end > $latest_end){
									$latest_end = $end;
								}
							}
						}
					}

					//format largest sampling period for samplers run at the same time period
					//update sample table with this new time
					$p_biggest_time;
					if(($start) && ($end)){
							$bts1 = strtotime($earliest_start);
							$bts2 = strtotime($latest_end);
		
							$big_seconds_diff = $bts2 - $bts1;
							
							$big_time = ($big_seconds_diff/3600);
							$p_biggest_time = round($big_time,2);
					}
					//echo 'biggest time'.$p_biggest_time;
					
					$time_query = "UPDATE sample SET start_samp_date_time = ?, end_samp_date_time = ?, total_samp_time = ? WHERE sample_name = ?";
					if($time_stmt = $dbc ->prepare($time_query)) {                 
	                	$time_stmt->bind_param('ssds',$earliest_start,$latest_end,$p_biggest_time,$p_sample_name);
				
	                    if($time_stmt -> execute()){
							$time_rows_affected = $time_stmt ->affected_rows;
						
							$time_stmt -> close();
							if($time_rows_affected < 0){	
								$successfull = 'false';
								throw new Exception("Insert Failure: Unable To Insert Sampler");
							}
						}
						else{
							$successfull = 'false';
							throw new Exception("Execution Failure: Unable To Insert Sampler");
						}
					}
					else{
						$successfull = 'false';
						throw new Exception("Prepare Failure: Unable To Insert Sampler");
					}
					
					
					//////////////////////////////////////////////////////////////////
					
					//////////////////////////////////////////////////////////////////
					/****Check If No Errors****/
					//////////////////////////////////////////////////////////////////
					if($successfull == 'true'){
						$submitted = 'true';
						#echo "Success was true";
						$dbc->commit();
						echo '<script>Alert.render("SUCCESS:Click Button For Details");</script>';
						if($updates_check == 'false'){
							echo "WARNING: No Updates Needed. Please Check That You Made Any Changes To The Form.";
						}
						unset_session_vars('storage_info');
					}		
					else{
						throw new Exception("No Success found! ");
						echo '<script>Alert.render("WARNING: Error was found. No updates have been made. Please see details of update );</script>';
					}
				}
				catch (Exception $e) { 
    				if (isset ($dbc)){
       	 				$dbc->rollback ();
						echo '<script>Alert.render("ERROR!!! All Updates Have Been Reverted. No Update Has Been Made. Please See Earlier Error Messages And Notify Admin");</script>';
       					echo "Final Error:  " . $e; 
    				}
				}
			
			}
		}

	?>
</div>
</pre>


<p>
<input action="action" class="button" type="button" value="Go Back" onclick="history.go(-1);" />
</p>
<!--<button class="btn btn-success" type=button onClick="parent.location='/series/dynamic/airmicrobiomes/sample_update_lookup2.php'" value='Go Back'>Go Back</button>-->
</p>
<button class="button" type=button onClick="parent.location='<?php echo $root;?>sample_update_lookup.php'" value='update'>Update Another Sample</button>
</p>

</body>
</html>	

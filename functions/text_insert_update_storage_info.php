
<?php	
//2015/05/25 built for text input recall for storage info. trying to mod it to use only one function instead of multiple...still in the works
function text_insert_update_stinfo($sample_name,$field_name,$table,$root){ #send also the query name?, always based on sample name
	#echo 'fn:'.$field_name;
	//include('config/path.php');
	$path = $_SERVER['DOCUMENT_ROOT'].$root;
	include($path.'database_connection.php');
	
	$stmt = $dbc->prepare("SELECT * FROM storage_info WHERE sample_name = ?");
	$stmt -> bind_param('s',$sample_name);	

	if ($stmt->execute()){			
	    if($stmt->fetch()){
	    	$meta = $stmt->result_metadata(); 
   			while ($field = $meta->fetch_field()){ 
        		$params[] = &$row[$field->name]; 
    		} 

    		call_user_func_array(array($stmt, 'bind_result'), $params); 
				
			$stmt->execute();
			while ($stmt->fetch()) {
				return htmlspecialchars($row[$field_name]);		
			}		
			$stmt->close();
		}
		return "Error"; 
	}
	return "Error";
}


function text_insert_update_daily_data($daily_date,$field_name,$table,$location,$root){ #send also the query name?, always based on sample name
	$path = $_SERVER['DOCUMENT_ROOT'].$root;
	include($path.'database_connection.php');
	
	if($table == 'daily_data2'){
		$stmt = $dbc->prepare("SELECT * FROM daily_data2 WHERE daily_date = ?");
		$stmt -> bind_param('s',$daily_date);
	}
	/*if($table == 'daily_data2_particle_counter'){ //would need to send sensor name. Do not need at this moment
		$stmt = $dbc->prepare("SELECT * FROM daily_data2_particle_counter WHERE daily_date = ? AND location = ? AND part_sens_name");
		$stmt -> bind_param('sss',$daily_date,$location,$sensor_name);
	}*/
		
	if ($stmt->execute()){			
	    while($stmt->fetch()){
	    	$meta = $stmt->result_metadata(); 
   			while ($field = $meta->fetch_field()){ 
        		$params[] = &$row[$field->name]; 
    		} 

    		call_user_func_array(array($stmt, 'bind_result'), $params); 
				
			$stmt->execute();
			while ($stmt->fetch()) {
				return htmlspecialchars($row[$field_name]);		
			}		
			$stmt->close();
		}
	}else{
		return "Error";
	}
	
}
?>
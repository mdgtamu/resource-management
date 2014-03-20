<!--Function Repository-->
<?php
	
	/*------------------------------------------------------------------------------------------------*/
	/* Group: Database Functions */
	/*------------------------------------------------------------------------------------------------*/
	

	/* variables for storing connection information */
	$MYSQL_connection;
	$MYSQL_host = "dev.chem.tamu.edu:1";
	$MYSQL_username = "web";
	$MYSQL_password = "tamu13";
	$MYSQL_dbname = "supercomputer";
	
		
	/* Function: db_connect()
	   Connects to the MySQL database. Must be called before other functions can be used.
	
	   Parameters:
	   HARDCODED
	   
	   Returns:
	   NONE
	   
	*/
	function db_connect()
	{
		global $MYSQL_connection, $MYSQL_host, $MYSQL_username, $MYSQL_password, $MYSQL_dbname;
		$MYSQL_connection=mysqli_connect($MYSQL_host, $MYSQL_username, $MYSQL_password, $MYSQL_dbname);
		if (mysqli_connect_errno($MYSQL_connection))
		{
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		else
		{
			define_status_constants();
		}
	}
	
	/* Function: db_disconnect()
	   Closes the connection to the MySQL database. Should be called once database is no longer being used.
	 
	   Parameters:
	   NONE
	   
	   Returns:
	   NONE
	   
	*/
	function db_disconnect()
	{
		global $MYSQL_connection;
		mysqli_close($MYSQL_connection);
	}
	
	/* Function: create_date_comp()
	   Creates date comparison function in MySQL database
	 
	   Parameters:
	   NONE
	   
	   Returns:
	   - TRUE on success
	   - FALSE on failure
	 
	 */
	function create_date_comp()
	{
		global $MYSQL_connection;
		return mysqli_query($MYSQL_connection,
		"DELIMITER //

		CREATE FUNCTION ISNEWER(a DATETIME, b DATETIME)
			RETURNS BOOL
			BEGIN
				DECLARE aisnewer BOOL;

				IF (TIMESTAMPDIFF(SECOND, a, b) < 0)
				THEN SET aisnewer = TRUE;
				ELSE SET aisnewer = FALSE;
				END IF;

				RETURN aisnewer;

			END //

		DELIMITER ;");
	}

	function define_status_constants()
	{
		define("PENDING", get_status_id('Pending') );
		define("ACTIVE", get_status_id('Active') );
		define("PENDING_GROUP", get_status_id('Pending - Group') );
		define("WARNING", get_status_id('Warning') );
		define("EXPIRED", get_status_id('Expired') );
		define("REVIEW", get_status_id('Review') );
		define("DENIED" , get_status_id('Denied') );
		define("INACTIVE", get_status_id('Inactive') );
	}
	
	/*------------------------------------------------------------------------------------------------*/
	/* Group: Helper Functions */
	/*------------------------------------------------------------------------------------------------*/
	
	/* Function: get_status_id()
	   Get status ID based on status_admin name.
	   
	   Parameters:
	   $status_name - plaintext name of status (uses status_admin name)
	   
	   Returns:
	   - $status_id['status_id']
	 */
	function get_status_id( $status_name )
	{
		global $MYSQL_connection;
		$status_id = mysqli_fetch_array( mysqli_query( $MYSQL_connection, "SELECT status_id FROM Status WHERE status_admin = $status_name" ) );
		return $status_id['status_id'];
	}

	/* Function: get_status_name()
	   Get status_admin name based on status ID. 
	
	   Parameters:
	   $status_id - id of status
	   
	   Returns:
	   - $status_name['status_name']
	 */
	function get_status_name( $status_id )
	{
		global $MYSQL_connection;
		$status_name = mysqli_fetch_array( mysqli_query( $MYSQL_connection, "SELECT status_admin FROM Status WHERE status_id = $status_id" ) );
		return $status_name['status_name'];
	}


	/* Function: get_user_id()
	   Gets the user_id key associated with the given username in the 'Users' table
	   
	   Parameters:
	   $username - $username
	   
	   Returns:
	   - $user_id['user_id']
	 */
	function get_user_id($username)
	{
		global $MYSQL_connection;
		$user_id=mysqli_fetch_array(mysqli_query($MYSQL_connection,"SELECT user_id FROM Users WHERE NetID=$username;"));
		return $user_id['user_id'];
	}
	
	/* Function: get_username()
	   Gets the NetID with the given user_id
	 
	   Parameters:
	   $user_id - user_id key stored in the database table 'Users'
	   
	   Returns:
	   - $username['NetID']
	  
	 */
	function get_username($user_id)
	{
		global $MYSQL_connection;
		$username=mysqli_fetch_array(mysqli_query($MYSQL_connection,"SELECT NetID FROM Users WHERE user_id=$user_id;"));
		return $username['NetID'];
	}
	
	/* Function: get_host_id()
	   Gets the host_id key associated with the given host name in the 'Hosts' table
	   
	   Parameters:
	   $hostname - plaintext name of the host (eg. 'Orion', 'Medusa')
	 
	   Returns:
	   - $host_id['host_id']
	 */
	function get_host_id($hostname)
	{
		
		global $MYSQL_connection;
		$host_id=mysqli_fetch_array(mysqli_query($MYSQL_connection,"SELECT host_id FROM Hosts WHERE hostname=$hostname;"));
		return $host_id['host_id'];
	}
	
	/* Function: get_hostname()
	   Gets the name of the host with the given host_id
	   
	   Parameters:
	   $host_id - host_id key stored in the database table 'Hosts'
	 
	   Returns:
	   - $hostname['hostname'] 
	 */
	function get_hostname($host_id)
	{
		global $MYSQL_connection;
		$hostname=mysqli_fetch_array(mysqli_query($MYSQL_connection,"SELECT hostname FROM Hosts WHERE host_id=$host_id;"));
		return $hostname['hostname'];
	}
	
	/* Function: get_ext_host_id()
	   Gets the host_id key associated with the given host name in the 'External_Hosts' table
	 
	   Parameters:
	   $exthostname - plaintext name of the external host (eg. 'Orion', 'Medusa')
	 
	   Returns:
	   - $ext_host_id['external_host_id']
	 */
	function get_ext_host_id($exthostname)
	{
		
		global $MYSQL_connection;
		$ext_host_id=mysqli_fetch_array(mysqli_query($MYSQL_connection,"SELECT external_host_id FROM External_Hosts WHERE hostname=$exthostname;"));
		return $ext_host_id['external_host_id'];
	}
	
	/* Function: get_ext_host_admin()
	   Gets the admin_contact field for the given external_host_id
	   
	   Parameters:
	   $ext_host_id - external_host_id key stored in the database table 'External_Hosts'
	 
	   Returns:
	   - $ext_host_admin['admin_contact'] 
	 */
	function get_ext_host_admin($ext_host_id)
	{
		global $MYSQL_connection;
		$ext_host_admin=mysqli_fetch_array(mysqli_query($MYSQL_connection,"SELECT admin_contact FROM External_Hosts WHERE external_host_id=$ext_host_id;"));
		return $ext_host_admin['admin_contact'];
	}
	
	/* Function: get_exthostname()
	   Gets the name of the host with the given host_id
	   
	   Parameters:
	   $ext_host_id - host_id key stored in the database table 'External_Hosts'
	 
	   Returns:
	   - $exthostname['hostname']
	 */
	function get_exthostname($ext_host_id)
	{
		global $MYSQL_connection;
		$exthostname=mysqli_fetch_array(mysqli_query($MYSQL_connection,"SELECT hostname FROM External_Hosts WHERE external_host_id=$ext_host_id;"));
		return $exthostname['hostname'];
	}
	
	/* Function: get_group_id()
	   Gets the group_id key associated with the given group name in the 'Groups' table
	   
	   Parameters:
	   $groupname - plaintext name of the group (eg. 'export control', 'AERO')
	 
	   Returns:
	   - $group_id['group_id']
	 */
	function get_group_id($groupname)
	{
		global $MYSQL_connection;
		$group_id=mysqli_fetch_array(mysqli_query($MYSQL_connection,"SELECT group_id FROM Groups WHERE group_name=$groupname;"));
		return $group_id['group_id'];
	}
	
	/* Function: get_groupname()
	   Gets the name of the group with the given group_id
	   
	   Parameters:
	   $group_id - group_id key stored in the database table 'Groups'
	 
	   Returns:
	   - $groupname['group_name']
	 */
	function get_groupname($group_id)
	{
		global $MYSQL_connection;
		$groupname=mysqli_fetch_array(mysqli_query($MYSQL_connection,"SELECT group_name FROM Groups WHERE group_id=$group_id;"));
		return $groupname['group_name'];
	}
	
	/* Function: get_software_id()
	   Gets the software_id key associated with the given software name in the 'Software' table
	   
	   Parameters:
	   $softwarename - plaintext name of the software (eg. 'COMD')
	 
	   Returns:
	   - $software_id['software_id']
	 */
	function get_software_id($softwarename)
	{
		global $MYSQL_connection;
		$software_id=mysqli_fetch_array(mysqli_query($MYSQL_connection,"SELECT software_id FROM Software WHERE software_name=$softwarename;"));
		return $software_id['software_id'];
	}
	
	/* Function: get_softwarename()
	   Gets the name of the software with the given software_id
	   
	   Parameters:
	   $software_id - software_id key stored in the database table 'Software'
	 
	   Returns:
	   - $softwarename['software_name']
	 */
	function get_softwarename($software_id)
	{
		global $MYSQL_connection;
		$softwarename=mysqli_fetch_array(mysqli_query($MYSQL_connection,"SELECT software_name FROM Software WHERE software_id=$software_id;"));
		return ;
	}
	
	/* Function: get_notification_type_id()
	   Gets the notification_type_id key associated with the given notification type in the 'Notification_Type' table
	   
	   Parameters:
	   $notification_type - plaintext name of the notification type (eg. 'admin', 'general')
	   
	   Returns: 
	   - $notification_type_id['notification_type_id']
	 */
	function get_notification_type_id($notification_type)
	{
		global $MYSQL_connection;
		$notification_type_id=mysqli_fetch_array(mysqli_query($MYSQL_connection,"SELECT notification_type_id FROM Notification_Type WHERE notification_type=$notification_type;"));
		return $notification_type_id['notification_type_id'];
	}
	
	/* Function: get_notification_type()
	   Gets the name of the notification type with the given notification_type_id
	 
	   Parameters:
	   $notification_type_id - notification_type_id key stored in the database table 'Notification_Type'
	 
	   Returns:
	   - $notification_type['notification_type']
	 */
	function get_notification_type($notification_type_id)
	{
		global $MYSQL_connection;
		$notification_type=mysqli_fetch_array(mysqli_query($MYSQL_connection,"SELECT notification_type FROM Notification_Type WHERE notification_type_id=$notification_type_id;"));
		return $notification_type['notification_type'];
	}
	
	/* Function: get_app_settings_id()
	   Gets the app_settings_id key associated with the given application setting in the 'App_Settings' table
	   
	   Parameters:
	   $settingname - plaintext name of the application setting (eg. 'notify delay', 'warning period')
	  
	   Returns:
	   - $app_settings_id['app_settings_id']
	 */
	function get_app_settings_id($settingname)
	{
		global $MYSQL_connection;
		$app_settings_id=mysqli_fetch_array(mysqli_query($MYSQL_connection,"SELECT app_settings_id FROM App_Settings WHERE app_settings_name=$settingname;"));
		return $app_settings_id['app_settings_id'];
	}
	
	/* Function: get_app_settings_name()
	   Gets the name of the application setting with the given app_settings_id key
	   
	   Parameters:
	   $setting_id - app_settings_id key stored in the database table 'App_Settings'
	 
	   Returns:
	   - $settingname['app_settings_name'] 
	 */
	function get_settingname($setting_id)
	{
		global $MYSQL_connection;
		$settingname=mysqli_fetch_array(mysqli_query($MYSQL_connection,"SELECT app_settings_name FROM App_Settings WHERE app_settings_id=$setting_id;"));
		return $settingname['app_settings_name'];
	}
	
	/* Function: get_research_desc_id()
	   Gets the research_desc_id key for the research description associated with the given user in the 'User_Research_Description' table
	
	   Parameters:
	   $username - $username
	 
	   Returns:
	   - $research_desc_id['research_desc_id']
	 */
	function get_research_desc_id($username)
	{
		global $MYSQL_connection;
		$user_id=get_user_id($username);
		$research_desc_id=mysqli_fetch_array(mysqli_query($MYSQL_connection,"SELECT research_desc_id FROM User_Research_Description WHERE user_id=$user_id;"));
		return $research_desc_id['research_desc_id'];
	}
	
	/* Function: get_department_id_from_code()
	   Gets the department_id key associated with the given department_code in the 'Department' table
	   
	   Parameters:
	   $department_code - shortcode for department (eg. 'CHEM', 'AERO')
	   
	   Returns:
	   - $department_id['department_id'] 
	 */
	function get_department_id_from_code($department_code)
	{
		global $MYSQL_connection;
		$department_id=mysqli_fetch_array(mysqli_query($MYSQL_connection,"SELECT department_id FROM Department WHERE department_code=$department_code;"));
		return $department_id['department_id'];
	}
	
	/* Function: get_department_id_from_name()
	   Gets the department_id key associated with the given department_name in the 'Department' table
	 
	   Parameters:
	   $department_name - plaintext name of department (eg. 'Chemistry', 'Aerospace Engineering')
	 
	   Returns: 
	   - $department_id['department_id'] 
	 */
	function get_department_id_from_name($department_name)
	{
		global $MYSQL_connection;
		$department_id=mysqli_fetch_array(mysqli_query($MYSQL_connection,"SELECT department_id FROM Department WHERE department_name=$department_name;"));
		return $department_id['department_id'];
	}
	
	/* Function: get_department_code_from_name()
	   Gets the department_code shortcode associated with the given department_name in the 'Department' table
	   
	   Parameters:
	   $department_name - plaintext name of department (eg. 'Chemistry', 'Aerospace Engineering')
	 
	   Returns:
	   - $department_code['department_code']
	 */
	function get_department_code_from_name($department_name)
	{
		global $MYSQL_connection;
		$department_code=mysqli_fetch_array(mysqli_query($MYSQL_connection,"SELECT department_code FROM Department WHERE department_name=$department_name;"));
		return $department_code['department_code'];
	}
	
	/* Function: get_department_code_from_id()
	   Gets the department_code shortcode associated with the given department_id key in the 'Department' table
	   
	   Parameters:
	   $department_id - department_id key stored in the database table 'Department'
	 
	   Returns: 
	   - $department_code['department_code'] 
	 */
	function get_department_code_from_id($department_id)
	{
		global $MYSQL_connection;
		$department_code=mysqli_fetch_array(mysqli_query($MYSQL_connection,"SELECT department_code FROM Department WHERE department_id=$department_id;"));
		return $department_code['department_code'];
	}
	
	/* Function: get_department_name_from_code()
	   Gets the name of the department associated with the given department_code shortcode in the 'Department' table
	   
	   Parameters:
	   $department_code - shortcode name of department (eg. 'CHEM', 'AERO')
	  
	   Returns:
	   - $department_name['department_name']
	 */
	function get_department_name_from_code($department_code)
	{
		global $MYSQL_connection;
		$department_name=mysqli_fetch_array(mysqli_query($MYSQL_connection,"SELECT department_name FROM Department WHERE department_code=$department_code;"));
		return $department_name['department_name'];
	}
	
	/* Function: get_department_name_from_id()
	   Gets the name of the department associated with the given department_id key in the 'Department' table
	   
	   Parameters:
	   $department_id - department_id key in the database table 'Department'
	 
	   Returns:
	   - $department_name['department_name'] 
	 */
	function get_department_name_from_id($department_id)
	{
		global $MYSQL_connection;
		$department_name=mysqli_fetch_array(mysqli_query($MYSQL_connection,"SELECT department_name FROM Department WHERE department_id=$department_id;"));
		return $department_name['department_name'];
	}

	/* Function: get_role_id()
	   Gets role_id key stored in the 'Roles' table
	   
	   Parameters:
	   $role_name - string name of role
	 
	   Returns:
	   - $role_id['role_id']	
	 */
	function get_role_id($role_name)
	{
		global $MYSQL_connection;
		$role_id = mysqli_fetch_array( mysqli_query( $MYSQL_connection, "SELECT role_id FROM Roles WHERE role_name = $role_name;" ) );
		return $role_id['role_id'];
	}

	/* Function: get_role_name()
	   Gets role_name stored in the 'Roles' table
	   
	   Parameters:
	   $role_id - int id key of role
	 
	   Returns:
	   - $role_name['role_name']	
	 */
	function get_role_name( $role_id )
	{
		global $MYSQL_connection;
		$role_name = mysqli_fetch_array( mysqli_query( $MYSQL_connection, "SELECT role_name FROM Roles WHERE role_id = $role_id;" ) );
		return $role_name['role_name'];
	}

	/* Function: get_preference_id()
	   Gets preference_id key stored in the 'User_Preferences' table
	   
	   Parameters:
	   $preference_name - string name of preference
	 
	   Returns:
	   - $preference_id['preference_id'] 	
	 */
	function get_preference_id( $preference_name )
	{
		global $MYSQL_connection;
		$preference_id = mysqli_fetch_array( mysqli_query( $MYSQL_connection, "SELECT preference_id FROM User_Preferences WHERE preference_name = $preference_name;" ) );
		return $preference_id['preference_id'];
	}

	/* Function: get_preference_name()
	   Gets preference name stored in the 'User_Preferences' table
	   
	   Parameters:
	   $preference_id - int id key of preference
	 
	   Returns:
	   - $preference_name['preference_name']
	   */
	function get_preference_name( $preference_id )
	{
		global $MYSQL_connection;
		$preference_name = mysqli_fetch_array( mysqli_query( $MYSQL_connection, "SELECT preference_name FROM User_Preferences WHERE preference_id = $preference_id;" ) );
		return $preference_name['preference_name'];
	}
	
	/* Function: get_exists()
	   Check to see if a value exists for a column in a specific table
	
	   Parameters:
	   $table_name - string giving the name of the table in which to search (eg. 'Users', 'Software')
	   $column_list - array of string names of the columns to check (eg. 'user_id', 'tos_id')
	   $value_list - array of strings of which values to search for in the given columns
	 
	   Returns:
	   - TRUE (1) if a match exists for given parameters
	   - FALSE (-1) if no match exists
	   - NULL if column_list and value_list do not have the same length
	 */
	function get_exists($table_name, $column_list, $value_list)
	{
		if(count($column_list) != count($value_list))
		{
			return null;
		}
		global $MYSQL_connection;
		$query="SELECT EXISTS(SELECT 1 FROM $table_name WHERE ";
		for($i=0; $i < count($column_list); ++$i)
		{
			$query.=$column_list[$i]."=".$value_list[$i];
			if($i < (count($column_list)-1))
			{
				$query.=" AND ";
			}
		}
		$query.=") AS Exist;";
		$entry_exists=mysqli_query($MYSQL_connection,$query);
		$result=mysqli_fetch_array($entry_exists);
		return ($result['Exist'] == 1);
	}
	
	/* Function: archive_research_description()
	   Archives the current research description of the given user
	   
	   Parameters:
	   $username - NetID of the user
	  
	   Returns:
	   - TRUE on archive success
	   - FALSE on archive fail
	 */
	function archive_research_description($username)
	{
		global $MYSQL_connection;
		$user_id=get_user_id($username);
		$research_desc=get_research_desc($username);
		if(mysqli_query($MYSQL_connection, "INSERT INTO User_Research_Description_Log (user_id, research_desc, archive_date) VALUES ($user_id, $research_desc, (SELECT NOW()));"))
		{
			return true;
		}
		return false;
	}
	
	/* Function: get_host_status()
	   Gets the status id from the 'User_Host_MAP' table based on the given user and host
	   
	   Parameters:
	   $username - NetID of the user
	   $hostname - plaintext name of the host
	  
	   Returns:
	   - $status_id['status_id'] if the status_id key of the match if a match is found
	   - FALSE if no match found
	 */
	function get_host_status($username, $hostname)
	{
		global $MYSQL_connection;
		$user_id=get_user_id($username);
		$host_id=get_host_id($hostname);
		$result=mysqli_query($MYSQL_connection,"SELECT status_id FROM User_Host_MAP WHERE (user_id=$user_id AND host_id=$host_id);");
		if(!$result)
		{
			return false;
		}
		$status_id=mysqli_fetch_array($result);
		return $status_id['status_id'];
	}
	
	/* Function: get_group_status_id()
	   Gets the status id from the 'User_Group_MAP' based on the given user and group
	   
	   Parameters:
	   $username - NetID or ChemID of the user
	   $groupname - plaintext name of the group
	 
	   Returns:
	   - $status_id['status_id'] if the status_id key of the match if a match is found
	   - FALSE if no match found
	 */
	function get_group_status_id($username, $groupname)
	{
		global $MYSQL_connection;
		$user_id=get_user_id($username);
		$group_id=get_group_id($groupname);
		$result=mysqli_query($MYSQL_connection,"SELECT status_id FROM User_Group_MAP WHERE (user_id=$user_id AND group_id=$group_id);");
		if(!$result)
		{
			return false;
		}
		$status_id=mysqli_fetch_array($result);
		return $status_id['status_id'];
	}
	
	/* Function: remove_user_host_map()
	   Removes a mapping between the given user and host on the 'User_Host_MAP' database table
	   
	   Parameters:
	   $username - NetID or ChemID of user
	   $hostname - plaintext name of host
	 
	   Returns: 
	   - TRUE on success
	   - FALSE on failure
	   - NULL if no such mapping exists
	 */
	function remove_user_host_map($username, $hostname)
	{
		global $MYSQL_connection;
		$user_id=get_user_id($username);
		$host_id=get_host_id($hostname);
		$table_name="User_Host_MAP";
		$column_list=array("user_id", "host_id");
		$value_list=array($user_id, $host_id);
		if(get_exists($table_name, $column_list, $value_list))
		{
			return mysqli_query($MYSQL_connection,"DELETE FROM $table_name WHERE (user_id=$user_id AND host_id=$host_id);");
		}
		else
		{
			return null;
		}
	}
	
	/* Function: remove_host_mappings()
	   Removes all mappings in the 'User_Host_MAP' database table associated with the given host
	   
	   Parameters:
	   $hostname - plaintext name of host
	 
	   Returns: 
	   - TRUE on success
	   - FALSE on failure
	 */
	function remove_host_mappings($hostname)
	{
		global $MYSQL_connection;
		$host_id=get_host_id($hostname);
		return mysqli_query($MYSQL_connection,"DELETE FROM User_Host_MAP WHERE host_id=$host_id;");
	}
	
	/*------------------------------------------------------------------------------------------------*/
	/* Group: System Functions */
	/*------------------------------------------------------------------------------------------------*/
	
	/* Function: check_TOS()
	   Checks the 'User_TOS_MAP' table for a match between the user and the TOS. If a match is found, compares the accept_date of the MAP with the create_date of the TOS
	 
	   Parameters:
	   $username - NetID of the user
	   $tosname - plaintext name of the Terms of Service
	 
	   Returns:
	   - TRUE if TOS is current
	   - FALSE if TOS is expired or no match found 
	 */
	function check_TOS($username, $tosname)
	{
		global $MYSQL_connection;
		$tos_id=get_tos_id($tosname);
		$user_id=get_user_id($username);
		//compares create date of TOS to accept date of TOS, returns - number if accept is newer, + number if create is newer
		/*$date_diff=mysqli_query($MYSQL_connection,"SELECT TIMESTAMPDIFF(SECOND, (SELECT create_date FROM TOS WHERE tos_id=$tos_id),(SELECT accept_date FROM User_TOS_MAP WHERE (user_id=$user_id AND tos_id=$tos_id))) AS DiffDate;");
		if((!$date_diff) || (mysqli_fetch_array($result))['DiffDate'] < 0)
		{
			return false;
		}
		else
		{
			return true;
		}*/
		$table_name="User_TOS_MAP";
		$column_list=array("user_id", "tos_id");
		$value_list=array($user_id, $tos_id);
		if(!get_exists($table_name, $column_list, $value_list))
		{
			return false;
		}
		$result=mysqli_query($MYSQL_connection,"SELECT ISNEWER((SELECT create_date FROM TOS WHERE tos_id=$tos_id),(SELECT accept_date FROM User_TOS_MAP WHERE (user_id=$user_id AND tos_id=$tos_id))) AS DiffDate");
		$diffdate=mysqli_fetch_array($result);
		return $diffdate['DiffDate'];
	}
	
	/* Function: get_TOS()
	   Gets the Terms of Service text for the given TOS Name
	   
	   Parameters:
	   $tosname = plaintext name of the Terms of Service
	 
	   Returns: 
	   - $tos['tos_text']
	 */
	function get_TOS($tosname)
	{
		global $MYSQL_connection;
		$tos_id=get_tos_id($tosname);
		$tos=mysqli_fetch_array(mysqli_query($MYSQL_connection,"SELECT tos_name,tos_desc FROM TOS WHERE tos_id=$tos_id;"));
		return $tos['tos_text'];
	}
	
	/* Function: accept_TOS()
	   Updates accept_date in the 'User_TOS_MAP' table if an association between the user and the TOS. If no association exists, creates an entry.
	 
	   Parameters:
	   $username = NetID of user
	   $tosname = plaintext name of the Terms of Service
	 
	   Returns: 
	   - TRUE if accept is successful
	   - FALSE if accept fails
	 */
	function accept_TOS($username, $tosname)
	{
		global $MYSQL_connection;
		$user_id=get_user_id($username);
		$tos_id=get_tos_id($tosname);
		//check if there is already an entry for the user/TOS combination (ie. user previously accepted an expired version of the TOS
		$table_name="User_TOS_MAP";
		$column_list=array("user_id", "tos_id");
		$value_list=array($user_id, $tos_id);
		if(get_exists($table_name, $column_list, $value_list)) //if entry exists, update the entry with the new date
		{
			if(mysqli_query($MYSQL_connection,"UPDATE User_TOS_MAP SET accept_date=(SELECT NOW()) WHERE (user_id=$user_id AND tos_id=$tos_id);"))
				return true;
		}
		else //create an entry
		{
			if(mysqli_query($MYSQL_connection,"INSERT INTO User_TOS_MAP (user_id, tos_id, accept_date) VALUES ($user_id, $tos_id, (SELECT NOW()));"))
				return true;
		}
		return false;
	}
	
	/* Function: get_research_description()
	   Gets the current research description for the given user
	 
	   Parameters:
	   $username - NetID of user
	   
	   Returns:
	   - $research_desc['research_desc'] 
	   - FALSE if no research description exists for user
	 */
	function get_research_description($username)
	{
		global $MYSQL_connection;
		$user_id=get_user_id($username);
		$table_name="User_Research_Description";
		$column_list=array("user_id");
		$value_list=array($user_id);
		if(get_exists($table_name, $column_list, $value_list))
		{
			$result=mysqli_query($MYSQL_connection,"SELECT research_desc FROM $table_name WHERE user_id=$user_id;");
			$research_desc=mysqli_fetch_array($result);
			return $research_desc['research_desc'];
		}
		return false;
	}
	
	/* Function: check_research_description()
	   Checks 'User_Research_Description' table for an entry for the given user
	   
	   Parameters:
	   $username - NetID of user
	
	   Returns: 
	   - TRUE if the research description status is 1(Pending) or 2(Active) or 3(Pending - Group) or 4(Warning) or 6(Under Review)
	   - FALSE for any other scenario
	 */
	function check_research_description($username)
	{
		global $MYSQL_connection;
		$user_id=get_user_id($username);
		//check if entry exists for this user
		$table_name="User_Research_Description";
		$column_list=array("user_id");
		$value_list=array($user_id);
		if(get_exists($table_name, $column_list, $value_list))
		{
			$status_id_array=mysqli_fetch_array(mysqli_query($MYSQL_connection,"SELECT status_id FROM User_Research_Description WHERE user_id=$user_id;"));
			$status_id=$status_id_array['status_id'];
			if($status_id == 1 || $status_id == 2 || $status_id == 3 || $status_id == 4 || $status_id == 6)
				return true;
		}
		return false;
	}
	
	/* Function: get_user_software()
	   Get list of software names associated with given user
	 
	   Parameters:
	   $username - NetID of user
	   
	   Returns:
	   - $software_list 
	 */
	function get_user_software($username)
	{
		global $MYSQL_connection;
		$user_id=get_user_id($username);
		$result=mysqli_query($MYSQL_connection,"SELECT software_id FROM User_Software_MAP WHERE user_id=$user_id;");
		$software_list=array();
		$i=0;
		while($row=mysqli_fetch_array($result))
		{
			$software_list[$i]=get_softwarename($row['software_id']);
			$i++;
		}
		return $software_list;
	}
	
	/* Function: get_software_users()
	   Get list of usernames associated with given software
	 
	   Parameters:
	   $softwarename - plaintext name of software
	 
	   Returns: 
	   - $user_list
	 */
	function get_software_users($softwarename)
	{
		global $MYSQL_connection;
		$software_id=get_software_id($softwarename);
		$result=mysqli_query($MYSQL_connection,"SELECT user_id FROM User_Software_MAP WHERE software_id=$software_id;");
		$user_list=array();
		$i=0;
		while($row=mysqli_fetch_array($result))
		{
			$user_list[$i]=get_username($row['user_id']);
			$i++;
		}
		return $user_list;
	}

	/* Function: get_user_ext_software()
	   Get list of software names associated with given user
	 
	   Parameters:
	   $username - NetID of user
	 
	   Returns: $software_list
	   -  
	 */
	function get_user_ext_software($username)
	{
		global $MYSQL_connection;
		$user_id=get_user_id($username);
		$result=mysqli_query($MYSQL_connection,"SELECT software_id FROM User_Software_External_MAP WHERE user_id=$user_id;");
		$software_list=array();
		$i=0;
		while($row=mysqli_fetch_array($result))
		{
			$software_list[$i]=get_softwarename($row['software_id']);
			$i++;
		}
		return $software_list;
	}
	
	/* Function: get_ext_software_users()
	   Gets a list of user_id's associated with given software
	 
	   Parameters:
	   $softwarename - plaintext name of software
	 
	   Returns:
	   - $user_list 
	 */
	function get_ext_software_users($softwarename)
	{
		global $MYSQL_connection;
		$software_id=get_software_id($softwarename);
		$result=mysqli_query($MYSQL_connection,"SELECT user_id FROM User_Software_External_MAP WHERE software_id=$software_id;");
		$user_list=array();
		$i=0;
		while($row=mysqli_fetch_array($result))
		{
			$user_list[$i]=get_username($row['user_id']);
			$i++;
		}
		return $user_list;
	}

	/* Function: get_host_software()
	   Gets a list of software names associated with given user
	
	   Parameters:
	   $hostname - name of host
	 
	   Returns:
	   - $software_list 
	 */
	function get_host_software($hostname)
	{
		global $MYSQL_connection;
		$host_id=get_host_id($hostname);
		$result=mysqli_query($MYSQL_connection,"SELECT software_id FROM Host_Software_MAP WHERE host_id=$host_id;");
		$software_list=array();
		$i=0;
		while($row=mysqli_fetch_array($result))
		{
			$software_list[$i]=get_softwarename($row['software_id']);
			$i++;
		}
		return $software_list;
	}
	
	/* Function: get_software_hosts()
	   Gets a list of host_id's associated with given software
	
	   Parameters:
	   $softwarename - plaintext name of software
	 
	   Returns:
	   - $host_list
	 */
	function get_software_hosts($softwarename)
	{
		global $MYSQL_connection;
		$software_id=get_software_id($softwarename);
		$result=mysqli_query($MYSQL_connection,"SELECT host_id FROM Host_Software_MAP WHERE software_id=$software_id;");
		$host_list=array();
		$i=0;
		while($row=mysqli_fetch_array($result))
		{
			$host_list[$i]=get_hostname($row['host_id']);
			$i++;
		}
		return $host_list;
	}

	/* Function: get_ext_host_software()
	   Gets a list of software names associated with given external host
	 
	   Parameters:
	   $exthostname - name of external host
	   
	   Returns:
	   - $software_list  
	 */
	function get_ext_host_software($exthostname)
	{
		global $MYSQL_connection;
		$external_host_id=get_ext_host_id($exthostname);
		$result=mysqli_query($MYSQL_connection,"SELECT software_id FROM External_Host_Software_MAP WHERE external_host_id=$external_host_id;");
		$software_list=array();
		$i=0;
		while($row=mysqli_fetch_array($result))
		{
			$software_list[$i]=get_softwarename($row['software_id']);
			$i++;
		}
		return $software_list;
	}
	
	/* Function: get_software_hosts()
	   Gets a list of external_host_id's associated with given software
	 
	   Parameters:
	   $softwarename - plaintext name of software
	 
	   Returns:
	   - $ext_host_list
	 */
	function get_software_ext_hosts($softwarename)
	{
		global $MYSQL_connection;
		$software_id=get_software_id($softwarename);
		$result=mysqli_query($MYSQL_connection,"SELECT ext_host_id FROM External_Host_Software_MAP WHERE software_id=$software_id;");
		$ext_host_list=array();
		$i=0;
		while($row=mysqli_fetch_array($result))
		{
			$host_list[$i]=get_exthostname($row['external_host_id']);
			$i++;
		}
		return $ext_host_list;
	}

	/* Function get_user_preferences()
	   Gets a list of preferences user has selected
	 
	   Parameters:
	   $username - NetID or ChemID of user
	 
	   Returns:
	   - $host_list
	 */
	function get_user_preferences( $username )
	{
		global $MYSQL_connection;
		$user_id = get_user_id( $username );
		$result = mysqli_query( $MYSQL_connection, "SELECT preference_id FROM User_User_Preferences_MAP WHERE user_id = $user_id;" );
		$pref_list = array();
		$i = 0;
		while( $row = mysqli_fetch_array( $result ) )
		{
			$host_list[$i] = get_hostname( $row['host_id'] );
			$i++;
		}
		return $host_list;
	}

	/* Function: get_user_roles()
	   Gets a list of roles given to user
	 
	   Parameters:
	   $username - user's NetID or ChemID
	 
	   Returns:
	   - $role_list 
	 */
	function get_user_roles( $username )
	{
		global $MYSQL_connection;
		$user_id = get_user_id( $username );
		$result = mysqli_query( $MYSQL_connection, "SELECT role_id FROM User_Roles_MAP WHERE user_id = $user_id;" );
		$role_list = array();
		$i = 0;
		while( $row = mysqli_fetch_array( $result ) )
		{
			$role_list[$i] = get_role_name( $row['role_id'] );
			$i++;
		}
		return $role_list;
	}

	/* Function: get_users_with_role()
	   Gets a list of users with the given role
	 
	   Parameters:
	   $rolename - name of role
	 
	   Returns:
	   - $user_list 
	 */
	function get_users_with_role( $rolename )
	{
		global $MYSQL_connection;
		$role_id = get_role_id( $rolename );
		$result = mysqli_query( $MYSQL_connection, "SELECT user_id FROM User_Roles_MAP WHERE role_id = $role_id;" );
		$user_list = array();
		$i = 0;
		while( $row = mysqli_fetch_array( $result ) )
		{
			$user_list[$i] = get_username( $result );
			$i++;
		}
		return $user_list;
	}
	
	/* Function: get_software_description()
	   Gets a description of given software
	 
	   Parameters:
	   $softwarename - plaintext name of software
	 
	   Returns:
	   - $software_desc['sofware_desc']
	 */
	function get_software_description($softwarename)
	{
		global $MYSQL_connection;
		$software_id=get_software_id($softwarename);
		$result=mysqli_query($MYSQL_connection,"SELECT software_desc FROM Software WHERE software_id=$software_id;");
		$software_desc=mysqli_fetch_array($result);
		return $software_desc['sofware_desc'];
	}
	
	/* Function: check_sponsorship()
	    Checks to see if sponsorship requirement is needed and if needed, has been met for user
	 
	   Parameters:
	   $username - NetID of user
	 
	   Returns: 
	   - TRUE if no sponsor required
	   - TRUE if sponsor required and sponsor set
	   - FALSE if if sponsor required and no sponsor set
	 */
	function check_sponsorship($username)
	{
		global $MYSQL_connection;
		$user_id=get_user_id($username);
		//check if sponsor required
		$sponsor_required=mysqli_fetch_array(mysqli_query($MYSQL_connection,"SELECT sponsor_required FROM Users WHERE user_id=$user_id;"));
		if($sponsor_required['sponsor_required'])
		{
			//sponsor required, so check if a sponsor exists
			$table_name="Sponsorship_MAP";
			$column_list=array("sponsored_user_id");
			$value_list=array($user_id);
			if(get_exists($table_name, $column_list, $value_list))
				return true;
			else
				return false;
		}
		else
			return true;
	}
	
	/* Function: force_sponsor_request()
	   ---- UNDOCUMENTED, EMPTY FUNCTION ---- Travis' comment: should this be somewhere else? ----
	 
	   Parameters:
	   $username - NetID of user
	 
	   Returns:
	   - ???
	 */
	function force_sponsor_request($username)
	{
		global $MYSQL_connection;
	}
	
	/* Function: get_notifications_for_user()
	   Gets all notifications associated with a given user.
	 
	   Parameters:
	   $username - NetID of user
	 
	   Returns: 
	   a 2D array of notifications of the format:
	   - $notifications['Name'][$i]
	   - $notifications['Desc'][$i]
	   
	   where $i denotes the number of the notification in the set
	 */
	function get_notifications_for_user($username)
	{
		global $MYSQL_connection;
		$user_id=get_user_id($username);
		$notifications_result=mysqli_query($MYSQL_connection,"SELECT notification_id FROM Notification_User_MAP WHERE user_id=$user_id;");
		$notifications=array(
			'Name'=>array(),
			'Desc'=>array());
		$i=0;
		//parse results into a standard PHP 2D array to be returned
		while($row=mysqli_fetch_array($notifications_result))
		{
			$notification_id=$row['notification_id'];
			$notification_result=mysqli_query($MYSQL_connection,"SELECT notification, notification_desc FROM Notifications WHERE notification_id=$notification_id;");
			$notification=mysqli_fetch_array($notification_result);
			$notifications['Name'][$i]=$notification['notification'];
			$notifications['Desc'][$i]=$notification['notification_desc'];
			$i++;
		}
		return $notifications;
	}
	
	/* Function: get_notifications_by_type()
	   Gets all notifications of the given type
	   
	   Parameters:
	   $notification_type - plaintext name of the notification type

	   Returns:
	   a 2D array of notifications of the format:
	   - notifications['Name'][$i]
	   - notifications['Desc'][$i]
	 
	   where $i denotes the number of the notification in the set
	 */
	function get_notifications_by_type($notification_type)
	{
		//notification_type = the notification_type_id
		global $MYSQL_connection;
		$notifications_result=mysqli_query($MYSQL_connection,"SELECT notification, notification_desc FROM Notifications WHERE notification_type_id=$notification_type;");
		$notifications=array(
			'Name'=>array(),
			'Desc'=>array());
		$i=0;
		//parse results into a standard PHP 2D array to be returned
		while($row=mysqli_fetch_array($notifications_result))
		{
			$notification=mysqli_fetch_array($row);
			$notifications['Name'][$i]=$notification['notification'];
			$notifications['Desc'][$i]=$notification['notification_desc'];
			$i++;
		}
		return $notifications;
	}
	
	/* Function: adjust_access_status()
	   Adjusts the access for the user to the given host based on the given access_status
	 
	   Parameters:
	   $username - NetID of user
	   $hostname - plaintext name of host
	   $access_status - string defining action ("active", "warning", "expire")
	  
	   Returns:
	   - TRUE if change is successful
	   - FALSE if change fails
	 */
	function adjust_access_status($username, $hostname, $access_status)
	{
		global $MYSQL_connection;
		//check if an entry exists on the user/host map
		$user_id=get_user_id($username);
		$host_id=get_host_id($hostname);
		$table_name="User_Host_MAP";
		$column_list=array("user_id", "host_id");
		$value_list=array($user_id, $host_id);
		$status_id = PENDING;
		if($access_status == "active")
		{
			$status_id = ACTIVE;
		}
		else if($access_status == "warning")
		{
			$status_id = WARNING;
		}
		else if($access_status == "expire")
		{
			$status_id = EXPIRE;
		}
		if(get_exists($table_name, $column_list, $value_list))
		{
			if(mysqli_query($MYSQL_connection,"UPDATE $table_name SET status_id=$status_id, create_date=(SELECT NOW()) WHERE (user_id=$user_id AND host_id=$host_id);"))
				return true;
		}
		else
		{
			if(mysqli_query($MYSQL_connection,"INSERT INTO $table_name (user_id, host_id, status_id, create_date) VALUES ($user_id, $host_id, $status_id, (SELECT NOW());"))
				return true;
		}
		return false;
	}
	
	/* Function: activate_access()
	   Calls adjust_access_status() using "active" as the access_status
	   
	   Parameters:
	   $username - NetID of user
	   $hostname - Plaintext name of host
	   
	   Returns:
	   - TRUE if change is successful
	   - FALSE if change fails
	 */
	function activate_access($username, $hostname)
	{
		return adjust_access_status($username, $hostname, "active");
	}
	
	/* Function: warn_access()
	   Calls adjust_access_status() using "warning" as the access_status
	   
	   Parameters:
	   $username - NetID of user
	   $hostname - Plaintext name of host
	   
	   Returns:
	   - TRUE if change is successful
	   - FALSE if change fails
	 */
	function warn_access($username, $hostname)
	{
		return adjust_access_status($username, $hostname, "warning");
	}
	
	/* Function: expire_access()
	   Calls adjust_access_status() with using "expire" as the access_status
	   
	   Parameters:
	   $username - NetID of user
	   $hostname - Plaintext name of host
	   
	   Returns:
	   - TRUE if change is successful
	   - FALSE if change fails
	 */
	function expire_access($username, $hostname)
	{
		return adjust_access_status($username, $hostname, "expire");
	}
	
	/*------------------------------------------------------------------------------------------------*/
	/* Group: User Functions */
	/*------------------------------------------------------------------------------------------------*/
	
	/* Function: update_research_description()
	   Calls archive_research_description()
	   
	   If archive function fails, update will halt and fail (old research description will not be overwritten)
	 
	   Parameters:
	   $username - NetId of user
	   $new_research_description - text of updated research description
	 
	   Returns: 
	   - TRUE if update succeeds
	   - FALSE if update fails
	 */
	function update_research_description($username, $new_research_description)
	{
		global $MYSQL_connection;
		$user_id=get_user_id($username);
		$table_name="User_Research_Description";
		$column_list=array("user_id");
		$value_list=array($usr_id);
		if(get_exists($table_name, $column_list, $value_list))
		{
			$entry=mysqli_query($MYSQL_connection,"SELECT research_desc_id FROM $table_name WHERE user_id=$user_id;");
			$research_desc_id=mysqli_fetch_array($entry);
			if(archive_research_description($research_desc_id['research_desc_id']))
			{
				return mysqli_query($MYSQL_connection,"UPDATE User_Research_Description SET research_desc=$new_research_description, status_id=" . REVIEW . ", create_date=(SELECT NOW()) WHERE research_desc_id=$research_desc_id;");
			}
		}
		else
		{
			return mysqli_query($MYSQL_connection,"INSERT INTO User_Research_Description (user_id, research_desc, create_date) VALUES ($user_id, $new_research_description, (SELECT NOW()));");
		}
		return false;
	}
	
	/* Function: get_host_description()
	   Gets the host_description field of the 'Hosts' table
	   
	   Parameters:
	   $hostname - plaintext name of the host
	   
	   Returns: 
	   $res['host_description']
	 */
	function get_host_description($hostname)
	{
		global $MYSQL_connection;
		$host_id=get_host_id($hostname);
		$result=mysqli_query($MYSQL_connection,"SELECT host_description FROM Hosts WHERE host_id=$host_id;");
		$res=mysqli_fetch_array($result);
		return $res['host_description'];
	}
	
	/* Function: request_host_access()
	   Adds or updates the status entry in the 'User_Host_MAP' table
	   - if no entry exists, or an entry exists with status Warning or Expired, sets status to Pending
	   - if an entry exists with status Pending, Active, Pending-Group, Review, or Denied, returns the status_id
	 
	   Parameters:
	   $username - NetID of user
	   $hostname - plaintext name of host
	 
	   Returns: 
	   - TRUE if no entry exists, or if an entry exists with status Warning or Expired
	   - $status_id if an entry exists with status Pending, Active, Pending-Group, Review, or Denied
	   - FALSE if the function fails
	 */
	function request_host_access($username, $hostname)
	{
		global $MYSQL_connection;
		$user_id=get_user_id($username);
		$host_id=get_host_id($hostname);
		//check if user has already requested host access
		$table_name="User_Host_MAP";
		$column_list=array("user_id", "host_id");
		$value_list=array($user_id, $host_id);
		if(get_exists($table_name, $column_list, $value_list))
		{
			$result=mysqli_query($MYSQL_connection,"SELECT status_id FROM $table_name WHERE (user_id=$user_id AND host_id=$host_id);");
			$status_id_array=mysqli_fetch_array($result);
			$status_id=$status_id_array['status_id'];
			switch($status_id)
			{
				case WARNING:
				case EXPIRED:
					return true;
					break;
				case PENDING:
				case ACTIVE:
				case PENDING_GROUP:
				case REVIEW:
				case DENIED:
					return $status_id;
					break;
				default:
					break;
			}
		}
		else
		{
			return mysqli_query($MYSQL_connection,"INSERT INTO User_Host_MAP (user_id, host_id, create_date) VALUES ($user_id, $host_id, (SELECT NOW()));");
		}
		return false;
	}
	
	/* Function: request_group_access()
	   Adds or updates the status entry in the 'User_Group_MAP' table.
	   
	   - If no entry exists, or an entry exists with status Warning or Expired, sets status to Pending-Group
	   - If an entry exists with status Pending, Active, Pending-Group, Review, or Denied, returns the status_id
	 
	   Parameters:
	   $username - NetID of user
	   $groupname - plaintext name of group
	 
	   Returns: 
	   - TRUE if no entry exists, or if an entry exists with status Warning or Expired
	   - $status_id if an entry exists with status Pending, Active, Pending-Group, Review, or Denied
	   - FALSE if the function fails
	 */
	function request_group_access($username, $groupname)
	{
		global $MYSQL_connection;
		$user_id=get_user_id($username);
		$group_id=get_group_id($groupname);
		//check if user has already requested group access
		$table_name="User_Group_MAP";
		$column_list=array("user_id", "group_id");
		$value_list=array($user_id, $group_id);
		if(get_exists($table_name, $column_list, $value_list))
		{
			$result=mysqli_query($MYSQL_connection,"SELECT status_id FROM $table_name WHERE (user_id=$user_id AND group_id=$group_id);");
			$status_id_array=mysqli_fetch_array($result);
			$status_id=$status_id_array['status_id'];
			switch($status_id)
			{
				case WARNING:
				case EXPIRED:
					return true;
					break;
				case PENDING:
				case ACTIVE:
				case PENDING_GROUP:
				case REVIEW:
				case DENIED:
					return $status_id;
					break;
				default:
					break;
			}
		}
		else
		{
			return mysqli_query($MYSQL_connection,"INSERT INTO User_Group_MAP (user_id, group_id, create_date) VALUES ($user_id, $group_id, (SELECT NOW()));");
		}
		return false;
	}
	
	/*------------------------------------------------------------------------------------------------*/
	/* Group: Administrator/Sponsor/Sponsor Manager Functions */
	/*------------------------------------------------------------------------------------------------*/
	
	/* adjust_sponsorship()
	 *	parameters:
	 *		$username_user = NetID or ChemID of the user to be sponsored
	 *		$username_sponsor = NetID or ChemID of the sponsor
	 *		$sponsorship_status = string defining action ("approve", "deny", "create", "renew", "revoke")
	 * if change is successful, returns true
	 * if change fails, returns false
	 * if no entry to adjust and not attempting "create", returns null
	 * if "create" and entry exists, returns entry create_date
	 */
	function adjust_sponsorship($username_user, $username_sponsor, $username_mod, $sponsorship_status, $start_date, $end_date)
	{
		global $MYSQL_connection;
		$sponsored_user_id=get_user_id($username_user);
		$sponsor_id=get_user_id($username_sponsor);
		$modify_user_id=get_user_id($username_mod);
		$table_name="Sponsorship_MAP";
		$column_list=array("sponsored_user_id", "sponsor_id");
		$value_list=array($sponsored_user_id, $sponsor_id);
		
		if(!get_exists($table_name, $column_list, $value_list))
		{
			if($sponsorship_status == "create")
			{
				return mysqli_query($MYSQL_connection,"INSERT INTO $table_name (sponsored_user_id, sponsor_id, create_date, modify_date, modify_user_id) VALUES ($sponsored_user_id, $sponsor_id, (SELECT NOW()), (SELECT NOW()), $modify_user_id);");
			}
			return null;
		}
		else
		{
			if($sponsorship_status == "create")
			{
				$result=mysqli_query($MYSQL_connection,"SELECT create_date FROM Sponsorship_MAP WHERE (sponsored_user_id=$sponsored_user_id AND sponsor_id=$sponsor_id);");
				$create_date=mysqli_fetch_array($result);
				return $create_date['create_date'];
			}
			else if($sponsorship_status == "approve" || $sponsorship_status == "renew")
			{
				return mysqli_query($MYSQL_connection,"UPDATE Sponsorship_MAP SET status_id=" . ACTIVE . ", start_date=$start_date, end_date=$end_date, modify_date=(SELECT NOW()), modify_user=$user_id_mod WHERE (sponsored_user_id=$sponsored_user_id AND sponsor_id=$sponsor_id);");
			}
			else if($sponsorship_status == "deny" || $sponsorship_staus == "revoke")
			{
				return mysqli_query($MYSQL_connection,"UPDATE Sponsorship_MAP SET status_id=" . REVIEW . ", end_date=(SELECT NOW()), modify_date=(SELECT NOW()), modify_user=$modify_user_id WHERE (sponsored_user_id=$sponsored_user_id AND sponsor_id=$sponsor_id);");
			}
		}
		return false;		
	}
	
	/* approve_sponsorship()
	 * passes through to adjust_sponsorship() with the "approve" sponsorship_status
	 * returns true if change is successful
	 * returns false if change fails
	 * if no existing entry, returns null
	 */
	function approve_sponsorship($username_user, $username_sponsor)
	{
		return adjust_sponsorship($username_user, $username_sponsor, "approve");
	}
	
	/* deny_sponsorship()
	 * passes through to adjust_sponsorship() with the "deny" sponsorship_status
	 * returns true if change is successful
	 * returns false if change fails
	 * if no existing entry, returns null
	 */
	function deny_sponsorship($username_user, $username_sponsor)
	{
		return adjust_sponsorship($username_user, $username_sponsor, "deny");
	}
	
	/* create_sponsorship()
	 * passes through to adjust_sponsorship() with the "create" sponsorship_status
	 * returns true if change is successful
	 * returns false if change fails
	 * if entry already exists, returns entry create_date
	 */
	function create_sponsorship($username_user, $username_sponsor)
	{
		return adjust_sponsorship($username_user, $username_sponsor, "create");
	}
	
	/* renew_sponsorship()
	 * passes through to adjust_sponsorship() with the "renew" sponsorship_status
	 * returns true if change is successful
	 * returns false if change fails
	 * if no existing entry, returns null
	 */
	function renew_sponsorship($username_user, $username_sponsor)
	{
		return adjust_sponsorship($username_user, $username_sponsor, "renew");
	}
	
	/* revoke_sponsorship()
	 * passes through to adjust_sponsorship() with the "revoke" sponsorship_status
	 * returns true if change is successful
	 * returns false if change fails
	 * if no existing entry, returns null
	 */
	function revoke_sponsorship($username_user, $username_sponsor)
	{
		return adjust_sponsorship($username_user, $username_sponsor, "revoke");
	}
	
	/* get_sponsor()
	 *	parameters:
	 *		$username_user = NetID or ChemID of user who is sponsored
	 * returns NetID of user's sponsor
	 * if user has no sponsor and sponsor is required, returns false
	 * if no sponsor is required, returns true
	 */
	function get_sponsor($username_user)
	{
		global $MYSQL_connection;
		$user_id=get_user_id($username_user);
		$result=mysqli_query($MYSQL_connection,"SELECT sponsor_required FROM Users WHERE user_id=$user_id;");
		$res=mysqli_fetch_array($result);
		if($res['sponsor_required'])
		{
			$result->free();
			$table_name="Sponsorship_MAP";
			$column_list=array("sponsored_user_id");
			$value_list=array($user_id);
			if(get_exists($table_name, $column_list, $value_list))
			{
				$result=mysqli_query($MYSQL_connection,"SELECT sponsor_id FROM $table_name WHERE sponsored_user_id=$user_id;");
				$sponsor_id=mysqli_fetch_array($result);
				$sponsorname=get_username($sponsor_id['sponsor_id']);
				return $sponsorname;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return true;
		}
	}
	
	/* get_sponsored_users()
	 *	parameters:
	 *		$username_sponsor = NetID or ChemID of sponsor
	 * returns array of NetID's for users sponsored by given user
	 * if user does not sponsor any other users, returns false
	 */
	function get_sponsored_users($username_sponsor)
	{
		global $MYSQL_connection;
		$user_id=get_user_id($username_sponsor);
		$table_name="Sponsorship_MAP";
		$column_list=array("sponsor_id");
		$value_list=array($user_id);
		if(get_exists($table_name, $column_list, $value_list))
		{
			$result=mysqli_query($MYSQL_connection,"SELECT sponsored_user_id FROM Sponsorship_MAP WHERE sponsor_id=$user_id;");
			$sponsored_users=array();
			$i=0;
			while($row=mysqli_fetch_array($result))
			{
				$sponsored_users[$i]=$row['sponsored_user_id'];
			}
			return $sponsored_users;
		}
		else
		{
			return false;
		}
	}
	
	/*------------------------------------------------------------------------------------------------*/
	/* Group: Administrator/Sponsor Functions */
	/*------------------------------------------------------------------------------------------------*/
	
	/* add_sponsor_manager()
	 *	parameters:
	 *		$username_manager = NetID or ChemID of user to be made a manager
	 *		$username_sponsor = NetID or ChemID of sponsor
	 *		$username_user = NetID or ChemID of user creating the relationship
	 * adds the given user as a sponsor manager
	 * if add is successful, returns true
	 * if add fails, return false
	 * if sponsor-manager association already exists, return the create_user_id of the creator
	 */
	function add_sponsor_manager($username_manager, $username_sponsor, $username_user)
	{
		global $MYSQL_connection;
		$manager_id=get_user_id($username_manager);
		$sponsor_id=get_user_id($username_sponsor);
		$create_user_id=get_user_id($username_user);
		$table_name="Sponsor_Manager_MAP";
		$column_list=array("sponsor_id", "manager_id");
		$value_list=array($sponsor_id, $manager_id);
		if(get_exists($table_name, $column_list, $value_list))
		{
			$result=mysqli_query($MYSQL_connection,"SELECT create_user_id FROM Sponsor_Manager_MAP WHERE (sponsor_id=$sponsor_id AND manager_id=$manager_id);");
			$creator_id=mysqli_fetch_array($result);
			return $creator_id['create_user_id'];
		}
		else
		{
			return mysqli_query($MYSQL_connection,"INSERT INTO Sponsor_Manager_MAP (sponsor_id, manager_id, create_date, create_user_id) VALUES ($sponsor_id, $manager_id, (SELECT NOW()), $create_user_id");
		}
		return false;
	}
	
	/* remove_sponsor_manager()
	 *	parameters:
	 *		$username_manager = NetID or ChemID of user to be made a manager
	 *		$username_sponsor = NetID or ChemID of sponsor
	 * removes the association between the manager and the sponsor
	 * if remove is successful, returns true
	 * if remove fails, return false
	 * if sponsor-manager association does not exist, returns true
	 */
	function remove_sponsor_manager($username_manager, $username_sponsor)
	{
		global $MYSQL_connection;
		$manager_id=get_user_id($username_manager);
		$sponsor_id=get_user_id($username_sponsor);
		$table_name="Sponsor_Manager_MAP";
		$column_list=array("sponsor_id", "manager_id");
		$value_list=array($username_sponsor, $username_manager);
		if(get_exists($table_name, $column_list, $value_list))
		{
			return mysqli_query($MYSQL_connection,"DELETE FROM $table_name WHERE (sponsor_id=$sponsor_id AND manager_id=$manager_id);");
		}
		return true;
	}
	
	/*------------------------------------------------------------------------------------------------*/
	/* Group: Administrator Functions */
	/*------------------------------------------------------------------------------------------------*/
	
	/* adjust_research_description()
	 *	parameters:
	 *		$username = NetID or ChemID of user
	 *		$desc_status = string defining action ("create", "modify", "approve", "deny", "expire", "comment")
	 *		$research_description = text of the research description
	 *		$admin_comment = comment on description made by an administrator
	 * function for administrators to modify or approve research descriptions
	 * comment function allows administrator to leave reasons for denial or modification
	 * if modify is successful, returns true
	 * if modify fails, returns false
	 * on attempt to create existing research description, returns create_date of research description
	 */
	function adjust_research_description($username_user, $username_admin, $desc_status, $research_description, $admin_comment)
	{
		global $MYSQL_connection;
		$user_id=get_user_id($username_user);
		$admin_id=get_user_id($username_admin);
		$table_name="User_Research_Description";
		$column_list=array("user_id");
		$value_list=array($user_id);
		if($desc_status == "create")
		{
			if(get_exists($table_name, $column_list, $value_list))
			{
				$result=mysqli_query($MYSQL_connection,"SELECT create_date FROM $table_name WHERE user_id=$user_id;");
				$create_date=mysqli_fetch_array($result);
				return $create_date['create_date'];
			}
			return update_research_description($username_user, $research_description);
		}
		else if($desc_status == "modify")
		{
			if(get_exists($table_name, $column_list, $value_list))
			{
				return update_research_description($username_user, $research_description);
			}
			return adjust_research_description($username_user, $username_admin, "create", $research_description, $admin_comment);
		}
		else if($desc_status == "approve")
		{
			if(get_exists($table_name, $column_list, $value_list))
			{
				return mysqli_query($MYSQL_connection, "UPDATE User_Research_Description SET status_id=" . ACTIVE . ", reviewer_id=$admin_id, review_date=(SELECT NOW()) WHERE user_id=$user_id");
			}
			else
			{
				return false;
			}
		}
		else if($desc_status == "deny")
		{
			if(get_exists($table_name, $column_list, $value_list))
			{
				return mysqli_query($MYSQL_connection, "UPDATE User_Research_Description SET status_id=" . DENIED . ", reviewer_id=$admin_id, review_date=(SELECT NOW()) WHERE user_id=$user_id");
			}
			else
			{
				return false;
			}
		}
		else if($desc_status == "expire")
		{
			if(get_exists($table_name, $column_list, $value_list))
			{
				return mysqli_query($MYSQL_connection, "UPDATE User_Research_Description SET status_id=" . EXPIRED . ", reviewer_id=$admin_id, review_date=(SELECT NOW()) WHERE user_id=$user_id");
			}
			else
			{
				return false;
			}
		}
		else if($desc_status == "comment")
		{
		}
	}
	
	/* create_research_description()
	 * passes through to adjust_research_description with "create" desc_status
	 * requires a research description to be input
	 * if change is successful, returns true
	 * if change fails, returns false
	 */
	function create_research_description($username_user, $username_admin, $research_description)
	{
		return adjust_research_description($username_user, $username_admin, "create", $research_description, "");
	}
	
	/* modify_research_description()
	 * passes through to adjust_research_description with "modify" desc_status
	 * requires a research description to be input
	 * if change is successful, returns true
	 * if change fails, returns false
	 */
	function modify_research_description($username_user, $username_admin, $research_description)
	{
		return adjust_research_description($username_user, $username_admin, "modify", $research_description, "");
	}
	
	/* approve_research_description()
	 * passes through to adjust_research_description with "approve" desc_status
	 * if change is successful, returns true
	 * if change fails, returns false
	 */
	function approve_research_descripton($username_user, $username_admin)
	{
		return adjust_research_description($username_user, $username_admin, "approve", "", "");
	}
	
	/* deny_research_description()
	 * passes through to adjust_research_description with "deny" desc_status
	 * if change is successful, returns true
	 * if change fails, returns false
	 */
	function deny_research_description($username_user, $username_admin)
	{
		return adjust_research_description($username_user, $username_admin, "deny", "", "");
	}
	
	/* expire_research_description()
	 * passes through to adjust_research_description with "expire" desc_status
	 * if change is successful, returns true
	 * if change fails, returns false
	 */
	function expire_research_description($username_user, $username_admin)
	{
		return adjust_research_description($username_user, $username_admin, "expire", "", "");
	}
	
	/* comment_research_description()
	 * passes through to adjust_research_description with "comment" desc_status
	 * requires a comment to be input
	 * if change is successful, returns true
	 * if change fails, returns false
	 */
	function comment_research_description($username_user, $username_admin, $comment)
	{
		return adjust_research_description($username_user, $username_admin, "comment", "", $comment); 
	}
	
	/* add_app_setting()
	 *	parameters:
	 *		$settingname = string giving the name of the setting
	 *		$settingdesc = string giving a description of the setting
	 *		$settingval_num = integer value of the setting for settings that take numbers
	 *		$settingval_text = string value of the setting for settings that take text
	 * adds a new setting to the 'App_Settings' table
	 * on success, returns true
	 * on fail, returns false
	 */
	function add_app_setting($settingname, $settingdesc, $settingval_num, $settingval_text)
	{
		global $MYSQL_connection;
		return mysqli_query($MYSQL_connection,"INSERT INTO App_Settings (app_settings_name, app_settings_desc, app_setting_value_numerical, app_setting_value_text) VALUES ($settingname, $settingdesc, $settingval_num, $settingval_text);");
	}
	
	/* add_numerical_app_setting()
	 * passes through to add_app_setting() with settingval_text as null
	 * on success, returns true
	 * on failure, returns false
	 */
	function add_numerical_app_setting($settingname, $settingdesc, $settingval_num)
	{
		return add_app_setting($settingname, $settingdesc, $settingval_num, null);
	}
	
	/* add_text_app_setting()
	 * passes through to add_app_setting() with settingval_num as null
	 * on success, returns true
	 * on failure, returns false
	 */
	function add_text_app_setting($settingname, $settingdesc, $settingval_text)
	{
		return add_app_setting($settingname, $settingdesc, null, $settingval_text);
	}
	
	/* adjust_app_setting()
	 *	parameters:
	 *		$settingname = string giving the name of the setting
	 *		$settingval_num = integer value of the setting for settings that take numbers
	 *		$settingval_text = string value of the setting for settings that take text
	 * changes the given setting to the provided parameters
	 * on success, returns true
	 * on failure, returns false
	 */
	function adjust_app_setting($settingname, $settingval_num, $settingval_text)
	{
		global $MYSQL_connection;
		$setting_id=get_setting_id($settingname);
		return mysqli_query($MYSQL_connection,"UPDATE App_Settings SET app_setting_value_numerical=$settingval_num, app_setting_value_text=$settingval_text WHERE app_settings_id=$setting_id;");
	}
	
	/* adjust_numerical_app_setting()
	 * passes through to adjust_app_setting() with $settingval_text as null
	 * on success, returns true
	 * on failure, returns false
	 */
	function adjust_numerical_app_setting($settingname, $settingval_num)
	{
		return adjust_app_setting($settingname, $settingval_num, null);
	}
	
	/* adjust_text_app_setting()
	 * passes through to adjust_app_setting() with $settingval_num as null
	 * on success, returns true
	 * on failure, returns false
	 */
	function adjust_text_app_setting()
	{
		return adjust_app_setting($settingname, null, $settingval_text);
	}
	
	/* get_hosts()
	 *	parameters:
	 *		$username = NetID or ChemID of user
	 * finds and returns array of hostnames the given user has requested access to
	 * if no hosts are associated with user, returns null
	 */
	function get_hosts($username)
	{
		global $MYSQL_connection;
		$user_id=get_user_id($username);
		$table_name="User_Host_MAP";
		$column_list=array("user_id");
		$value_list=array($user_id);
		if(get_exists($table_name, $column_list, $value_list))
		{
			$result=mysqli_query($MYSQL_connection,"SELECT host_id FROM User_Host_MAP WHERE user_id=$user_id");
			$hosts=array();
			$i=0;
			while($row=mysqli_fetch_array($result))
			{
				$host_id=$row['host_id'];
				$hosts[$i]=get_hostname($host_id);
				++$i;
			}
			return $hosts;
		}
		return null;
	}
	
	/* add_host()
	 *	parameters:
	 *		$hostname = plaintext name of the host
	 *		$department_code = shortcode of the department associated with the host (eg. 'CHEM', 'AERO')
	 *		$host_desc = description of the host (optional)
	 * adds a host with the given name, description, and department to the list of hosts
	 * on success, returns true
	 * on failure, returns false
	 * if a host with the given name already exists, returns the host_id of that host
	 */
	function add_host($hostname, $department_code, $host_desc=null)
	{
		global $MYSQL_connection;
		$table_name="Hosts";
		$column_list=array("hostname");
		$value_list=array($hostname);
		if(get_exists($table_name, $column_list, $value_list))
		{
			return get_host_id($hostname);
		}
		$department_id=get_department_id_from_code($department_code);
		return mysqli_query($MYSQL_connection,"INSERT INTO $table_name (hostname, department_id, host_description) VALUES ($hostname, $department_id, $host_desc);");
	}
	
	/* remove_host()
	 *	parameters:
	 *		$hostname = plaintext name of host
	 * removes a host from the list of available hosts
	 * also removes all entries on the 'User_Host_MAP' database table referencing the given host
	 * on success, returns true
	 * on failure, returns false
	 * if no host exists with given name, returns true
	 * if removal of 'User_Host_MAP' entries fails, returns false
	 */
	function remove_host($hostname)
	{
		global $MYSQL_connection;
		$host_id=get_host_id($hostname);
		if(remove_host_mappings($hostname))
		{
			return mysqli_query($MYSQL_connection,"DELETE FROM Hosts WHERE host_id=$host_id;");
		}
		return false;
	}
	
	/* adjust_host_access()
	 *	parameters:
	 *		$username = NetID or ChemID of user
	 *		$hostname = plaintext name of host
	 *		$host_status = string defining action ("approve", "deny", "revoke")
	 * adjusts user's host access status
	 * on success, returns true
	 * on failure, returns false
	 * if user has no mapping to the given host, returns null
	 */
	function adjust_host_access($username, $hostname, $host_status)
	{
		global $MYSQL_connection;
		$user_id=get_user_id($username);
		$host_id=get_host_id($host_id);
		$table_name="User_Host_MAP";
		$column_list=array("user_id", "host_id");
		$value_list=array($user_id, $host_id);
		
		if(!get_exists($table_name, $column_list, $value_list))
		{
			return null;
		}
		
		if($host_status == "approve")
		{
			return mysqli_query($MYSQL_connection,"UPDATE User_Host_MAP SET status_id=" . ACTIVE . " WHERE (user_id=$user_id AND host_id=$host_id);");
		}
		else if($host_status == "deny" || $host_status == "revoke")
		{
			return mysqli_query($MYSQL_connection,"UPDATE User_Host_MAP SET status_id=" . DENIED . " WHERE (user_id=$user_id AND host_id=$host_id);");
		}
		return false;
	}
	
	/* approve_host_access()
	 * passes through to adjust_host_access() with $host_status set to "approve"
	 * on success, returns true
	 * on fail, returns false
	 * if no mapping exists between the given user and host, returns null
	 */
	function approve_host_access($username, $hostname)
	{
		return adjust_host_access($username, $hostname, "approve");
	}
	
	/* deny_host_access()
	 * passes through to adjust_host_access() with $host_status set to "deny"
	 * on success, returns true
	 * on fail, returns false
	 * if no mapping exists between the given user and host, returns null
	 */
	function deny_host_access($username, $hostname)
	{
		return adjust_host_access($username, $hostname, "deny");
	}
	
	/* revoke_host_access()
	 * passes through to adjust_host_access() with $host_status set to "revoke"
	 * on success, returns true
	 * on fail, returns false
	 * if no mapping exists between the given user and host, returns null
	 */
	function revoke_host_access($username, $hostname)
	{
		return adjust_host_access($username, $hostname, "revoke");
	}
	
	/* add_group_manager()
	 *	parameters:
	 *		$username = NetID or ChemID of user
	 *		$groupname = plaintext name of group
	 *		$username_mod = NetID or ChemID of user submitting change
	 * adds a user/group mapping to the 'User_Group_Admin_MAP' database table
	 * on success, returns true
	 * on failure, returns false
	 * if mapping already exists, returns true but does not log the assign_user_id or assign_date
	 */
	function add_group_manager($username, $groupname, $username_mod)
	{
		global $MYSQL_connection;
		$user_id=get_user_id($username);
		$group_id=get_group_id($groupname);
		$assign_user_id=get_user_id($username_mod);
		$table_name="User_Group_Admin_MAP";
		$column_list=array("user_id", "group_id");
		$value_list=array($user_id, $group_id);
		if(get_exists($table_name, $column_list, $value_list))
		{
			return true;
		}
		return mysqli_query($MYSQL_connection,"INSERT INTO $table_name (user_id, group_id, assign_date, assign_user_id) VALUES ($user_id, $group_id, (SELECT NOW()), $assign_user_id);");
	}
	
	/* remove_group_manager()
	 *	parameters:
	 *		$username = NetID or ChemID of user
	 *		$groupname = plaintext name of group
	 * removes a user/group mapping from the 'User_Group_Admin_MAP' database table
	 * on success, returns true
	 * on failure, returns false
	 * if mapping does not exist, returns true
	 */
	function remove_group_manager($username, $groupname)
	{
		global $MYSQL_connection;
		$user_id=get_user_id($username);
		$group_id=get_group_id($groupname);
		$table_name="User_Group_Admin_MAP";
		$column_list=array("user_id", "group_id");
		$value_list=array($user_id, $group_id);
		if(!get_exists($table_name, $column_list, $value_list))
		{
			return true;
		}
		return mysqli_query($MYSQL_connection,"DELETE FROM $table_name WHERE (user_id=$user_id AND group_id=$group_id);");
	}
	
	/* get_manager_sponsors()
	 *	parameters:
	 *		$username_manager = NetID or ChemID of a sponsor manager
	 * finds all sponsors for whom the given manager is authorized
	 * returns an array with the NetID's or ChemID's of the sponsors
	 * if the user does not exist as a manger for any sponsors, returns null
	 */
	function get_manager_sponsors($username_manager)
	{
		global $MYSQL_connection;
		$manager_id=get_user_id($username_manager);
		$table_name="Sponsor_Manager_MAP";
		$column_list=array("manager_id");
		$value_list=array($manager_id);
		if(get_exists($table_name, $column_list, $value_list))
		{
			$result=mysqli_query($MYSQL_connection,"SELECT sponsor_id FROM $table_name WHERE manager_id=$manager_id;");
			$sponsors=array();
			$i=0;
			while($row=mysqli_fetch_array($result))
			{
				$sponsors[$i]=get_username($row['sponsor_id']);
				++$i;
			}
			return $sponsors;
		}
		return null;
	}
	
	/* get_sponsored_managers()
	 *	parameters:
	 *		$username_sponsor = NetID or ChemID of a sponsor
	 * finds all managers authorized by the given sponsor
	 * returns an array with the NetID's or ChemID's of the managers
	 * if the user does not exist as a sponsor for any managers, returns null
	 */
	function get_sponsored_managers($username_sponsor)
	{
		global $MYSQL_connection;
		$sponsor_id=get_user_id($username_sponsor);
		$table_name="Sponsor_Manager_MAP";
		$column_list=array("sponsor_id");
		$value_list=array($sponsor_id);
		if(get_exists($table_name, $column_list, $value_list))
		{
			$result=mysqli_query($MYSQL_connection,"SELECT manager_id FROM $table_name WHERE sponsor_id=$sponsor_id;");
			$managers=array();
			$i=0;
			while($row=mysqli_fetch_array($result))
			{
				$managers[$i]=get_username($row['manager_id']);
				++$i;
			}
			return $managers;
		}
		return null;
	}
	
	/*------------------------------------------------------------------------------------------------*/
	/* Group: Group Manager Functions */
	/*------------------------------------------------------------------------------------------------*/
	
	/* modify_group_access()
	 *	parameters:
	 *		$username = NetID or ChemID of user
	 *		$groupname = plaintext name of group
	 *		$username_mod = NetID or ChemID of user submitting the request
	 *		$access_status = string defining action ("approve", "deny", "renew", "revoke", "comment")
	 *		$comment = string containing an administrator comment
	 * modifies the access of the given user/group mapping
	 * on success, returns true
	 * on failure, returns false
	 * if no mapping exists between the given user and group, returns null
	 */
	function modify_group_access($username, $groupname, $username_mod, $access_status, $comment, $expire_date)
	{
		global $MYSQL_connection;
		$user_id=get_user_id($username);
		$group_id=get_group_id($groupname);
		$modify_user_id=get_user_id($username_mod);
		$table_name="User_Group_MAP";
		$column_list=array("user_id", "group_id");
		$value_list=array($user_id, $group_id);
		
		if(!get_exists($table_name, $column_list, $value_list))
		{
			return null;
		}
		
		if($access_status == "approve" || $access_status == "renew")
		{
			return mysqli_query($MYSQL_connection,"UPDATE $table_name SET modify_user_id=$modify_user_id, status_id=" . ACTIVE . ", modify_date=(SELECT NOW()), expire_date=$expire_date WHERE (user_id=$user_id AND group_id=$group_id);");
		}
		else if($access_status == "deny" || $access_status == "revoke")
		{
			return mysqli_query($MYSQL_connection,"UPDATE $table_name SET modify_user_id=$modify_user_id, status_id=" . DENIED . ", modify_date=(SELECT NOW()), expire_date=(SELECT NOW()) WHERE (user_id=$user_id AND group_id=$group_id);");
		}
		else if($access_status == "comment")
		{
			return mysqli_query($MYSQL_connection,"UPDATE $table_name SET modify_user_id=$modify_user_id, modify_date=(SELECT NOW()), comment=$comment WHERE (user_id=$user_id AND group_id=$group_id);");
		}
		return false;
	}
	
	/* approve_group_access()
	 * passes through to modify_group_access() with $access_status as "approve"
	 * on success, returns true
	 * on failure, returns false
	 * if no mapping exists between the given user and group, returns null
	 */
	function approve_group_access($username, $groupname, $username_mod, $expire_date)
	{
		return modify_group_access($username, $groupname, $username_mod, "approve", "", $expire_date);
	}
	
	/* deny_group_access()
	 * passes through to modify_group_access() with $access_status as "deny"
	 * on success, returns true
	 * on failure, returns false
	 * if no mapping exists between the given user and group, returns null
	 */
	function deny_group_access($username, $groupname, $username_mod)
	{
		return modify_group_access($username, $groupname, $username_mod, "deny", "", null);
	}
	
	/* renew_group_access()
	 * passes through to modify_group_access() with $access_status as "renew"
	 * on success, returns true
	 * on failure, returns false
	 * if no mapping exists between the given user and group, returns null
	 */
	function renew_group_access($username, $groupname, $username_mod, $expire_date)
	{
		return modify_group_access($username, $groupname, $username_mod, "renew", "", $expire_date);
	}
	
	/* revoke_group_access()
	 * passes through to modify_group_access() with $access_status as "revoke"
	 * on success, returns true
	 * on failure, returns false
	 * if no mapping exists between the given user and group, returns null
	 */
	function revoke_group_access($username, $groupname, $username_mod)
	{
		return modify_group_access($username, $groupname, $username_mod, "revoke", "", null);
	}
	
	/* comment_group_access()
	 * passes through to modify_group_access() with $access_status as "comment"
	 * on success, returns true
	 * on failure, returns false
	 * if no mapping exists between the given user and group, returns null
	 */
	function comment_group_access($username, $groupname, $username_mod, $comment)
	{
		return modify_group_access($username, $groupname, $username_mod, "comment", "", null);
	}
	
	/* get_users_in_group()
	 *	parameters:
	 *		$groupname = plaintext name of group
	 * returns an array of the NetID's or ChemIDs of all users with associations for the given group
	 * if group has no users, returns null
	 */
	function get_users_in_group($groupname)
	{
		global $MYSQL_connection;
		$group_id=get_group_id($groupname);
		$table_name="User_Group_MAP";
		$column_list=array("group_id");
		$value_list=array($group_id);
		
		if(!get_exists($table_name, $column_list, $value_list))
		{
			return null;
		}
		
		$result=mysqli_query($MYSQL_connection,"SELECT user_id FROM $table_name WHERE group_id=$group_id;");
		$users=array();
		$i=0;
		while($row=mysqli_fetch_array($result))
		{
			$user_id=$row['user_id'];
			$users[$i]=get_username($user_id);
			++$i;
		}
		return $users;
	}
	
	/* get_groups_for_user()
	 *	parameters:
	 *		$username = NetID or ChemID for user
	 * returns an array of the group names with associations to the given user
	 * if user has no groups, returns false
	 */
	function get_groups_for_user($username)
	{
		global $MYSQL_connection;
		$user_id=get_group_id($username);
		$table_name="User_Group_MAP";
		$column_list=array("user_id");
		$value_list=array($user_id);
		
		if(!get_exists($table_name, $column_list, $value_list))
		{
			return null;
		}
		
		$result=mysqli_query($MYSQL_connection,"SELECT group_id FROM $table_name WHERE user_id=$user_id;");
		$groups=array();
		$i=0;
		while($row=mysqli_fetch_array($result))
		{
			$group_id=$row['group_id'];
			$groups[$i]=get_groupname($group_id);
			++$i;
		}
		return $groups;
	}
?>
<!--End Repository-->
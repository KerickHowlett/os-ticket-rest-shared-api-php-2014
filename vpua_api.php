<?php

//Originally Programmed By: Kerick A. Howlett
//Original Date & Time of Completion: 8:15PM EST Thursday, December 4, 2014

//Update Notes:
//No Updates have been made as of yet.

//List of Functions used for the various methods of the API.

function con() //Function to quickly establish connection.
{
    if(!file_exists("ost_con.php")) //Is the 'ost_con.php' file there?
        die(http_response_code(404) . $result = __LINE__);
    
    //OSTicket Database Connection Configurations are saved on a separate file for security reasons.	
    include 'ost_con.php';	

    return $con; //Returns the essential information needed to establish connection to database for all SQL
                    //queries. 
}		
		
function arrayFilter($var) //To establish desired parameters for an array filter that omits empty values EXCEPT ZERO VALUES!
{
    return ($var !== NULL && $var !== '');
}
		
function dataArray() //Function to establish the entire array as a whole.
{
    //The "TRUE" is needed in order to turn the JSON information from the other file and covert it back into an array.	
    $var = json_decode(file_get_contents("php://input"), TRUE);
                                                                
    if($_GET["method"] == 'create_ticket') //Error Handling for the "Create_Ticket" Method.
        {
            while (list($dk,$dv) = each($var))
                {
                    $name = $var['name'];
                    
                    $subject = $var['subject'];
                    
                    $address = $var['address'];
                    
                    if($dk === 'ip')
                        {
                            if(empty($dv))
                                die(http_response_code(428) . $result = __LINE__); //Is the IP address empty?
                        }
                    else 
                        {
                            if(empty($dv))
                                die(http_response_code(406) . $result = __LINE__); //Are any of the critical fields empty?
                        }	
                }
                
            if(strlen($name) > 128) //Is the name more than 128 characters?
                die(http_response_code(413) . $result = __LINE__);
                
            if(strlen($address) > 128) //Is the e-mail address more than 128 characters?
                die(http_response_code(414) . $result = __LINE__);
                
            if(strlen($subject) > 255) //Is the subject header more than 128 characters?
                die(http_response_code(417) . $result = __LINE__);	
        }

    if($_GET["method"] == 'update_ticket') //Error Handling for the "Update_Ticket" Method.
        {
            $ip = $var['ip_address'];
            
            $time = $var['updated'];
            
            $number = $var['number'];
            
            $name = $var['name'];
            
            $subject = $var['subject'];
            
            $address = $var['address'];
            
            if(empty($ip) || empty($time))
                die(http_response_code(428) . $result = __LINE__); //Is there an IP address and/or date/time?
                
            if(empty($number)) //Is there a Ticket Number?
                die(http_response_code(406) . $result = __LINE__); 
            elseif(!is_numeric($number)) //Are there any letters in it?
                die(http_response_code(415) . $result = __LINE__);
            elseif(strlen($number) > 20) //Is it less than 20 characters?
                die(http_response_code(415) . $result = __LINE__);
                
            if(strlen($name) > 128) //Is the name more than 128 characters?
                die(http_response_code(413) . $result = __LINE__);
                
            if(strlen($address) > 128) //Is the e-mail address more than 128 characters?
                die(http_response_code(414) . $result = __LINE__);
                
            if(strlen($subject) > 255) //Is the subject header more than 128 characters?
                die(http_response_code(417) . $result = __LINE__);						
        }

    if($_GET["method"] == 'ticket_status') //Error Handling for the "Ticket_Status" Method.
        {
            $number = $var['number'];
            
            if(empty($number)) //Is there a Ticket Number?
                die(http_response_code(406) . $result = __LINE__);
            elseif(!is_numeric($number)) //Are there any letters in it?
                die(http_response_code(415) . $result = __LINE__);
            elseif(strlen($number) > 20) //Is it less than 20 characters?
                die(http_response_code(415) . $result = __LINE__);
        }	

    if($_GET["method"] == 'ticket_assignment') //Error Handling for the "Ticket_Assignment" Method.
        {
            $ip = $var['ip_address'];
            
            $time = $var['time'];
            
            $admin = $var['admin'];
            
            $username = $var['username'];
            
            $number = $var['number'];
            
            $body = $var['body'];
            
            if(empty($ip) || empty($time))
                die(http_response_code(428) . $result = __LINE__); //Is there an IP address and/or date/time?
                
            if(empty($number)) //Is there a Ticket Number?
                die(http_response_code(406) . $result = __LINE__); 
            elseif(!is_numeric($number)) //Are there any letters in it?
                die(http_response_code(415) . $result = __LINE__);
            elseif(strlen($number) > 20) //Is it less than 20 characters?
                die(http_response_code(415) . $result = __LINE__);
            
            if(empty($body)) //Is there any type of comment/note? Important Note: OSTicket UI says it's mandatory.
                die(http_response_code(416) . $result = __LINE__);
            
            if(empty($admin) Xor strlen($admin) > 32) //Is there an ADMIN Username? If so, is it longer than 32 characters?
                die(http_response_code(413) . $result = __LINE__);
                
            if(empty($username)) //Is there a STAFF Username?
                die(http_response_code(407) . $result = __LINE__);
        }

    if($_GET["method"] == 'lookup_user') //Error Handling for the "Lookup_User" Method.
        {
            $con = con(); //Establish connection.
        
            $username = $var['username']; //Pulls the entered Username belonging to the new Staff/Admin User.
            
            if(empty($username)) //Was there a Username entered?
                die(http_response_code(406) . $result = __LINE__); 
            if(strlen($username) > 32) //Was the Username longer than 32 characters?
                die(http_response_code(414) . $result = __LINE__);
        }
        
    if($_GET["method"] == 'create_user') //Error Handling for the "Create_User" Method.
        {
            $con = con(); //Establish connection.
        
            $username = $var['username']; //Pulls the entered Username belonging to the new Staff/Admin User.
            
            $created = $var['created']; //Pulls the Date & Time of when the new Staff/Admin User was created.
            
            $admin = $var['admin']; //Pulls the Username belonging to the Admin of whom is creating this new Staff/Admin User.
            
            $isadmin = $var['isadmin']; //Pulls the binary setting regarding Access Privileges of when the new Staff/Admin User was created.
            
            if(empty($created)) //Is there a Date & Time Record?
                die(http_response_code(428) . $result = __LINE__);
            
            if(empty($username)) //Was there a Username entered?
                die(http_response_code(406) . $result = __LINE__); 
            if(strlen($username) > 32) //Was the Username longer than 32 characters?
                die(http_response_code(414) . $result = __LINE__);
                    
            if(empty($admin) Xor strlen($admin) > 32) //Is there an ADMIN Username? If so, is it longer than 32 characters?
                die(http_response_code(413) . $result = __LINE__);
            
            // Was there anything wrong with setting the User Privileges setting?					
            switch($isadmin) {
                case 0: //Staff Privileges.
                    break; //Does nothing.
                case 1: //Admin Privileges.
                    break; //Does nothing.
                case FALSE: //Is $isadmin empty/NULL?
                    die(http_response_code(416) . $result = __LINE__);
                default: //If it's anything but 1 or 0, or even if it's empty.
                    die(http_response_code(416) . $result = __LINE__);
            }
        }
    
    if($_GET["method"] == 'update_user') //Error Handling for the "Update_User" Method.
        {
            $con = con(); //Establish connection.
            
            $username = $var['username']; //Pulls the entered Username.
            
            $updated = $var['updated']; //Pulls the automatically entered 
                                        //Date & Time of when the User's 
                                        //information is being Updated. 
            
            $firstname = $var['firstname']; //Pulls the entered First Name.
            
            $lastname = $var['lastname']; //Pulls the entered Last Name.
            
            $email = $var['email']; //Pulls the entered E-Mail Address.
            
            $phone = $var['phone']; //Pulls the entered Main/Primary/Office
                                    //Phone Number.
            
            //Pulls the entered Extension Number to the Main/Primary/Office Phone Number.
            //It is also all caps just in case somebody types in "NULL".
            $phone_ext = strtoupper($var['phone_ext']); 
            
            $mobile = $var['mobile']; //Pulls the entered Mobile/Cell Phone Number.
            
            if(empty($username)) //Was there a Username entered?
                die(http_response_code(406) . $result = __LINE__); 
            if(strlen($username) > 32) //Was the Username longer than 32 characters?
                die(http_response_code(414) . $result = __LINE__);
                
            if(empty($updated)) //Is there a Date & Time Record?
                die(http_response_code(428) . $result = __LINE__);
            
            //This error handling protocol only needs to take effect if there's actually
            //something entered in the "firstname" or "lastname" fields.					
            if(isset($firstname) || isset($lastname))
                {
                    //Was the First/Last Name longer than 32 characters?
                    if(strlen($username) > 32 || strlen($username) > 32)
                        die(http_response_code(418) . $result = __LINE__);
                }
                
            //This error handling protocol only needs to take effect if there's actually
            //something entered in the "email" field.					
            if(isset($email))
                {
                    //Was the E-Mail Address longer than 128 characters?
                    if(strlen($email) > 128)
                        die(http_response_code(418) . $result = __LINE__);
                }
                
            //This error handling protocol only needs to take effect if there's actually
            //something entered in the "phone" or "phone_ext" fields.					
            if(isset($phone) || isset($mobile))
                {
                    //Was the Main/Primary/Office/Mobile/Cell Phone Number longer than
                    //24 characters?
                    if(strlen($phone) > 24 || strlen($mobile) > 24)
                        die(http_response_code(418) . $result = __LINE__);
                }
                
            //This error handling protocol only needs to take effect if there's actually
            //something entered in the "phone_ext" field.					
            if(isset($phone_ext))
                {
                    //Was the Extension Number for their Main/Primary/Office Phone Number
                    //longer than 6 characters?
                    if(strlen($phone_ext) > 6)
                        die(http_response_code(418) . $result = __LINE__);
                }
        }
    
    //Removing any records where the values are left NULL from the overall array.
    $array = array_filter($var, "arrayFilter"); 
        
    return $array; //Returning the post-filtered array.
}			

function updateCountArray() //To ensure that the Users enters at least one field of data other than just the Ticket Number.
{
    $array = dataArray(); //Pulls the Array from the dataArray() function and assigns it to a variable for 
                            //later error handling.

    // Two of the fields that need to be filled-out regardless, obviously, are the "Ticket Number"/"Username" and the 
    //"Updated" fields. Though there should also be, at the very least, one more field filled up in order to update at
    //at least one field/record - otherwise, what's the point?
    if(count($array) === 3)
        die(http_response_code(405) . $result = __LINE__);
}
		
function data($field) //Function to pull out the desired data.
{
    $array = dataArray(); // Pulls the post-filtered array from the dataArray() function. 

    //Confirms that the ticket number exists within the OSTicket database for only the desired/appropiate methods.
    if($_GET["method"] == 'ticket_update' Xor $_GET["method"] == 'ticket_status' Xor $_GET["method"] == 'ticket_assignment') 
        {
            $con = con(); //Establishes connection to database.
        
            $number = $array['number']; //Pulling entered Ticket Number from the array.
                
            //Executing the query that will search for a matching Ticket Number within the ost_ticket database table.  
            $test = mysqli_fetch_assoc(mysqli_query($con,"SELECT  number
                                                            FROM  ost_ticket
                                                            WHERE  number = $number
                                                            LIMIT  1"));
                                                                
            if(!$test) //Could not find the Ticket Number?
                die(http_response_code(400) . $result = __LINE__);
        }	

    $data = $array[$field]; //To pull out the specific data which is desired by it's particular variable name,
                            //which are listed within the $data array of either the 'status.php' or 'update.php'
                            //files.
                            
    if($data == FALSE && $data != 0) //Could not find the data for the desired field? [Setup to where it can accept values of zero(0).]
        die(http_response_code(409) . $result = $field . "-" . __LINE__);
            
    return $data; //Returns the desired data. 
}	

function userLookup() //Function used for looking-up any possibly existing users that are stored within the OSTicket Database.
{
    $con = con(); //Establish connection.
    
    $key = 'username'; //Index Key for pulling value from data array, as well as name of database column header for SQL WHERE clause. 
    
    $value = data($key); //Pulling up the e-mail address or username to search for within the OSTicket Database.
    
    //Executing query in order to see whether or not the username or e-mail address currently exists within the 
    //OSTicket Database.
    $ost = mysqli_fetch_assoc(mysqli_query($con, "SELECT *
                                                    FROM ost_staff
                                                    WHERE $key = '$value'"));
    
    if($_GET["method"] == 'update_user' && $ost == FALSE) //Error Handling for the "Update_User" Method.
        die(http_response_code(406) . $result = __LINE__); //Couldn't find the Staff/Admin User in question by their Username.
        
    return $ost; //Returning boolean results for later methods. 
}
		
function singleUser() //Function for ensuring that there is never more than ONE(1) Staff/Admin User associated with any given e-mail address.
{
    $ost = userLookup(); //Pulling up boolean results from the userLookup() function.
    
    //Executing the following protocols based on whether it finds a matching e-mail address or not. 
    switch($ost) {
        case TRUE: //Kills the program and returns the appropriate error message.
            $line = __LINE__ + 3; //Acquiring accurate line number of where the program was killed for better error handling.
            //Error Message without the usual method of sending back a "http_response_code", as the system will constantly
            //keep overwriting the response code with that of a "500 - Internal Error" response code for some unknown reason.
            die("<b>418-" . $line . ":</b>This user already exists. If you find this message an error, please contact
                your system administrator.");
            break; 
        case FALSE: //Does nothing, and continues on with creating the new Staff/Admin User.
            break;
    }
}
		
function LDAP() //Function to establish connection to LDAP system, and pull appropriate data.
{
    if(!file_exists("ldap_config.php")) //Is the 'ldap_config.php' file there?
        die(http_response_code(404) . $result = __LINE__);

    include 'ldap_config.php'; //LDAP Configurations are saved on a separate file for security reasons.
    
    $key = 'username'; //Data Array Index Key.
    
    $ldap_key = data($key); //The Key that will uniquely identify the desired employee within the LDAP System.

    $ldap_attr = 'cn'; //LDAP Attribute to look under for desired entry. 
                        //The LDAP Attribute for an employee's Username in the system is "cn".
                        //The LDAP Attribute for an employee's E-Mail Address in the system is "mail".
    
    $ldapcon = ldap_connect($server); // Establishing connection to the LDAP system. 
    
    if(!$ldapcon) //LDAP Error handling.
        {
            $error = ldap_error($ldapcon); //Pulling up the LDAP specific error code. 
            
            $pos = __LINE__ + 4; //Accurate line number of where the program was killed. 
            
            $line = substr($pos, 3); //Trimming away extra unneeded numbers. 
        
            die(http_response_code(445) . $result = "$error-$line" ); 
        }
    
    $bind = ldap_bind($ldapcon, $ldapuser, $pass); //Accessing desired LDAP through appropriate database path and authentication.

    if(!$bind) //LDAP Error handling.
        {
            $error = ldap_error($ldapcon); //Pulling up the LDAP specific error code. 
            
            $pos = __LINE__ + 4; //Accurate line number of where the program was killed. 
            
            $line = substr($pos, 3); //Trimming away extra unneeded numbers. 
        
            die(http_response_code(445) . $result = "$error-$line" ); 
        }
    
    $search = ldap_search($ldapcon, $tree, "($ldap_attr = $ldap_key)"); //Looking-up the employee with desired LDAP Attribute & Key.

    if(!$search) //LDAP Error handling.
        {
            $error = ldap_error($ldapcon); //Pulling up the LDAP specific error code. 
            
            $pos = __LINE__ + 4; //Accurate line number of where the program was killed. 
            
            $line = substr($pos, 3); //Trimming away extra unneeded numbers. 
        
            die(http_response_code(445) . $result = "$error-$line" ); 
        }
        
    $entry = ldap_get_entries($ldapcon, $search); //Assigning the record into an Array Variable. 
    
    if(!$entry) //LDAP Error handling.
        {
            $error = ldap_error($ldapcon); //Pulling up the LDAP specific error code. 
            
            $pos = __LINE__ + 4; //Accurate line number of where the program was killed. 
            
            $line = substr($pos, 3); //Trimming away extra unneeded numbers. 
        
            die(http_response_code(445) . $result = "$error-$line" ); //Error code. 
        }
    
    for ($i=0; $i < $entry["count"]; $i++) //Extracting the needed information. 
        {
            //The Username used for OSTicket will be the same as what they used for other various systems. 
            $username = $entry[$i]["cn"][0];
            
            //The Last Name of the User for the OSTicket Database
            $lastname = $entry[$i]["sn"][0];
            
            //The First Name and Middle Initial of the User in the database, as that is how the LDAP database
            //is setuped. 
            $givenname = $entry[$i]["givenname"][0];
            
            //The e-mail address again, as to make data storage process a bit simpler. 
            $email = $entry[$i]["mail"][0];	
        }	

    //Error handling in the event that the entered e-mail address is not found in the LDAP system.
    if(!isset($username) || !isset($lastname) || !isset($givenname) || !isset($email))
        die(http_response_code(415) . $result = __LINE__);
        
    //To determine the number of where the space is between the Employee's First Name and his/her middle initial.
    $pos = strpos($givenname, ' ');
    
    //Extracting JUST the Employee's First Name for the OSTicket Database.
    $firstname = substr($givenname, 0, $pos);		
    
    $LDAPArray = Array(); //Creating the array to store the information that was just extracted. 
    
    $LDAPArray['username'] = $username; //Storing the Username into the LDAPArray.
    
    $LDAPArray['firstname'] = $firstname; //Storing the First Name into the LDAPArray.
    
    $LDAPArray['lastname'] = $lastname; //Storing the Last Name into the LDAPArray.
    
    $LDAPArray['email'] = $email; //Storing the E-Mail Address into the LDAPArray.
    
    ldap_close($ldapcon); //Closing LDAP connection.
    
    return $LDAPArray; //Returning the now fully created array that's now containing all the needed data. 
}
				
function dateCheck() //Function to make sure that date & time associated with the updated information proceeds that of what was there previously.		
{
    $con = con(); //Establish connection to the OSTicket Database. 

    if ($_GET["method"] === 'update_ticket') // Error Handling for the "Update_Ticket" method.
        {
            $number = data('number'); //Pulling Ticket Number from Data Array.
            
            $time = data('updated'); //Pulling the date & time of this update. 
            
            //Executing the SQL Query to find the ticket in question.
            $check = mysqli_fetch_assoc(mysqli_query($con,"SELECT  *
                                                                FROM  ost_ticket
                                                            WHERE  number = $number
                                                            LIMIT  1"));

            if(!$check) //SQL Query Error Handling.
                die(http_response_code(409) . $result = __LINE__);
                
            $pre_date = $check['updated']; //Assigns variable with 'updated' value.
            
            if($pre_date = '0000-00-00 00:00:00') //If it's '0000-00-00 00:00:00'(empty), than assign variable with 'created' value.
                $pre_date = $check["created"];
            
            //Was the created/previous-updated date & time recorded at the exact same moment or after this current/latest date & time? 
            //Or in other words: Do the words, "Does Not Compute!", come to mind?
            if($pre_date >= $time)
                die(http_response_code(428) . $result = __LINE__);
            
        }
        
    if ($_GET["method"] === 'ticket_assignment')  // Error Handling for the "Ticket_Assignment" method.
        {
            $time = data('time'); //Pulling the date & time of this Ticket Assignment. 
            
            $ticket_id = ticketNumber(); //Pulling the Ticket ID Number using the ticketNumber() function.
            
            //Executing the SQL Query to look and see if the ticket is currently assigned to someone or not.
            $check = mysqli_fetch_assoc(mysqli_query($con,"SELECT  *
                                                                FROM  ost_ticket_thread
                                                            WHERE  ticket_id = $ticket_id
                                                                AND  staff_id != 0
                                                                AND  user_id = 0
                                                                AND  thread_type = 'N'
                                                                AND  title LIKE '%Ticket Assigned%'
                                                                AND  poster NOT LIKE 'SYSTEM'
                                                            LIMIT  1"));
            
            if($check): //Was it able to find any record of the ticket already being assigned?
                {
                    $pre_date = $check['updated']; //Assigns variable with 'updated' value.
                    
                    if($pre_date = '0000-00-00 00:00:00') //If it's '0000-00-00 00:00:00'(empty), than assign variable with 'created' value.
                        $pre_date = $check["created"];
                    
                    //Was the created/previous-updated date & time recorded at the exact same moment or after this current/latest date & time? 
                    //Or in other words: Do the words, "Does Not Compute!", come to mind?
                    if($pre_date >= $time)
                        die(http_response_code(428) . $result = __LINE__);							
                }
            endif; //If it doesn't find a Ticket that's already assigned; than obviously, it doesn't need to do anything else
                    //here, and can continue on with the rest of the method.
        }	

    if ($_GET["method"] === 'update_user')  // Error Handling for the "Update_User" method.
        {
            $staff_record = userLookup(); //Pulls-up record pertaining to the Admin/Staff User in question into an Array.
            
            $updated = data('updated'); //Pulling the date & time that the information pertaining to this Staff/Admin User was
                                        //attempted at being Updated. 
        
            $pre_date = $staff_record['updated']; //Assigns variable with 'updated' value.
            
            if($pre_date = '0000-00-00 00:00:00') //If it's '0000-00-00 00:00:00'(empty), than assign variable with 'created' value.
                $pre_date = $staff_record["created"];
                
            //Was the created/previous-updated date & time recorded at the exact same moment or after this current/latest date & time? 
            //Or in other words: Do the words, "Does Not Compute!", come to mind?
            if($pre_date >= $updated)
                die(http_response_code(428) . $result = __LINE__);
        }
}
		
function _APIKey() //Function to confirm API Key.
{
        if(isset($_SERVER['HTTP_X_API_KEY']))
        {
            $con = con(); //Establishing connection with the database.
            
            $api = $_SERVER['HTTP_X_API_KEY'];
                    
            $key = mysqli_fetch_assoc(mysqli_query($con, "SELECT apikey
                                                            FROM ost_api_key
                                                            WHERE apikey = '$api'
                                                            LIMIT 1"));
                                                                
            if(!$key)												 
                die(http_response_code(204) . $result = __LINE__);
                
            return $api; //To bounce the API Key forward to the other API when executing the "Create Ticket" protocol.
        }
    else
            die(http_response_code(214) . $result = __LINE__);
}

function staff_id() //To acquire the Staff Member's staff_id number.
{
    $con = con(); //Establishing connection with the database.
    
    $username = data('username');
    
    $var = mysqli_fetch_assoc(mysqli_query($con,"SELECT *
                                                    FROM ost_staff
                                                    WHERE username = '$username'
                                                    LIMIT 1"));
                                                    
    if(!$var)
        {
                //Error handling.
                die(http_response_code(408) . $result = __LINE__);
        }
        else
        {
            $isactive = $var['isactive']; //Is the account Staff Member's account currently active?
            
            if($isactive === 0)
                die(http_response_code(412) . $result = __LINE__);
        
                //Pulling staff_id from the $var array.
                $staff_id = $var['staff_id'];

                //Closing connection to the database. 
                mysqli_close($con);
                
                //Returns the $ticket_id for output.
                return $staff_id;	
        }
}

function staff_name($staff_id) //To acquire the Staff Member's full-name.
{
    $con = con(); //Establishing connection with the database.
    
    if ($_GET["method"] === 'ticket_status') //To look up a staff member by their internal staff_id number.
        {
            $key = 'staff_id';
            
            $value = $staff_id;
        }
    elseif ($_GET["method"] === 'ticket_assignment') //To look up a staff member by their Username.
        {
            $username = data('username');
            
            $key = 'username';
            
            $value = $username;
        }
        
    if($value == 0) //In case there is no staff member assigned to the ticket.
        {
            $fullname = "Not Yet Assigned";
            
            return $fullname;
        }
    else
        {
            $var = mysqli_fetch_assoc(mysqli_query($con,"SELECT *
                                                            FROM ost_staff
                                                            WHERE $key = '$value'
                                                            LIMIT 1"));
                                                            
            if(!$var) //Error handling.
                die(http_response_code(408) . $result = __LINE__); 

            //Pulling the staff member's FIRST name from the $var array.
            $firstname = $var['firstname'];

            //Pulling the staff member's LAST name from the $var array.
            $lastname = $var['lastname'];


            //Concatenating the staff member's first and last name to create his or her FULL name.
            $fullname = $firstname . ' ' . $lastname;

            //Closing connection to the database. 
            mysqli_close($con);

            //Returns the $ticket_id for output.
            return $fullname;	
        }
}

function adminVerification($admin)	//Verification of Admin Privileges when necessary.
{
    $con = con(); //Establishing connection with the database.
    
    $var = mysqli_fetch_assoc(mysqli_query($con,"SELECT *
                                                    FROM ost_staff
                                                    WHERE username = '$admin'
                                                    LIMIT 1"));
                                                    
    if(!$var)
        {
            //Error handling.
            die(http_response_code(413) . $result = __LINE__);
        }
        else
        {
            //This part is to test to see if whether or not the User has proper authorization/is active or not. 
            $isadmin = $var['isadmin'];
            
            if($isadmin === 0)
                die(http_response_code(411) . $result = __LINE__);
                
            $isactive = $var['isactive'];
            
            if($isactive === 0)
                die(http_response_code(412) . $result = __LINE__);
        }
}
		
function admin_name() //To acquire admin's name and to test for proper authorization.
{
    $con = con(); //Establishing connection with the database.
    
    $admin = data('admin');
    
    adminVerification($admin); //Validating that this is an authorized admin making the changes.
    
    $var = mysqli_fetch_assoc(mysqli_query($con,"SELECT *
                                                    FROM ost_staff
                                                    WHERE username = '$admin'
                                                    LIMIT 1"));
            
            //Pulling the staff member's FIRST name from the $var array.
            $firstname = $var['firstname'];

            //Pulling the staff member's LAST name from the $var array.
            $lastname = $var['lastname'];
                
                
            //Concatenating the staff member's first and last name to create his or her FULL name.
            $fullname = $firstname . ' ' . $lastname;
                
            //Closing connection to the database. 
            mysqli_close($con);
                
            //Returns the $ticket_id for output.
            return $fullname;	
}
		
function ticketNumber() //Function to convert the external Ticket Number into the internal ticket_id.
{
    $con = con(); //Establishing connection with the database.

    $number = data('number');
    
    //Retrieving Ticket Information by it's External Ticket Number.
    $var = mysqli_fetch_assoc(mysqli_query($con,"SELECT *
                                                FROM ost_ticket
                                                WHERE number = $number
                                                LIMIT 1"));
    
    if(!$var)
    {
        //Error handling.
        die(http_response_code(400) . $result = __LINE__);
    }
    else
    {
        //Pulling ticket_id from the $var array.
        $ticket = $var['ticket_id'];

        //Closing connection to the database. 
        mysqli_close($con);
        
        //Returns the $ticket_id for output.
        return $ticket;	
    }
}

function userID() //Function to acquire proper user_id.
{
    $con = con(); //Establishing connection with the database.

    $number = data('number');

    //Retrieving Ticket Information by it's External Ticket Number.
    $var = mysqli_fetch_assoc(mysqli_query($con,"SELECT  *
                                                    FROM  ost_ticket
                                                    WHERE  number = $number
                                                    LIMIT  1"));

    if(!$var)
        {
            //Error handling.
            die(http_response_code(409) . $result = __LINE__);	 
        }
    else
        {	
        //Pulling user_id from the $var array.
        $user_id = $var['user_id'];				   

        //Closing connection to the database. 
        mysqli_close($con);

        //Returns the $user_id for output.
        return $user_id; 
    }
}
		
function entryID($form_id) //Function to acquire proper entry_id in order to find the desired information in the ost_form_entry_values table.
{	
        $con = con(); //Establishing connection with the database.
    
        $user_id = userID();  //Pulling Internal User_ID from userID() function.
        
        $ticket = ticketNumber();  //Pulling internal Ticket_ID from ticketNumber() function.											
                                                    
        if ($form_id == 1)
            {
                $form = $form_id;
                $id = $user_id;
                $type = 'U';
            }
            
        elseif($form_id == 2)
            {
                $form = $form_id;
                $id = $ticket;
                $type = 'T';
            }
        
        $var = mysqli_fetch_assoc(mysqli_query($con,"SELECT  *
                                                    FROM  ost_form_entry
                                                    WHERE  object_id = $id
                                                        AND  form_id = $form 
                                                        AND  object_type = '$type'
                                                    LIMIT  1"));	
    
    if(!$var) // Error handling.
        die(http_response_code(409) . $result = __LINE__);

    //Pulling entry_id from the $var2 array.
    $entry_id = $var['id']; 

    //Closing connection to the database. 
    mysqli_close($con);
        
    //Returns the $entry_id for output.
    return $entry_id;

}

function defaultEMail() //Function to acquire the End User's "default_email_id" based on user_id.
{
    $con = con(); //Establishing connection with the database.
        
    $user_id = userID();  //Pulling Internal User_ID from userID() function.
        
    $var = mysqli_fetch_assoc(mysqli_query($con,"SELECT *
                                                    FROM ost_user
                                                    WHERE id = $user_id
                                                    LIMIT 1"));
    
    if(!$var)
        {
            //Error handling.
            die(http_response_code(409) . $result = __LINE__);
        }
    else
        {
            $email_id = $var['default_email_id'];	

            //Closing connection to the database. 
            mysqli_close($con);		 

            return $email_id;
        }
}
		
_APIKey(); //Executing API Key security function.

//Series of Methods used to execute the plethora of functions of the API.
	
//Method of the API for when the End User wishes to create a new ticket.
//Important Note: This Method primarily just bounces the processed data back-and-forth between this API and OSTicket's
//pre-existing API that actually creates the tickets.	
if ($_GET["method"] === 'create_ticket') 
{
    $key = _APIKey();
    
    // If 1, display things to debug.
    $debug="0";

    $config = array(
            'url'	=>	'http://127.0.0.1/osticket/api/http.php/tickets.json',  // URL 
            'key'	=>	$key  // API Key 
    );

    $data = dataArray();

    if($debug=='1') {
        print_r($data);
        die();
    }

    #pre-checks
    function_exists('curl_version') or die('CURL support required');
    function_exists('json_encode') or die('JSON support required');

    #set timeout
    set_time_limit(30);

    #curl post
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $config['url']);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_USERAGENT, 'osTicket API Client v1.8');
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array( 'Expect:', 'X-API-Key: '.$config['key']));
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $result=curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($code === 201)
        {	
            echo "Your ticket was successfully created.<br><br>Your Ticket Number is: <b>$result</b>";
            http_response_code(201);
        }
    else
        die(http_response_code($code) . $result = __LINE__);

        
    $ticket_id = (int) $result;

    function IsNullOrEmptyString($question){
        return (!isset($question) || trim($question)==='');
    }
}
//Protocol of the API for when the End User wishes to check on a ticket's current status.
elseif ($_GET["method"] === 'ticket_status') 
	{
		$con = con(); //Establishing quick connection.

		$number = data('number'); //Pulling external Ticket Number.
		
		$user_id = userID(); //Pulling internal UserID Number.
		
		$email_id = defaultEMail(); //Pulling up internal Default Email ID.
		
		$ticket_id = ticketNumber(); //Pulling up internal Ticket_ID Number. 
		
		//Looking up current data within the ost_ticket table by its associated Ticket Number. 
		$ticket_table = mysqli_fetch_assoc(mysqli_query($con,"SELECT *
																FROM ost_ticket
															   WHERE number = $number
															   LIMIT 1"));
											   
		if(!$ticket_table) //Error Handling.
			 die(http_response_code(409) . $result = __LINE__);
			 
		//Pulling up the status from the $ticket_table array.
		$status = $ticket_table['status'];	

		//Pulling up the time & date the ticket was initially OPEN from the $ticket_table array.
		$time = $ticket_table['created'];

		if($status == 'closed') //To change the time & date to the of when it was closed. 
			{
				unset($time); //Removing the time & date of when it was originally opened.
				
				$time = $ticket_table['closed'];
			}
		
		//Pulling up the internal staff_id number from the $ticket_table array.
		$staff_id = $ticket_table['staff_id'];
		
		//Pulling up the staff member's name by their internal staff_id number.
		$staff_name = staff_name($staff_id);
		
		if($staff_id !== 0) //To see if the ticket is assigned to someone or not. 
			{
				//Pulling up the date & time the staff member was assigned the ticket. 
				$assigned_query = mysqli_fetch_assoc(mysqli_query($con,"SELECT *
																		  FROM ost_ticket_thread
																		 WHERE ticket_id = $ticket_id
																		   AND staff_id = $staff_id
																		   AND title LIKE '%Ticket Assigned%'
																		   AND thread_type = 'N'
																		   AND format = 'html'
																		 LIMIT 1"));
																		 
				if(!$assigned_query) //Error Handling.
					die(http_response_code(409) . $result = __LINE__);
					
				$assigned_time = $assigned_query['created']; //Establishes the initial time & date of assignment.
				
				//To acquire the appropiate time & date if this ticket had ever been re-assigned.
				if($assigned_time === '0000-00-00 00:00:00')
					{
						unset($assigned_time); //Removing the 'created' time & date.
						$assigned_time = $assigned_query['updated']; //Re-assigning the 'updated' time & date.
					}
			}
																
		//Pulling Ticket Holder Name
		$user = mysqli_fetch_assoc(mysqli_query($con,"SELECT name
													    FROM ost_user
													   WHERE id = $user_id
													   LIMIT 1"));
													   
		if(!$user) //Error Handling.
			 die(http_response_code(409) . $result = __LINE__);
			 
		//Pulling Ticket Holder Name from the $user array.
		$name = $user['name'];				 
			 
		//Pulling up User Email Address
		$email = mysqli_fetch_assoc(mysqli_query($con,"SELECT address
													     FROM ost_user_email
													    WHERE id = $email_id
													    LIMIT 1"));
														
		if(!$email) //Error Handling.
			 die(http_response_code(409) . $result = __LINE__);	
			 
		//Pulling email address from the $email array.
		$address = $email['address'];				 

		//Pulling information from the ost_ticket_thread data table - namely the ticket body and title.
		$thread = mysqli_fetch_assoc(mysqli_query($con,"SELECT *
													      FROM ost_ticket_thread
													     WHERE ticket_id = $ticket_id
													       AND user_id = $user_id
														   AND thread_type = 'M'
														   AND format = 'text'
													     LIMIT 1"));
													   
		if(!$thread) //Error Handling.
			 die(http_response_code(409) . $result = __LINE__);
			 
		//Pulling ticket body message from the $body array.
		$body = $thread['body'];		 	

		//Pulling ticket's subject header from the $title array.
		$title = $thread['title'];	

		//The following few lines are part of a dynamic While-Loop to pull information from the ost_form_entry_values table.
		
		//This is the query that will acquire the fields needed for not just acquiring the specific information needed
		//for the output, but also for the dynamic loop itself.
		$customDataArray = array(); //Creating a new array to store the custom fields' VALUES so that they may be stored in an array for printout.
		
		//This query will pull up the appropiate field_id number for each custom field, as well as the phone number, for a later query.
		$form_field = mysqli_query($con, "   SELECT *
											   FROM ost_form_field
											  WHERE (form_id = 2
													 AND name NOT IN ('subject','message','priority'))
												 OR (form_id = 1
													 AND name = 'phone')
										   ORDER BY form_id, label");
		
		while($row = mysqli_fetch_array($form_field)) //The loop that processes through each "$row" of the array.
			{
				$field_id = $row['id']; //Stores the field_id number of each result.
				
				//Stores the label of each result to be used as respective index keys for the arrays that
				//the final results will be later stored in, and to also serve as a label for printout.
				$label = $row['label'];
				
				//This will be used for the entryID() function in order to find the entry_id that will be needed
				//for the later query.
				$form_id = $row['form_id']; 
				
				$entry_id = entryID($form_id); //To pull the entry_id for the later query.
				
				//This query will search for the desired data using the $entry_id and $field_id variables that were
				//previously acquired.
				$value = mysqli_fetch_assoc(mysqli_query($con, "SELECT value
																  FROM ost_form_entry_values
																 WHERE entry_id = $entry_id
																   AND field_id = $field_id"));
																   
				if(!$value) //In case there is nothing entered in one of the custom fields for whatever reason.
					$field = "<i>N/A</i>";
				else
					$field = $value['value'];
				
				$customDataArray[$label] = $field; //This will store the desired information into an array.
			}
		
			//Print out of Ticket Information and status.
			echo "<h2>Ticket Number: $number is currently <u>" . strtoupper($status) . "</u>!</h2>"
			   . "<h4>This ticket has been <b>$status</b> since <b>$time</b>.<br>"
			   . "Currently assigned to <b>$staff_name</b> as of <b>$assigned_time</b>.</h4><hr>"
			   . "<h3>Ticket Information:</h3>"
			   . "<b>Name:</b> $name<br>"
			   . "<b>E-Mail Address:</b> $address<br>"
			   . "<b>Subject:</b> $title<br>"
			   . "<b>Message:</b> $body<br>";
			   
			//This loop will print out the remaining information, such as phone number and all other Custom Fields.
			foreach($customDataArray as $label => $value)
				echo "<b>$label :</b> $value<br>";
				
			echo "<br><hr>"; //Just places a line at the bottom of the display to make it look cleaner.
		
		mysqli_close($con); //Closing connection.
	}
	
//Protocol of the API for when the End User wishes to update/alter ticket information.	
elseif ($_GET["method"] === 'update_ticket') 
    {	
		dateCheck(); //To make sure that the date & time does not compromise the data's integrity by entering a 
					 //date & time that precedes either the date & time of the previous update or the date & time
					 //of the Ticket's initial creation.

		updateCountArray(); //To ensure that the Users enters at least one field of data other than just the Ticket Number.

		//Assigns internal ID numbers to variables based off the internal Ticket Number pulled from the $data array
		//that is currently hard coded in the 'update.php' file.		 
		$ticket_id = ticketNumber(); //Internal ticket_id number.
									  
		$user_id = userID(); //Internal user_id number.

		$email_id = defaultEMail(); //Internal user default_email_id number for email address.

		$array = dataArray(); //To assign the filtered data array as a usable variable.

		$number = data('number'); //Ticket Number for print out of confirmation.
			
		$con = con(); //Establishing connection with the OSTicket database.
		
 		while (list($dk,$dv) = each($array)) //Loop of updating queries.
			{
				//This section is for the fields that each have their own unique paths starting from the 
				//ticket number located within the ost_ticket table and cannot use the same query to be simply
				//looped repeatedly as needed.
				if ($dk === 'number' Xor $dk === 'name' Xor $dk === 'address' Xor $dk === 'body' Xor $dk === 'updated' Xor
					$dk === 'ip_address')
					{	
						//This updates the full name of the user, as well as establishes a date of most recent
						//update as well as the IP address of the person who made it in the ost_ticket table.
						if ($dk === 'name' Xor $dk === 'updated')
							{
								$sql = "UPDATE ost_user
										   SET $dk = '$dv'
										 WHERE id = $user_id";
										 
								
							}
							
						//This updates the default email address of the user.	
						if ($dk === 'address')
							{
								$sql = "UPDATE ost_user_email
										   SET $dk = '$dv'
										 WHERE id = $email_id";
							}
							
						//This updates the main body (or the problem details) of the ticket, as well as  
						//establishes a date of most recent update as well as the IP address of the person 
						//who made it in the ost_ticket table.
						if ($dk === 'body' Xor $dk === 'updated' Xor $dk === 'ip_address')
							{
								$sql = "UPDATE ost_ticket_thread
										   SET $dk = '$dv'
										 WHERE ticket_id = $ticket_id	 
										   AND thread_type = 'M'
										   AND user_id = $user_id
										   AND format = 'text'";
							}

						//Establishes a date of most recent update as well as the IP address of the person
						//who made it in the ost_ticket table.
						if ($dk === 'updated' Xor $dk === 'ip_address')
							{
								$sql = "UPDATE ost_ticket
										   SET $dk = '$dv'
										 WHERE number = $number";
										 
								if($dk === 'updated') //Updating the date & time of the update in the "ost_users" table.
									{
										$sql2 = "UPDATE ost_user
												    SET $dk = '$dv'
												  WHERE id = $user_id";
									}
							}
					}
				
				//This is a query that can be used repeatedly for all custom fields that each share a common path 
				//from the ticket number stored in the ost_ticket table, and can actually be looped repeatedly as
				//as many times as is needed.
				else
					{
						//Query to help gather information needed to find the right specific field to make the 
						//update to.
						$id_query =  mysqli_fetch_assoc(mysqli_query($con, "SELECT *
																			  FROM ost_form_field
																			 WHERE name = '$dk'
																			 LIMIT 1"));
						
						if(!$id_query)
							die(http_response_code(409) . $result = __LINE__);

						//These are to help locate the specific field to 
						//update within the ost_form_entry_values table.
						$field_id = $id_query['id'];
						
						$form_id = $id_query['form_id'];
						
						$entry_id = entryID($form_id);
						/////////////////////////////////////////////////
						
						$sql =  "UPDATE ost_form_entry_values
									SET value = '$dv'
								  WHERE entry_id = $entry_id
									AND field_id = $field_id
								  LIMIT 1";
					}
				
				//Actual execution of all queries.
				if ($dk !== 'number')
					{			
						$ut_query = mysqli_query($con, $sql);
						
						//Executing the query for the Subject Title of the ticket.
						if ($dk === 'subject' Xor $dk === 'updated')
							{
								if($dk === 'subject') //Update message body title.
									{
										$sql2 = "UPDATE ost_ticket_thread
												    SET $dk = '$dv'
												  WHERE id = $ticket_id";
									}
							
							
								$ut_query2 = mysqli_query($con, $sql2);
								
								if(!$ut_query)
									die(http_response_code(409) . $result = __LINE__);
							}
						//Error handling code for in case the query either runs into a syntax error or just simply couldn't find
						//the database field in question.
						if(!$ut_query)
							die(http_response_code(409) . $result = __LINE__);
					}
			}
				
		mysqli_close($con); //Closing connection to database.
			
		//Confirmation that the End User's desired ticket was successfully updated.	
		echo "<h3>Ticket Number: <i>$number</i> has been successfully updated!</h3>"; 
	}

//Protocol of the API for when the End User wishes to either Assign or Re-Assign a given ticket to a specific Staff Member.
elseif ($_GET["method"] === 'ticket_assignment')
	{
		//To make sure that the date & time does not compromise the data's integrity by entering a 
		//date & time that precedes either the date & time of the previous update or the date & time
		//of the Ticket's initial creation.
		dateCheck();
		 
		//Assigns internal ID numbers to variables based off the internal Ticket Number pulled from the $data array
		//that is currently hard coded in the 'update.php' file.		 
		$con = con(); //Establishing connection with the database.
		
		$ticket_id = ticketNumber(); //Internal ticket_id number.
		 
		$staff_id = staff_id(); //Internal staff_id number.
		 
		$staff_name = staff_name($staff_id); //To pull the staff member's full-name from the database by their staff_id.
		
		$admin_name = admin_name(); //To pull the Admin's/Poster's full-name from the database.
		
		$array = dataArray(); //To assign the filtered data array as a usable variable.
		
		$time = data('time'); //The date & time of when the Ticket Assignment was made.
		
		//This is to test and see if it can find the ticket assignment. If it can, that means this is a 
		//TICKET RE-ASSIGNMENT; if not, this will create the TICKET ASSIGNMENT.
		$check = mysqli_fetch_assoc(mysqli_query($con,"SELECT  staff_id
														 FROM  ost_ticket
														WHERE  ticket_id = $ticket_id
														LIMIT  1"));
														
		//Error handling code for in case the query either runs into a syntax error or just simply couldn't find
		//the database field in question.
		if(!$check)
			die(http_response_code(409) . $result = __LINE__);
		
		//To find out whether or not there is a staff ID associated with the ticket.
		//When staff_id is zero(0), it means that there is no one assigned to handling the ticket.
		$staff_check = $check['staff_id'];
		
		if($staff_check == 0) //Assign Ticket to Staff Member ("Create").
			{
				$assignment = 'assigned'; //For confirmation output.
			
				$ip_address = data('ip_address'); //The IP Address of whomever made the Ticket Assignment.
				
				$note = data('body'); //In case, the Admin decided not to include a note with the Ticket Assignment.
				
				//Inserting the additional record into the ost_ticket_thread table.
				$sql = "INSERT INTO ost_ticket_thread
						(ticket_id, staff_id, user_id, thread_type, poster, source, title, body, format, ip_address, created, updated)
						VALUES
						($ticket_id, $staff_id, 0, 'N', '$admin_name', 'API','Ticket Assigned to $staff_name', '$note', 'html', '$ip_address', '$time', '0000-00-00 00:00:00')";
				
				$ta_query = mysqli_query($con, $sql); 
				
				//Error handling code for in case the query either runs into a syntax error or just simply couldn't find
				//the database field in question.
				if(!$ta_query)
					die(http_response_code(409) . $result = __LINE__);
			}  
			
										
		else //Ticket Re-Assignment ("Update").
			{
				$assignment = 're-assigned'; //For confirmation output.
				
				while (list($dk,$dv) = each($array)) //Loop of updating queries.
					{		
						$sql2 = 'empty'; //Just in case the Admin only wants to attach a note, and not re-assign the user.
						
						if($dk !== 'number') //To exclude the Ticket Number record of the array from the While Loop.
							{
								if($dk === 'username')
									{
										//The query that re-assigns the ticket.
										$sql = "UPDATE ost_ticket_thread
												   SET  staff_id = $staff_id
												 WHERE  ticket_id = $ticket_id
												   AND  staff_id != 0
												   AND  user_id = 0
												   AND  thread_type = 'N'
												   AND  title LIKE '%Ticket Assigned%'
												   AND  poster NOT LIKE 'SYSTEM'
												 LIMIT  1";
										
										//To post the name of who the ticket was re-assigned to in the title.
										$sql2 = "UPDATE ost_ticket_thread
													SET  title = 'Ticket Assigned to $staff_name'
												  WHERE  ticket_id = $ticket_id
													AND  staff_id != 0
													AND  user_id = 0
													AND  thread_type = 'N'
													AND  title LIKE '%Ticket Assigned%'
													AND  poster NOT LIKE 'SYSTEM'
												  LIMIT  1";
									}
									
								if($dk === 'body' Xor $dk === 'ip_address') //Changing date & time 
									{
										//To attach a note or change the IP address.
										$sql = "UPDATE ost_ticket_thread
												   SET  $dk = '$dv'
												 WHERE  ticket_id = $ticket_id
												   AND  staff_id = $staff_id
												   AND  user_id = 0
												   AND  thread_type = 'N'
												   AND  title LIKE '%Ticket Assigned to $staff_name%'
												   AND  poster NOT LIKE 'SYSTEM'
												 LIMIT  1";
									}	
									
								if($dk === 'time') //Establishing date & time of Re-Assignment
									{
										$sql = "UPDATE ost_ticket_thread
												   SET  updated = '$dv'
												 WHERE  ticket_id = $ticket_id
												   AND  staff_id = $staff_id
												   AND  user_id = 0
												   AND  thread_type = 'N'
												   AND  title LIKE '%Ticket Assigned to $staff_name%'
												   AND  poster NOT LIKE 'SYSTEM'
												 LIMIT  1";
									}
									
								if($dk === 'admin') //Establishing the name of whoever re-assigned the ticket. 
									{
										$sql = "UPDATE ost_ticket_thread
												   SET  poster = '$admin_name'
												 WHERE  ticket_id = $ticket_id
												   AND  staff_id = $staff_id
												   AND  user_id = 0
												   AND  thread_type = 'N'
												   AND  title LIKE '%Ticket Assigned to $staff_name%'
												   AND  poster NOT LIKE 'SYSTEM'
												 LIMIT  1";
									}
									
								$tra_query = mysqli_query($con, $sql);
								
								//Error handling code for in case the query either runs into a syntax error or just simply couldn't find
								//the database field in question.
								if(!$tra_query)
									die(http_response_code(409) . $result = __LINE__);
									
								//Executing the query for the Subject Title of the ticket.
								if($sql2 !== 'empty')
									{
										$tra_query2 = mysqli_query($con, $sql2);
										
										if(!$tra_query2)
											die(http_response_code(409) . $result = __LINE__);
									}
							}
					}
			}
			
		//This will update the primary ticket information, namely the Staff_ID number, as well as the date & time
		//that the Ticket (Re-)Assignment was made; so that it will appear in the OSTicket User-Interface by using 
		//this query below.
		$sql_ui = "UPDATE ost_ticket
					  SET staff_id = $staff_id, 
					       updated = '$time'
				    WHERE ticket_id = $ticket_id";	
		
		$ui_update	= mysqli_query($con, $sql_ui); //Execution of query.
		
		//Error handling code for in case the query either runs into a syntax error or just simply couldn't find
		//the database field in question.
		if(!$ui_update)
			die(http_response_code(409) . $result = __LINE__);
		
		mysqli_close($con); //Closing connection to database.
		
		$number = data('number'); //Ticket Number for Confirmation Output.
		
		echo "You have successfully $assignment Ticket Number: <b>$number</b> to <b>$staff_name</b>.";
	}

//Protocol of the API for when the End User wishes to lookup the information of a given Staff/Admin User based on the entered Username.
elseif ($_GET["method"] === 'lookup_user')
	{
		$staff = userLookup(); //Executes the primary query to see if the Staff/Admin User even exists or not. 
		
		//Two sets of scenarios to execute based on the Boolean results of whether the userLookup() function found anything or not.
		switch($staff){
			case TRUE: //If it SUCCEEDS in finding a matching Username.
				$username = $staff['username']; //Acquiring the Staff/Admin User's Username from the Database.
				
				$firstname = $staff['firstname']; //Acquiring the Staff/Admin User's First Name from the Database.
				
				$lastname = $staff['lastname']; //Acquiring the Staff/Admin User's Last Name from the Database.
				
				$email = $staff['email']; //Acquiring the Staff/Admin User's E-Mail Address from the Database.
				
				$priv = $staff['isadmin']; //Acquiring the Staff/Admin User's Access Level (Staff or Admin?) from the Database.
				
				//To later display the Staff/Admin User's Access Level.
				switch($priv) {
					case 0: //Staff User
						$access = "Staff";
						break;
					case 1: //Admin User
						$access = "Admin";
						break;
					default:
						$access = "Staff";
						break;
				}
				
				$phone = $staff['phone']; //Acquiring the Staff/Admin User's Phone Number from the Database.
				
				//Does this User have an existing Phone Number associated with their record?
				switch($phone) {
					case TRUE:
						$number = "$phone";
						break;
					case FALSE:
						$number = "<i>N/A</i>"; 
						break;
					default:
						$number = "<i>N/A</i>";
						break;
				}
				
				$phone_ext = $staff['phone_ext']; //Acquiring the Staff/Admin User's Phone Number Extension Address from the Database.
				
				//Does this User have an existing Phone Number Extension associated with their record?
				switch($phone_ext) {
					case TRUE:
						$ext = " <b>Ext:</b> $phone_ext";
						break;
					case FALSE:
						$ext = NULL; //Blank.
						break;
					default:
						$ext = NULL; //Blank.
						break;
				}
				
				$mobile = $staff['mobile']; //Acquiring the Staff/Admin User's Mobile Number from the Database.
				
				//Does this User have an existing Phone Number Extension associated with their record?
				switch($mobile) {
					case TRUE:
						$cell = "$mobile";
						break;
					case FALSE:
						$cell = "<i>N/A</i>"; 
						break;
					default:
						$cell = "<i>N/A</i>"; 
						break;
				}
				
				//Output Display.
				echo "<h2>User Information Acquired!</h2>
					  <hr>
					  <b>Username:</b> $username<br>
					  <b>Access Level:</b> $access<br>
					  <b>Name:</b> $firstname $lastname<br>
					  <b>E-Mail Address:</b> $email<br>
					  <b>Phone Number:</b> $number $ext<br>
					  <b>Mobile Number:</b> $cell<br>
					  <hr>
					  ";
				break;  
			case FALSE: //If it FAILS in finding a matching Username.
				$username = data('username'); //Pulls up the Username that was entered by the End User.
				
				echo "<h2>User Not Found!</h2>
					  <hr>
					  We're sorry, but we could not find <b>$username</b> in our database."; //Displaying response message.
				break;	
		}
	}
		
//Protocol of the API for when the End User wishes to create a Staff or Admin User for OSTicket using LDAP authentication.
//IMPORTANT NOTE: The OSTicket LDAP Plugin will need to be installed when implementing this API.
elseif ($_GET["method"] === 'create_user')
	{
		singleUser(); //Making sure that the e-mail address entered doesn't already exist within the OSTicket Database.
	
		$admin = data('admin'); //Pulling Admin Username for Verifying Admin Privileges. 
		
		adminVerification($admin); //Verifying Admin Privileges.
		
		$isadmin = data('isadmin'); //Whether the said created user will have either Staff or Admin Privileges.
		
		$created = data('created'); //The Time & Date of when the User was initially created.
		
		$LDAPArray = LDAP(); //Connection to LDAP Server and pulling all needed data into an Array. 
		
		$username = $LDAPArray['username']; //Pulling the Username from the Array.
		
		$firstname = $LDAPArray['firstname']; //Pulling the First Name from the Array.
		
		$lastname = $LDAPArray['lastname']; //Pulling the Last Name from the Array.
		
		$email = $LDAPArray['email']; //Pulling the E-Mail Address from the Array.
		
		$backend = 'ldap'; //Default setting for how the OSTicket System will authenticate Users. 
						   //Naturally, LDAP will be needed for VPUA's purposes.
		
		//The following are default settings needed for creating users with the OSTticket Database.
		
		//Important Note: When implementing this API, you will need to go into the database, 
		//and make it to where the phone and mobile numbers can be NULL!!!
		
		$department = 1;  //Associated DEPARTMENT of staff member, but since there is only one, this will be set as
						  //the Default value. 
						  
		$group = 1; // Associated GROUP of staff member, but since there is only one, this will be set as default.

		
		$assigned_only = 1; //Set to only show ONLY the tickets already assigned to them AKA Limted Access?
		
		$show_assigned_tickets = 0; //Set to only show ONLY the tickets already assigned to them AKA Limted Access?
		
		$daylight_savings = 1; //Set to automatically adjust for daylight savings time.
		
		$timezone_id = 8; //The Timezone is set to the default of EST.
		
		//The "commented-out" variables are there in case you should want the default value for your system
		//to be different from OSTicket's. If you want it different, just uncomment them back in and make sure
		//to include both their KEYS and VALUES, respectively. 
		
		// $invisible = 1; //Set to where he/she is visible in directory.
		
		// $isactive = 1; //Set to where the account is already active. 
		
		// $invocation = 0; //Set to where the user is not currently on vacation.
		
		// $signature = NULL; //The default signature for newly created users is just simply, "None". 
		
		// $default_signature_type = NULL; //Default signature for auto-emails.
		
		// $default_paper_size = NULL; //Default paper size of emails (I think).
		
		// $max_page_size = 25; //Default number of tickets that can show up on a given page.
		
		// $auto_refresh_rate = 0; //Setting for how often their page automatically refreshes. 
		
		// $change_passwd = NULL; //Shows the number of times that the User has changed his/her password, 
							      // which of course, should be zero(0).
		
		// $passwd = NULL; //THIS SHOULD ALWAYS REMAIN AS NULL/DEFAULT IF USING LDAP!!!
		
		// $passwdreset = NULL; //THIS SHOULD ALWAYS REMAIN AS NULL/DEFAULT SINCE THIS IS THE A BRAND NEW OSTICKET ACCOUNT!
		
		// $lastlogin = NULL; //THIS SHOULD ALWAYS REMAIN AS NULL/DEFAULT SINCE THIS IS THE A BRAND NEW OSTICKET ACCOUNT!
		
		// $updated = NULL; //THIS SHOULD NEVER CHANGE SINCE THIS IS THE A BRAND NEW OSTICKET ACCOUNT!
		
		$con = con(); //Establishing quick connection.
		
		//The INSERT SQL Query that will input the data into the OSTicket Database.
		$sql = "INSERT INTO ost_staff
				(group_id, dept_id, timezone_id, username, firstname, lastname, backend, email, isadmin, 
				 assigned_only, show_assigned_tickets, daylight_saving, created)
				VALUES
				 ($group, $department, $timezone_id, '$username', '$firstname', '$lastname', '$backend',
				  '$email', '$isadmin', '$assigned_only', '$show_assigned_tickets', '$daylight_savings',
				  '$created')";
				  
		$create_user = mysqli_query($con, $sql); //Executing the query.
		
		if(!$create_user) //Error Handling.
			die(http_response_code(409) . $result = __LINE__);
			 
		mysqli_close($con); //Closing database connection.
		
		echo "<h2>You have successfully created a Staff-User Account for OSTicket!</h2><hr>
			  $firstname $lastname can now login as <b>$username</b> with his current password.";
	}

//Protocol of the API for when the End User wishes to Update the information pertaining to a(n) Staff/Admin User Account.	
elseif ($_GET["method"] === 'update_user')
	{
		dateCheck(); //To make sure that the date & time does not compromise the data's integrity by entering a 
					 //date & time that precedes either the date & time of the previous update or the date & time
					 //of the Staff/Admin User' account's initial creation.

		updateCountArray(); //To ensure that the Users enters at least one field of data other than just the Ticket Number.
	
		$array = dataArray(); //To assign the filtered data array as a usable variable.
		
		$username = data('username'); //Entered Username to be used as the Primary Key for the later SQL WHERE clause.
		
		$con = con(); //Establishing connection with the OSTicket database.
		
		while (list($dk,$dv) = each($array)) //Loop of executing Update Queries.
			{
				if($dk !== 'username')
					{
						//In case a Phone Extension Number needs to be omitted after there is already one there.
						if($dk === 'phone_ext' && $dv === 'NULL')
							$dv = NULL; 
					
						//Update SQL Statement.
						$sql = "UPDATE ost_staff
								   SET $dk = '$dv'
								 WHERE username = '$username'";
					
						$update_query = mysqli_query($con, $sql); //Actual execution of Update query.
						
						if(!$update_query) //Error handling.
							die(http_response_code(409) . $result = __LINE__);	
					}
			}
			
		mysqli_close($con); //Closing connection to database.
			
		//Confirmation that the End User's desired ticket was successfully updated.	
		echo "<h3>Your Account Information was Successfully Updated!</h3>"; 
	}

//A "Safety Net" form of error handling for just in case the URL method does not equal one of the methods currently listed within the API.
else
	die(http_response_code(420) . $result = __LINE__);
	
?>
<?php
session_start();

if(isset($_POST['action']) && $_POST['action'] == 'register')
{
	foreach ($_POST as $name => $value) 
	{
		if(empty($value))
		{
			$_SESSION['error'][$name] = "sorry, " . $name . " cannot be blank";
		}
		else
		{
			switch ($name) {
				case 'first_name':
				case 'last_name':
					if(is_numeric($value))
					{
						$_SESSION['error'][$name] = $name . ' cannot contain numbers';
					}
				break;
				case 'email':
					if(!filter_var($value, FILTER_VALIDATE_EMAIL))
					{
						$_SESSION['error'][$name] = $name . ' is not a valid email';
					}
				break;
				case 'password':
					if(strlen($value) < 5)
					{
						$_SESSION['error'][$name] = $name . ' must be greater than 5 characters';
					}
				break;
				case 'birthdate':
					$birthdate = explode('/',$value);
					if(!checkdate($birthdate[0], $birthdate[1], $birthdate[2]))
					{
						$_SESSION['error'][$name] = $name . ' is not a valid date';
					}
				break;
			}
		}	
	}

	if($_FILES['file']['error'] > 0)
	{
		$_SESSION['error']['file'] = "Error on file upload Return Code: ".$_FILES['file']['error'];
	}
	else
	{
		$directory = 'upload/';
		$file_name = $_FILES['file']['name'];
		$file_path = $directory.$file_name;
		if(file_exists($file_path))
		{
			$_SESSION['error']['file'] = $file_name.' already exists'; 
		}
		else
		{
			if(!move_uploaded_file($_FILES['file']['tmp_name'], $file_path))
			{
				$_SESSION['error']['file'] = $file_name." could not be saved";
			}
		}
	}

	if(!isset($_SESSION['error']))
	{
		$_SESSION['success_message'] = "Congratulations you are now a member!";
	}
}

header('Location: index.php');

?>
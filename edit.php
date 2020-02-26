<?php
	require_once("inc/config.php");
	
	if (! isset($_SESSION["user_id"])) {   // Not logged in
		die(ERR_NO_ACCESS);
	}

	if ( isset($_POST["cancel"])) {        // User has clicked Cancel on form
		header("Location: index.php");
		exit;
	}

	if (! exists_in_db($pdo, "profile_id", "Profile", $_GET["profile_id"])) {
		$_SESSION["error"] = ERR_NO_PROFILE;
		header("Location: index.php");		
		exit;

	} else {

		// Grab the row and fields for the car to pre-populate the form. Also
		// Check to make sure the user has edit rights on this record.
        $stmt = $pdo->prepare("SELECT * FROM Profile where profile_id = :pid");
        $stmt->execute(array(":pid" => $_GET['profile_id']));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ( $row === false ) {
            $_SESSION['error'] = 'Bad value for Profile ID';
            header( 'Location: index.php' ) ;
            exit;
        }

        $fn = htmlentities($row['first_name']);
        $ln = htmlentities($row['last_name']);
        $em = htmlentities($row['email']);
        $he = htmlentities($row['headline']);
        $su = htmlentities($row['summary']);
        $ui = $row['user_id'];

        if ( $ui !== $_SESSION["user_id"]) {
        	$_SESSION['error'] = 'You do no have permission to edit this record.';
	    	header( 'Location: view.php?profile_id=' .  $_GET['profile_id']) ;
	    	exit;
        }
	}

	if ( isset($_POST["save"]) ) {  // Coming from form

		foreach ($_POST as $form_value) {  // Check all fields for empty strings
		
			if ($form_value == "") {
				$_SESSION["error"] = ERR_EMPTY_FIELDS;
				header("Location: edit.php");
				exit;
			}
		}

		if (! strrpos($_POST["email"], "@") ) { // Check for @ in email
			$_SESSION["error"] = ERR_EMAIL;
			header("Location: edit.php");
			exit;
		} 

		$stmt = $pdo->prepare('UPDATE Profile 
							   SET  first_name = :fn, 
							   		last_name = :ln, 
							   		email = :em, 
							   		headline = :he, 
							   		summary = :su
        					   WHERE profile_id = :pid');
    	$stmt->execute(array(
	        ':fn' => $_POST['first_name'],
	        ':ln' => $_POST['last_name'],
	        ':em' => $_POST['email'],
	        ':he' => $_POST['headline'],
	        ':su' => $_POST['summary'],
    	    ':pid' => $_POST['profile_id'])
    	);
    	
    	$_SESSION["success"] = "Record updated";
    	header("Location: index.php");
    	exit;
	}
	
	if (! isset($_GET["profile_id"])) { //  No profile ID is set on the URL (GET) (may fire on form subit)
	    $_SESSION["error"] = ERR_NO_PROFILE_ID;
	    header("Location: index.php");
	    exit;
	}
?>

<!DOCTYPE html>
<html lang='en'>
<head>
	<script type="text/javascript" src="inc/jsfunc.js"></script>
	<?php include("inc/header.php");?>
</head>
<body>
<div class="container" id="main-content">
	<h1> Adding Profile for <?= $_SESSION["name"] ?></h1>
	<!-- flash error -->
	<?php include("inc/flash.php"); ?>
	<form name="add_user" method="post" action="">
		<div class="form-group">
			<label for="txt_fname">First Name</label>
			<input type="text" name="first_name" id="txt_fname" class="form-control" value=<?= $fn ?>>

			<label for="txt_lname">Last Name</label>
			<input type="text" name="last_name" id="lname" class="form-control" value=<?= $ln ?>><br>
			
			<label for="txt_email">Email</label>
			<input type="text" name="email" id="txt_email" class="form-control" value=<?= $em ?>><br>

			<label for="txt_headline">Headline</label>
			<input type="text" name="headline" id="txt_head" class="form-control" value=<?= $he ?>> <br>
			
			<input type="hidden" name="profile_id" value=<?= $_GET["profile_id"] ?>>

			<label for="txt_fname">Summary</label>
			<textarea name="summary" id="txta_summary" rows="10" class="form-control"><?= $su ?></textarea><br>

			<input type="submit" name="save" value="Save" 
				   onclick='return validateAdd(["input", "textarea"]);' 
				   class="btn btn-primary">
			<input type="submit" name="cancel" value="Cancel" class="btn">
		</div>
	</form>

</div>

	<?php include("inc/footer.php");?>
</body>

</html>

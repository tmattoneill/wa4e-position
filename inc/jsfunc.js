function doValidate() {

	// validate password field
	console.log('Validating password...');
	try {
		pw = document.getElementById('pwd_pass').value;
		em = document.getElementById('txt_email').value;
		console.log("Validating em: " + em);

		// Field validation
		if (pw == null || pw == "" || 
			em == null || em == "") {
			alert("Both fields must be filled out");
			return false; // validation failed
		}

		// email formation check
		if ( em.search("@") < 0 ) {
			alert("Username must contain '@' in email");
			return false; // validation failed
		}

		return true; // validation passed		


	} catch(e) {
		return false; // try
	}
	return false; // function
}

function validateEmail(em) {

		// email formation check
		if ( em.search("@") < 0 ) {
			alert("Email address must contain '@'");
			return false; // validation failed
		}

		return true; // validation passed	

}

function validateAdd(arrTagNames) {
	// arrTagName is an array of one or more form tag names to validate against

	for (const tag of arrTagNames) {

		var fields = document.getElementsByTagName(tag);

		for (const field of fields) {
			if ( field.value == "" ) {
				alert ("All fields are required");
				return false;
			}
		}
	}

	if (! validateEmail(document.getElementById("txt_email").value)) {

		return false;
	}

	return true;

}
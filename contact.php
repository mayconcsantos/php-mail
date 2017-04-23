<?php
/*
	* Created by Maycon C Santos
	* www.mayconcsantos.com
*/

$vName		  = htmlspecialchars($_POST["cname"]);
$vEmail		  = htmlspecialchars($_POST["email"]);
$vPhone		  = htmlspecialchars($_POST["phone"]);
$vSubject	  = htmlspecialchars($_POST["subject"]);
$vInterest	= $_POST["interest"];
$vMessage	  = htmlspecialchars($_POST["message"]);
	
// Testing empty fields and validating email

if (empty($vName) OR empty($vEmail) OR empty($vPhone) OR empty($vSubject) OR empty($vInterest) OR empty($vMessage)){
    header('location: contact.html#formError1');
}
else if (substr_count($vEmail,"@") == 0 || substr_count($vEmail,".") == 0){
	header('location: contact.html#formError2');
}

//If everything is OK... Preparing to send the emails

else{		
	$vDate      = date("d/m/y");
	$vIp        = $_SERVER['REMOTE_ADDR'];
	$vBrowser 	= $_SERVER['HTTP_USER_AGENT'];
	$vTime     	= date("H:i:s", mktime(gmdate("H")-8, gmdate("i"), gmdate("s")));

	//Customize with your data
	
	$companyEmail 	= "myemail@mydomain.com";
	$companyName	  = "Your name or your company's name";
	$companyWebsite	= "www.yourwebsite.com";
	
	//Preparing email for Company
	
	$companyTitle = "Contact from $vName";
	
	$companyMessage	 = "Date and Time: $vDate - $vTime<br />";
	$companyMessage	.= "Phone: $vPhone<br />";
	$companyMessage	.= "Email: $vEmail<br />";
	$companyMessage	.= "Subject: $vSubject<br />";
	$companyMessage	.= "Interested in:";
	for($i = 0; $i < count($vInterest); $i++){
		if($vInterest[$i] != ""){
			$companyMessage .= "<br /> - ".htmlspecialchars($vInterest[$i]);
		}
	}	
	$companyMessage	.= "<br />Message: $vMessage";
	
	//Preparing email for Customer
	
	$customerName	  = $vName;
	$customerEmail 	= $vEmail;
	$customerTitle 	= "Contact from $companyName";
	
	$customerMessage = "
						$customerName, <br /><br />
						Thank you for contacting us. We will return shortly.<br /><br />
						Sincerely,<br />
						$companyName<br />
						$companyWebsite<br />
						$companyEmail
						";	
	
	//Preparing headers
	
	$headers = "From: $companyEmail\r\n" .
				"Reply-To: $customerEmail\r\n" .
				"X-Mailer: PHP/" . phpversion() . "\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=utf-8\r\n";
	
	$headersCustomer = 	"From: $customerEmail\r\n" .
				   		"Reply-To: $companyEmail\r\n" .
				   		"X-Mailer: PHP/" . phpversion() . "\r\n";
	$headersCustomer .= "MIME-Version: 1.0\r\n";
	$headersCustomer .= "Content-Type: text/html; charset=utf-8\r\n";
				
	//Sendind the emails
					
	if(	mail($companyEmail, $companyTitle, $companyMessage, $headers, "-r".$companyEmail) AND
		mail($customerEmail, $customerTitle, $customerMessage, $headersCustomer, "-r".$companyEmail)
		){
		header('location: contact.html#formSuccess');
	}else{
		header('location: contact.html#formError3');
	}		
}
?>

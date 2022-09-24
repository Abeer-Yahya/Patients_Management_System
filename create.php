<?php
// Include conn file
require_once "conn.php";
 
// Define variables and initialize with empty values
$Name =  $Age = $Address = "";
$Name_err = $Age_err = $Address_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate name
    $input_name = trim($_POST["Name"]);
    if(empty($input_name)){
        $Name_err = "Please enter a name.";
    } elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $Name_err = "Please enter a valid name.";
    } else{
        $Name = $input_name;
    }
    
    // Validate age
    $input_age = trim($_POST["Age"]);
    if(empty($input_age)){
        $Age_err = "Please enter the age.";     
    } elseif(!ctype_digit($input_age)){
        $Age_err = "Please enter a positive integer value.";
    } else{
        $Age = $input_age;
    }
    // Validate address
    $input_address = trim($_POST["Address"]);
    if(empty($input_address)){
        $Address_err = "Please enter an address.";     
    } else{
        $Address = $input_address;
    }
    
    
    // Check input errors before inserting in database
    if(empty($Name_err) && empty($Age_err) && empty($Address_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO patients (Name, Age, Address) VALUES (:Name, :Age, :Address)";
        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":Name", $param_name);
            $stmt->bindParam(":Age", $param_age);
            $stmt->bindParam(":Address", $param_address);


            // Set parameters
            $param_name = $Name;
            $param_age = $Age;
            $param_address = $Address;

            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Records created successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        unset($stmt);
    }
    
    // Close connection
    unset($pdo);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Create Record</h2>
                    <p>Please fill this form and submit to add patient record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="Name" class="form-control <?php echo (!empty($Name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $Name; ?>">
                            <span class="invalid-feedback"><?php echo $Name_err;?></span>
                        </div>

                        <div class="form-group">
                            <label>Age</label>
                            <input type="text" name="Age" class="form-control <?php echo (!empty($Age_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $Age; ?>">
                            <span class="invalid-feedback"><?php echo $Age_err;?></span>
                        </div>

                        <div class="form-group">
                            <label>Address</label>
                            <textarea name="Address" class="form-control <?php echo (!empty($Address_err)) ? 'is-invalid' : ''; ?>"><?php echo $Address; ?></textarea>
                            <span class="invalid-feedback"><?php echo $Address_err;?></span>
                        </div>
                     
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
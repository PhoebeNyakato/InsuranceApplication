<?php
session_start();

error_reporting(E_ALL);  // Set error reporting to show all types of errors.
 //Instructs PHP to track every type of error, warning, and notice.
ini_set('display_errors', 1);// Ensures that all errors are displayed on the web page for debugging purposes.

$host = "127.0.0.1";//default for myphpadmin local host(my computer)
$username = "root";// default username for using myphpadmin on localhost
$password = ""; // left blank to use default password
$port= ''; //left blank to use the default port/password.
$dbname = "policy_holder_db"; // Database name

$dbconn = mysqli_connect($host, $username, $password, $dbname); // Create connection to the database.

if (!$dbconn) {
    die("Database connection failed: " . mysqli_connect_error()); //checks if connection variable exists.
} //If the connection failed, it stops the script immediately and prints an error message.

$success = false; // starts as false and only turns true if the (variable is initiialized) database save works.
$error = ""; // Initialize error message variable. will store any error messages to show the user later.


if (isset($_POST['submit'])) { //Checks if the form was actually submitted by looking for a button or input named "submit" in the POST request.

    $policy_holder_name = $_POST['policy_holder_name'];
    $age = $_POST['age'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];
    $gender = $_POST['gender'];
    $occupation = $_POST['occupation'];
    $beneficiary_1 = $_POST['beneficiary_1'];
    $beneficiary_2 = $_POST['beneficiary_2'];
    $beneficiary_3 = $_POST['beneficiary_3'];
    // These lines capture the data typed into the form fields and store them in local PHP variables

    $sql = "
        INSERT INTO policy_holder 
        (policy_holder_name, age, email, telephone, gender, occupation,
         beneficiary_1, beneficiary_2, beneficiary_3)
        VALUES
        ('$policy_holder_name', '$age', '$email', '$telephone', '$gender',
         '$occupation', '$beneficiary_1', '$beneficiary_2', '$beneficiary_3')
    ";
    //INSERT INTO tells the database to add a new row(data) to the policy_holder table.
    //Values consists of the actual data to be inserted, taken from the form fields.(PHP variables)

    if (mysqli_query($dbconn, $sql)) { ////Sends the command to the database. If it works, $success is set to true.
        $success = true;
    } else {
        $error = mysqli_error($dbconn); //If it fails, the error message from the database is stored in $error.

    }
    
}
?>
<!DOCTYPE html>
 <html lang="en">
 <head>
    <meta charset="UTF-8">
    <title>Ascend Life Insurance  Confirmation</title>

    <style>
    body{
        font-family: "Segoe UI", Arial, sans-serif;
        background: linear-gradient(120deg, #fddbb0, #b8e4f0);
        padding: 40px;
    }

    .container{
        background: #a7d7e4;
        max-width: 800px;
        margin: auto;
        padding: 50px;
        border-radius: 20px;
        text-align: center;
    }

    h1{
        margin-bottom: 10px;
    }

    .success{
        color: #1e8449;
        font-size: 20px;
        margin-top: 20px;
    }

    .error{
        color: #c0392b;
        font-size: 18px;
    }

    .details{
        text-align: left;
        margin-top: 30px;
        background: rgba(255,255,255,0.6);
        padding: 25px;
        border-radius: 12px;
    }

    .details p{
        margin: 8px 0;
    }

    a.button{
        display: inline-block;
        margin-top: 30px;
        padding: 14px 30px;
        background: #2c3e50;
        color: white;
        text-decoration: none;
        border-radius: 10px;
        font-size: 16px;
    }
    .premium-box {
    background: white;
    padding: 20px;
    border-radius: 10px;
    border:  #2c3e50;
    margin-top: 20px;
    text-align: center;
    color: #2c3e50;
    }
    </style>
 </head>

    <body>

        <div class="container">

            <?php if ($success):             
            ?>
             <p class="success">Your policy holder details have been successfully captured.</p>

                <!--This section uses PHP's "If statement" to show the content based on  the data  saved.
                If $success is true,  at the end it shows a thank you message and the details submitted.-->
                <div class="details">
                    <p><strong>Name:</strong> <?= htmlspecialchars($policy_holder_name) ?></p> 
                    <!--Short echo tag used to print the variable value.-->
                    <!--htmlspecialchars ensures the browser treats the input like a normal sentence.-->
                    <p><strong>Age:</strong> <?= htmlspecialchars($age) ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($email) ?></p>
                    <p><strong>Telephone:</strong> <?= htmlspecialchars($telephone) ?></p>
                    <p><strong>Gender:</strong> <?= htmlspecialchars($gender) ?></p>
                    <p><strong>Occupation:</strong> <?= htmlspecialchars($occupation) ?></p>
                    <p><strong>Beneficiary 1:</strong> <?= htmlspecialchars($beneficiary_1) ?></p>
                    <p><strong>Beneficiary 2:</strong> <?= htmlspecialchars($beneficiary_2) ?></p>
                    <p><strong>Beneficiary 3:</strong> <?= htmlspecialchars($beneficiary_3) ?></p>
                
                    <h2>Premium Assessment</h2>
                    <p>Premium Payment for: <strong><?= htmlspecialchars($policy_holder_name) ?></strong></p>

                    <?php
                    $base_premium = 50000; // Base premium in ugx
                    $age_factor = ($age < 25) ? 1.5 : (($age <= 40) ? 1.2 : 1.0);
                    $occupation_factor = ($occupation == 'Student' ) ? 1.5 : 1.0;
                    $gender_factor = ($gender == 'Male') ? 1.1 : 1.0; 
                    $final_premium = $base_premium * $age_factor * $occupation_factor * $gender_factor;
                    ?>
                    <div class="premium-box">
                        <strong>Your calculated premium is: UGX <?= ($final_premium) ?></strong>
                    </div>
                </div>
                <h1>Would you like to proceed?</h1> 

                <a href="policies.html" class="button">Yes,Proceed to get insurance policy.</a>
                <a href="index.html" class="button">No, Exit.</a>

             <!--This section uses PHP's "If statement" to show an error  based on what  data was input.-->

            <?php else: ?>
            
                <h1>Submission Failed</h1>
                <p class="error">Something went wrong while saving your details.</p>
                <p class="error"><?= htmlspecialchars($error) ?></p>

                <a href="insurance_policy_holder_details.php" class="button">Go Back</a>

            <?php endif; ?>

        </div>

    </body>
</html>


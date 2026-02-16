<?php
session_start();

error_reporting(E_ALL);  // Set error reporting to show all types of errors.
ini_set('display_errors', 1);// Ensures that all errors are displayed on the web page for debugging purposes.

require __DIR__ . '/vendor/autoload.php'; // Load Composer's autoloader for any external libraries.

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

use PHPMailer\PHPMailer\PHPMailer; //send emails using PHPMailer library
use PHPMailer\PHPMailer\Exception; 

$host = "127.0.0.1";
$username = "root";
$password = ""; 
$port= ''; 
$dbname = "policy_holder_db"; 

$dbconn = mysqli_connect($host, $username, $password, $dbname); 
if (!$dbconn) {
    die("Database connection failed: " . mysqli_connect_error()); 
}
$success = false; 
$error = "";


if (isset($_POST['submit'])) { 

    $policy_holder_name = $_POST['policy_holder_name'];
    $age = $_POST['age'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];
    $gender = $_POST['gender'];
    $occupation = $_POST['occupation'];
    $beneficiary_1 = $_POST['beneficiary_1'];
    $beneficiary_2 = $_POST['beneficiary_2'];
    $beneficiary_3 = $_POST['beneficiary_3'];
    $policy_type = $_POST['policy_type'];
    $dateofbirth = date('Y-m-d', strtotime($_POST['dateofbirth']));
    $date = date('Y-m-d H:i:s');

    // Map Policy IDs to Readable Names
    $policy_names = [
        'Health_Essential' => 'Health Insurance - Essential Care',
        'Health_Family' => 'Health Insurance - Family Plus',
        'Health_Premium' => 'Health Insurance - Premium Elite',
        'Life_Term' => 'Life Insurance - Term Life',
        'Life_Whole' => 'Life Insurance - Whole Life',
        'Life_Endowment' => 'Life Insurance - Endowment'
    ];
    
    // Get readable name or default to raw value
    $display_policy_type = isset($policy_names[$policy_type]) ? $policy_names[$policy_type] : $policy_type;

    $base_premium = 50000; 
    $age_factor = ($age < 25) ? 1.5 : (($age <= 40) ? 1.2 : 1.0);
    $occupation_factor = ($occupation == 'Student') ? 1.4 : 
                        (($occupation == 'Unemployed') ? 1.5 : 
                        (($occupation == 'Retired') ? 1.3 : 
                        (($occupation == 'Employed') ? 1.0 : 1.0)));
    $gender_factor = ($gender == 'Male') ? 1.1 : 1.0; 
    $final_premium = $base_premium * $age_factor * $occupation_factor * $gender_factor;

    $next_due_date = date('Y-m-d', strtotime('+1 days'));

    // Generate Unique Policy Number
    $policy_number = "POL-" . date('Ymd') . "-" . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 4));

    $sql = "
        INSERT INTO policy_holder 
        (policy_holder_name, age, email, telephone, gender, occupation, dateofbirth, date,
         beneficiary_1, beneficiary_2, beneficiary_3, policy_type, premium_amount, next_due_date, policy_number)
        VALUES
        ('$policy_holder_name', '$age', '$email', '$telephone', '$gender',
         '$occupation', '$dateofbirth', '$date',
         '$beneficiary_1', '$beneficiary_2', '$beneficiary_3', '$policy_type', '$final_premium', '$next_due_date', '$policy_number')
    ";
   

    if (mysqli_query($dbconn, $sql)) { 
        $success = true;

        $mail = new PHPMailer(true); 

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com'; 
            $mail->SMTPAuth   = true; 
            
            $mail->Username   = $_ENV['SMTP_USER'];  
            $mail->Password   = $_ENV['SMTP_PASS'];  
            
            $mail->Port       = 587;  
            
            $mail->setFrom($_ENV['SMTP_USER'], 'Ascend Life Insurance');
            $mail->addAddress($_POST['email']);// Add a recipient


            $mail->isHTML(true);
            $mail->Subject = 'Policy Registration Confirmation';
            $mail->Body = "
                <h2>Welcome to Ascend Life Insurance</h2>
                <p>Dear " . htmlspecialchars($policy_holder_name) . ",</p>
                <p>Your policy has been registered successfully on " . htmlspecialchars($date) . ".</p>
                <p><strong>Policy Number: " . htmlspecialchars($policy_number) . "</strong></p>
                <p>Please keep this number safe for future reference.</p>
                <p><strong>Thank you for choosing us.</strong></p>
            ";
 
            $mail->send();
            echo "Registration successful! Confirmation email sent.";
        } catch (Exception $e) { 
            echo "Saved, Email failed to send: {$mail->ErrorInfo}"; 
        }

    } else {
        $error = mysqli_error($dbconn); 
        echo "Database Error: " . $error;
    }
}
?>
<!DOCTYPE html>
 <html lang="en">
 <head>
    <meta charset="UTF-8">
    <title>Ascend Life Insurance - Confirmation</title>
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

    body {
        font-family: 'Inter', sans-serif;
        background: linear-gradient(135deg, #fff7ed 0%, #e0f2fe 100%);
        padding: 40px 20px;
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 0;
    }

    .container {
        background: #ffffff;
        max-width: 700px;
        width: 100%;
        margin: auto;
        padding: 50px;
        border-radius: 16px;
        text-align: center;
        box-shadow: 0 10px 25px -5px rgba(14, 165, 233, 0.1); 
        border: 1px solid #fed7aa; 
    }

    h1 {
        margin-bottom: 15px;
        color: #1e293b;
        font-weight: 800;
        font-size: 1.75rem;
        letter-spacing: -0.5px;
    }

    .success {
        color: #0f766e; 
        font-size: 1.1rem;
        font-weight: 500;
        margin-top: 20px;
        background-color: #ccfbf1;
        padding: 10px;
        border-radius: 8px;
        display: inline-block;
    }

    .error {
        color: #ef4444;
        font-size: 1.1rem;
        font-weight: 500;
        background-color: #fee2e2;
        padding: 10px;
        border-radius: 8px;
        display: inline-block;
    }

    .details {
        text-align: left;
        margin-top: 40px;
        background: #fff7ed; 
        padding: 30px;
        border-radius: 12px;
        border: 1px solid #fed7aa;
    }

    .details p {
        margin: 12px 0;
        color: #334155;
        font-size: 0.95rem;
        display: flex;
        justify-content: space-between;
        border-bottom: 1px dashed #cbd5e1;
        padding-bottom: 5px;
    }
    
    .details p:last-child {
        border-bottom: none;
    }
    
    .details strong {
        color: #0f172a;
        font-weight: 600;
    }

    .details h2 {
        font-size: 1.25rem;
        color: #f97316; 
        margin-top: 30px;
        margin-bottom: 15px;
        border-bottom: 2px solid #fed7aa;
        padding-bottom: 10px;
    }

    a.button {
        display: inline-block;
        margin-top: 30px;
        padding: 14px 30px;
        background: #f97316; 
        color: white;
        text-decoration: none;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        transition: background 0.2s;
    }
    
    a.button:hover {
        background-color: #ea580c;
    }
    
    a.button.secondary {
        background-color: white;
        color: #64748b;
        border: 1px solid #cbd5e1;
        margin-left: 10px;
    }
    
    a.button.secondary:hover {
        background: #f1f5f9;
        border-color: #94a3b8;
    }

    .premium-box {
        background: #ffffff;
        padding: 20px;
        border-radius: 10px;
        border: 1px solid #0ea5e9; /* Sky Blue Border */
        margin-top: 25px;
        text-align: center;
        color: #0f172a;
        box-shadow: 0 4px 6px -1px rgba(14, 165, 233, 0.1);
    }
    
    .premium-value {
        font-size: 1.5rem;
        color: #0ea5e9; /* Sky Blue Value */
        font-weight: 700;
        display: block;
        margin-bottom: 5px;
    }
    </style>
 </head>

    <body>

        <div class="container">

            <?php if ($success):             
            ?>
             <h1>Registration Successful</h1>
             <p class="success">Your policy holder details have been successfully captured.</p>
                <div class="details">
                    <p><span>Name:</span> <strong><?= htmlspecialchars($policy_holder_name) ?></strong></p> 
                    <p><span>Policy Number:</span> <strong><?= htmlspecialchars($policy_number) ?></strong></p>
                    <p><span>Age:</span> <strong><?= htmlspecialchars($age) ?></strong></p>
                    <p><span>Email:</span> <strong><?= htmlspecialchars($email) ?></strong></p>
                    <p><span>Telephone:</span> <strong><?= htmlspecialchars($telephone) ?></strong></p>
                    <p><span>Gender:</span> <strong><?= htmlspecialchars($gender) ?></strong></p>
                    <p><span>Occupation:</span> <strong><?= htmlspecialchars($occupation) ?></strong></p>
                    <p><span>Policy Type:</span> <strong><?= htmlspecialchars($display_policy_type) ?></strong></p>
                    
                    <h2>Premium Assessment</h2>
                    <p><span>Premium Payment for:</span> <strong><?= htmlspecialchars($policy_holder_name) ?></strong></p>

                    <div class="premium-box">
                        <span class="premium-value">UGX <?= number_format($final_premium, 2) ?></span>
                        <small>Next Payment Due: <strong><?= htmlspecialchars($next_due_date) ?></strong></small>
                    </div>
                </div>
                
                <h3 style="margin-top: 40px; color: #64748b;">Would you like to proceed?</h3>

                <a href="policies.html" class="button">Yes, Proceed to Policy</a>
                <a href="index.html" class="button secondary">No, Exit</a>


            <?php else: ?>
            
                <h1>Submission Failed</h1>
                <p class="error">Something went wrong while saving your details.</p>
                <p class="error"><?= htmlspecialchars($error) ?></p>

                <a href="insurance_policy_holder_details.html" class="button">Go Back to Form</a>

            <?php endif; ?>

        </div>

    </body>
</html>


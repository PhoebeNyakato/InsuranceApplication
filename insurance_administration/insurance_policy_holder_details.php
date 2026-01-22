<?php
session_start(); //initiate sessions, which allows you to store data across multiple pages.
error_reporting(E_ALL); // needs to set to E_ALL for development, 0 for production. also reprts all types of errors.
ini_set('display errors',1); // needs to set to 1 for development, 0 for production. also displayes errors on the browser

$host= '127.0.0.1'; //default for myphpadmin local host(my computer)
$dbname='policy_holder_db'; //database name
$username='root'; //default username for using myphpadmin on localhost
$port= ''; //left blank to use the default port/password.
$password = '';

//creating database connection.
$dbconn = mysqli_connect($host, $username, $password, $dbname); 

if (isset($_POST['submit'])){ //if submit has been pressed, pick the details below.
    $age = $_POST['age'];
    $policy_holder_name = $_POST['policy_holder_name'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $beneficiary_1 = $_POST['beneficiary_1'];
    $beneficiary_2 = $_POST['beneficiary_2'];
    $beneficiary_3 = $_POST['beneficiary_3'];
    $telephone = $_POST['telephone'];
    $occupation = $_POST['occupation'];
     //collecting form data.

    $query = mysqli_query($dbconn,
    "insert into policy_holder
    (age, policy_holder_name, email, gender, beneficiary_1, beneficiary_2, beneficiary_3, telephone, occupation)
    value
    ('$age', '$policy_holder_name', '$email', '$gender', '$beneficiary_1', '$beneficiary_2', '$beneficiary_3', '$telephone', '$occupation')"
    );  
     //inserting form data into the database table named policy_holder.

    if($query){
        echo "<script>alert('Policy Holder Details added successfully');</script>"; //if querry was successfull, brings alert
        echo "<script>window.location.href = 'thank_you.html'</script>";
    }

    else{
         echo "<script>alert('Something went wrong. Please try registering again.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Policy Holder Details</title>

  <style>
    body {
      margin: 0;
      height: 100vh;
      font-family: Arial, Helvetica, sans-serif;
    }

    .container {
      position: relative;
      width: 100%;
      height: 800px;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .bg-image {
      position: absolute;
      width: 100%;
      height: 100%;
      object-fit: cover;
      opacity: 0.5;
      z-index: -1;
    }

    .card {
      background-color: rgb(158, 205, 220);
      padding: 40px 50px;
      border-radius: 25px;
      width: 75%;
      max-width: 1000px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }

    .card h1 {
      font-size: 40px;
      margin-bottom: 10px;
    }

    .card h2 {
      font-size: 22px;
      font-weight: normal;
      margin-bottom: 35px;
    }
    .card h2 {
      font-size: 18px;
      font-weight: normal;
      margin-bottom: 30px;
    }  
    form {
      width: 100%;
    }

    .form-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px 30px;
      margin-bottom: 20px;
    }

    label {
      font-size: 14px;
      margin-bottom: 5px;
      display: block;
    }

    input {
      width: 100%;
      padding: 10px;
      border-radius: 6px;
      border: 1px solid #999;
      font-size: 14px;
    }

    .full-width {
      grid-column: 1 / -1;
    }

    button {
      margin-top: 30px;
      padding: 12px 30px;
      border-radius: 8px;
      border: none;
      background-color: #f7f1e3;
      font-size: 16px;
      cursor: pointer;
    }

    button:hover {
      background-color: #eee;
    }

    @media (max-width: 768px) {
      .form-row {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>

<body>
  <div class="container">
    <img src="background.jfif" class="bg-image" alt="Background">

    <div class="card">
      <h1> Welcome to Ascend Life Insurance!</h1>
      <h3>"Here for you"</h3>
      <h2>Please fill in your policy holder details</h2>

      <form method="post" action="">
        <div class="form-row">
          <div>
            <label>Policy Holder Name</label>
            <input type="text" name="policy_holder_name" required>
          </div>

          <div>
            <label>Age</label>
            <input type="text" name="age" placeholder="" required>
          </div>

          <div>
            <label>Email Address</label>
            <input type="email" name="email" placeholder=" e.g nyakatophoebe@gmail.com" required>
          </div>

          <div>
            <label>Telephone</label>
            <input type="text" name="telephone" required>
          </div>

          <div>
            <label>Gender</label>
            <input type="text" name="gender" placeholder="male or female" required>
          </div>  

         <div>
            <label>Occupation</label>
            <input type="text" name="occupation" placeholder="e.g teacher, doctor, business man/woman etc" required>
         </div>

          <div>
            <label>Beneficiary 1</label>
            <input type="text" name="beneficiary_1" placeholder="e.g (parent)atwine praise" required>
          </div>

          <div>
            <label>Beneficiary 2</label>
            <input type="text" name="beneficiary_2" placeholder="e.g (child)atwine praise" required>
          </div>

          <div>
            <label>Beneficiary 3</label>
            <input type="text" name="beneficiary_3" placeholder="e.g (spouse)atwine praise" required>
          </div>

          
        </div>

        <button type="submit" name="submit">Submit Policy Details</button>
      </form>
    </div>
  </div>
</body>
</html>


<!DOCTYPE html>
 <html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Ascend Life Insurance – Policy Holder Details</title>

    <style>
    body{
        font-family: "Segoe UI", Arial, sans-serif;
        background: linear-gradient(120deg, #fddbb0, #b8e4f0);
        padding: 30px;
    }

    .container{
        background: #a7d7e4;
        max-width: 1000px;
        margin: auto;
        padding: 40px;
        border-radius: 20px;
    }

    h1{
        margin-bottom: 5px;
    }

    .tagline{
        font-style: italic;
        margin-bottom: 25px;
    }

    .form-grid{
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
    }

    label{
        font-weight: 600;
        display: block;
        margin-bottom: 6px;
    }

    input, select{
        width: 100%;
        padding: 12px;
        border-radius: 8px;
        border: 1px solid #999;
        font-size: 15px;
    }

    .full-width{
        grid-column: 1 / -1;
    }

    button{
        margin-top: 25px;
        width: 100%;
        padding: 15px;
        background: #2c3e50;
        color: white;
        font-size: 18px;
        border: none;
        border-radius: 10px;
        cursor: pointer;
    }
    </style>
  </head>

  <body>

    <div class="container">
        <h1>Welcome to Ascend Life Insurance!</h1>
        <div class="tagline">"Here for you"</div>
        <p>Please fill in your policy holder details</p>

        <form method="post" action="confirmation.php">
            <div class="form-grid">

                <div>
                    <label>Policy Holder Name</label>
                    <input type="text" name="policy_holder_name" required>
                </div>

                <div>
                    <label>Age</label>
                    <input type="number" name="age" required>
                </div>

                <div>
                    <label>Email Address</label>
                    <input type="email" name="email" required>
                </div>

                <div>
                    <label>Telephone</label>
                    <input type="text" name="telephone" required>
                </div>

                <div>
                    <label>Gender</label>
                    <select name="gender" required>
                        <option value="">- Select -</option>
                        <option value="Female">Female</option>
                        <option value="Male">Male</option>
                    </select>
                </div>

                <div>
                    <label>Occupation</label>
                    <select name="occupation" required>
                        <option value="">- Select -</option>
                        <option value="Employed">Employed</option>
                        <option value="Self-Employed">Self-Employed</option>
                        <option value="Unemployed">Unemployed</option>
                        <option value="Student">Student</option>
                        <option value="Retired">Retired</option>
                    </select>
                </div>

                <div>
                    <label>Beneficiary 1</label>
                    <input type="text" name="beneficiary_1" require>
                </div>

                <div>
                    <label>Beneficiary 2</label>
                    <input type="text" name="beneficiary_2" required>
                </div>

                <div class="full-width">
                    <label>Beneficiary 3</label>
                    <input type="text" name="beneficiary_3" >
                </div>

            </div>

            <button type="submit" name="submit">Proceed</button>
        </form>
    </div>

  </body>
</html>


<?php 
session_start();
include("../conn.php");

extract($_POST);

// Select the user by email
$selAcc = $conn->query("SELECT * FROM examinee_tbl WHERE exmne_email='$username'");
$selAccRow = $selAcc->fetch(PDO::FETCH_ASSOC);

if($selAcc->rowCount() > 0)
{
    // Verify the password entered by the user against the hashed password in the database
    if(password_verify($pass, $selAccRow['exmne_password']))
    {
        // Password is correct, create session
        $_SESSION['examineeSession'] = array(
            'exmne_id' => $selAccRow['exmne_id'],
            'examineenakalogin' => true
        );
        $res = array("res" => "success");
    }
    else
    {
        // Password does not match
        $res = array("res" => "invalid");
    }
}
else
{
    // No user found with this email
    $res = array("res" => "invalid");
}

echo json_encode($res);
?>

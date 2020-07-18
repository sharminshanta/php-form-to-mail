<?php
session_start();

//Dependency setup
require_once 'vendor/autoload.php';

if (!empty($_POST)) {
//phpmailer object
$mail = new PHPMailer();
//Enable SMTP debugging.
$mail->SMTPDebug = 0;
//Set PHPMailer to use SMTP.
$mail->isSMTP();

//Set SMTP host name
$mail->Host = "smtp.gmail.com";
//Set this to true if SMTP host requires authentication to send email
$mail->SMTPAuth = true;

//Provide username and password
$mail->Username = "SMTP_USERNAME";
$mail->Password = "SMTP_PASSWORD";

//Set from
$mail->SetFrom($_POST["email"], $_POST["name"]);

//If SMTP requires TLS encryption then set it
$mail->SMTPSecure = "tls";
//Set TCP port to connect to
$mail->Port = 587; // Put your SMTP port [587 or 25 or 999];

//from email address and name
$mail->From = $_POST['email'];
$mail->FromName = $_POST['name'];
$mail->Sender = $_POST['email'];

//to email address and name
$mail->addAddress("example@gmail.com", "Example Name");
// if you want to send to multiple recipient
//$mail->addAddress($_POST['email'], $_POST['name']); //Recipient name is optional

//Send HTML or Plain Text email
$mail->isHTML(true);

$mail->Subject = $_POST['subject'];
$name = $_POST['name'];
$message = $_POST['message'];
$mail->Body = "You have received a new from the user $name. \n".
    "Here is the message: \n $message";
$mail->AltBody = "This is the plain text version of the email content";
//var_dump($mail); die();

    try {
        if ($mail->send() === true) {
            $_SESSION['message'] = "success";
        } elseif ($mail->send() === false) {
            $_SESSION['message'] = "error";
        }
    } catch (phpmailerException $e) {
        echo $e->getMessage();
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Email Template | @yield('title')</title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/jquery.min.js"></script>
    <!------ Include the above in your HEAD tag ---------->

</head>
<body>
<div class="container">
    <div class="mt-5">
        <div class="row">
            <div class="col-lg-6 offset-3">
                <?php
                if (isset($_SESSION['message'])) {
                    if ($_SESSION['message'] === "success") { ?>
                        <div class="alert alert-success" role="alert">
                            <?php echo "Mail sent successfully."; ?>
                        </div>
                    <?php } elseif ($_SESSION['message'] === "error") { ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo "Something went wrong."; ?>
                        </div>
                    <?php } ?>
                <?php } ?>
                <div class="card-header bg-info text-white py-3">
                    <div class="row">
                        <div class="col-lg-7 col-md-10">
                            <span>Compose New Message</span>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form method="post" action="#">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <label for="name">Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" placeholder="Enter Name" required="required">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <label for="toEmail">To <span class="text-danger">*</span></label>
                                        <input type="email" name="email" class="form-control" placeholder="Enter Email Address" required="required">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <label for="toSub">Subject <span class="text-danger">*</span></label>
                                        <input type="text" name="subject" maxlength="30" class="form-control" placeholder="Enter Subject" required="required">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <label for="mes">Message <span class="text-danger">*</span></label>
                                        <textarea name="message" maxlength="120" rows="4" class="form-control" placeholder="Enter Message" required="required"></textarea>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary py-1">Send Message</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>

<?php
//At first, unset the session
session_unset();

//Finally, destroy the session
session_destroy();
?>
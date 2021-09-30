<?php 
    // This class for send OTP code throug email usein PHPMailer
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    require "../PHPMailer/vendor/autoload.php";
    require_once "DbConnection.class.php";

    class MailHandle extends DbConnection{

        private $username;
        private $usermail;
        private $userid;
        private $otpCode;

        // send OTP code
        public function sendOTP($userMail){
            $this->usermail = $userMail;
            $otpres = $this->GetReleventDetails();
            if($otpres == "ok"){
                $otpSendResult = $this->otpSendMethod();
                if($otpSendResult == "SENDOTP"){
                    return "SENDOTP";
                    exit();
                }
                else{
                    return $otpSendResult;
                    exit();
                }
            }
            else{
                return $otpres;
                exit();
            }
        }
        
        // check database and get relevent details to send OTP code useing email
        private function GetReleventDetails() {
            $sqlQ = "SELECT user_id, username, otpCode FROM users WHERE email=?";
            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                return "sqlerror";
                exit();
            }
            else{
                mysqli_stmt_bind_param($stmt, "s", $this->usermail);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                if($row = mysqli_fetch_assoc($result)){
                    $this->username = $row['username'];
                    $this->userid = $row['user_id'];
                    $this->otpCode = $row['otpCode'];
                    return "ok";
                }
                else{
                    return "noemail";
                    exit();
                }
            }
        }

        // send email with OTP code using PHPMailer
        public function otpSendMethod(){
            $mail = new PHPMailer(true);

            try{
                $mail->SMTPDebug = 0; // SMTP Debug service
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'chatchops@gmail.com';
                $mail->Password = 'sltc@gamechangers';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;
                $mail->setFrom('chatchops@gmail.com'. 'ChatChop');
                $mail->addAddress($this->usermail, $this->username);
                $mail->isHtml(true);
                $mail->Subject = 'Email verification from ChatChop';
                $mail->Body = '<p>Your verfication code is: <b style="font-size: 30px;">'.$this->otpCode.'</b></p>';
                $mail->send();
                return "SENDOTP";
            }
            catch(Exception $e){
                echo "Message could not be send. Mail Error: {$mail->ErrorInfo}";
                return "OTPSENDERROR";
            }
        }

    }
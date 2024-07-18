<?php

if(!defined("TERANGA__ROOT_DIR"))
{
    define("TERANGA__ROOT_DIR", __DIR__);
}



// Include vendor autoloader for composer dependencies
require_once TERANGA__ROOT_DIR . "/vendor/autoload.php";
// Libs and helper functions
require_once TERANGA__ROOT_DIR . "/v1/lib/functions.php";


$dotenv = Dotenv\Dotenv::createImmutable(TERANGA__ROOT_DIR);
$dotenv->safeLoad();



define("BASE_URL", "http://91.108.112.225/");
define("APPLICATION_NAME", "Teranga");


// === HOSTS ===

// Old servers
define("SRV_LOCALHOST__IP_ADDRESS", "127.0.0.1");
define("SRV_TERANGA__IP_ADDRESS", "127.0.0.1");


// Server : Localhost / Database : maurlims
define("SRV_LOCALHOST__DATABASE_BDDUAVFH", "malilims");
define("SRV_LOCALHOST__USER_ARBO__USERNAME", "limsuser");
define("SRV_LOCALHOST__USER_ARBO__PASSWORD", "Malilims@2024");






// Set error handler to static method Teranga_Error::global_error_handler
set_error_handler(['Teranga_Error', 'global_error_handler']);
// Set exception handler to static method Teranga_Error::global_exception_handler
set_exception_handler(['Teranga_Error', 'global_exception_handler']);

class Teranga_Error {

    const REPORT_EMAIL =
        //"x+1201559961335740@mail.asana.com" // Send reports to Asana ("Teranga - HI - ECRDS" project)
        //"x+1202241647035790@mail.asana.com" // Send reports to Asana ("Teranga Prod Server Errors" project)
        "CISSE Mamadou <mseesay024@gmail.com>"
    ;
    const REPORT_REPLYTO_EMAIL = "mamadou.cisse@pasteur.sn";
    const REPORT_EMAIL_RECIPIENTS = [
        "CISSE Mamadou <mseesay024@gmail.com>"
        /*
        "Assane Mbengue <assane.mbengue@pasteur.sn>",
        "Mamadou CISSE <Mamadou.CISSE@pasteur.sn>",
        // TODO : add other recipients
        */
    ];

    static function createTable($array)
    {
        if (is_array($array) && count($array) > 0) {
            // Plain text version (display table with fixed width columns)
            $errorContent = "";
            foreach ($array as $key => $val) {
                $errorContent .= $key . " | ";
                if (is_array($val) && count($val) > 0) {
                    $errorContent .= self::createTable(json_decode(json_encode($val), true));
                } else {
                    $errorContent .= print_r($val, true);
                }
            }

            return $errorContent;
        }

        return '';
    }

    /**
     *
     * @param int $errorNumber // This parameter returns error number.
     * @param string $errorString // This parameter returns error string.
     * @param string $errorFile // This parameter returns path of file in which error found.
     * @param string $errorLine // This parameter returns line number of file in which you get an error.
     * @param array $errorContext // This parameter return error context.
     */
    static function global_error_handler($errorNumber, $errorString, $errorFile, $errorLine, $errorContext)
    {
        $emailSubject = 'Error on Teranga';
        // Plain text message
        $emailMessage = 'Error Reporting on :- ' . date("Y-m-d h:i:s", time()) . " <br>\n";
        $emailMessagePayload = "Error Number :- " . print_r($errorNumber, true) . " <br>\n";
        $emailMessagePayload .= "Error String :- " . print_r($errorString, true) . " <br>\n";
        $emailMessagePayload .= "Error File :- " . print_r($errorFile, true) . ":" . print_r($errorLine, true) . " <br>\n";
        //$emailMessagePayload .= "Error Context :- " . self::createTable($errorContext) . " <br>\n";
        $emailMessage .= $emailMessagePayload;
        $emailMessagePayloadHash = md5($emailMessagePayload);
        $emailMessage .= "Hash : " . $emailMessagePayloadHash . " <br>\n";

        $emailMessage .= self::extra_info();

        // Send email
        $headers = "MIME-Version: 1.0" . "rn";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: Teranga Error <teranga@pasteur.sn>" . "\r\n";
        $headers .= "Reply-To: ".self::REPORT_REPLYTO_EMAIL . "\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();

      //  mail(self::REPORT_EMAIL, $emailSubject . " (" . $emailMessagePayloadHash . ")", $emailMessage, $headers);
    }

    /**
     * @param Exception $exception
     * @return void
     */

    private static function extra_info()
    {
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $user_agent_info = "User Agent :- " . $user_agent . " <br>\n";
        if (isset($_SERVER['REMOTE_ADDR'])) {
            $user_agent_info .= "IP Address :- " . $_SERVER['REMOTE_ADDR'] . " <br>\n";
        }
        if (isset($_SERVER['REQUEST_URI'])) {
            $user_agent_info .= "URL :- " . $_SERVER['REQUEST_URI'] . " <br>\n";
        }
        return $user_agent_info;
    }

}

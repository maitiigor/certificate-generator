<?php
/**
 * Plugin Name
 *
 * @package           CertificateGenerator
 * @author            Oluwatosin Salami
 * @copyright         2019 maitiigor
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Certficate Generatorm
 * Plugin URI:        https://example.com/plugin-name
 * Description:       Helps in automatic generating of certificate sends email to certificate owners whenever a csv file containing their details are uploaded.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Oluwatosin Salami
 * Author URI:        https://example.com
 * Text Domain:       cert
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Update URI:        https://example.com/my-plugin/
 */
global $jal_db_version;
$jal_db_version = '1.0';

//require_once __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
//echo ABSPATH;
require __DIR__ . '/../../../wp-includes/PHPMailer/SMTP.php';
require __DIR__ . '/../../../wp-includes/PHPMailer/Exception.php';
require __DIR__ . '/../../../wp-includes/PHPMailer/PHPMailer.php';
require WP_PLUGIN_DIR . '/certificate-generator/controller/CertificateGeneratorController.php';

add_action('rest_api_init', function () {
    $certficate_generator_controller = new CertificateGeneratorController();
    $certficate_generator_controller->register_routes();
});

function certificate_generator_activate()
{

    add_option('Activated_Plugin', 'cert-gen');
    jal_install();
    /* activation code here */
}

register_activation_hook(__FILE__, 'certificate_generator_activate');
//register_deactivation_hook( __FILE__, 'certficate_generator_deactivate' );

/* function generate_cert_pdf(){

$mpdf = new \Mpdf\Mpdf(['orientation' => 'L']);
$filepath = __DIR__ ."/mpdf/temp/".substr(str_shuffle(md5(time())), 0, 8).'.pdf';
$content = get_document_template($data);
$mpdf->WriteHTML ($content,\Mpdf\HTMLParserMode::HTML_BODY);
$mpdf->Output($filepath, 'F');

return $filepath;
} */

function get_document_template($data)
{
    $html = null;
    if ($data['certificate_type'] == "2023 ICMC Induction Certificate of Membership (Fellow Cadre)") {
        $image = WP_PLUGIN_URL . "/certificate-generator/assets/2023Fellow.png";
        $date_month = date('F d', strtotime($data['issue_date']));
        $day = date('Y', strtotime($data['issue_date']));
        $code = $data['certificate_code'];
        $html = "
        <div style='min-width:210mm; min-height: 297mm; position:absolute; left: 100000px' id='cert-container' >
            <div style='padding: 0; margin: 0; width:210mm; height: 297mm; position: absolute;' id='cert-doc' data-val='$code'>
                <div>
                    <img src='$image' id='image-template' style='width: 100%; height: 100%' class='image-template'>
                    <div class='owner-name' style='width: 100%; position: absolute; top: 125mm; text-align: center; font-size: 35px;'>
                        <h2 style=' padding-left: 30mm; padding-right: 35mm; font-family: 'Trebuchet MS', Tahoma, san-serif;'>
                            {$data['owner_name']}
                        </h2>
                    </div>
                    <div style= 'position: absolute; top: 140mm; width: 297mm;' class='issued-date-container'>

                        <div class='issued-date-row' style='display: flex; font-size: 30px;'>

                            <div class='issued-date-col1' style='margin-left: 90mm; width: 55mm; text-align: center;'>
                                <span>

                                </span>
                            </div>
                            <div class='issued-date-col2' style='margin-left: 45mm; width: 50mm; text-align:center'>

                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>";
    }

    if ($data['certificate_type'] == "Membership Certificate for Fellow Cadre") {
        $image = WP_PLUGIN_URL . "/certificate-generator/assets/Fellow.png";
        $date_month = date('F d', strtotime($data['issue_date']));
        $day = date('Y', strtotime($data['issue_date']));
        $code = $data['certificate_code'];
        $html = "
        <div style='min-width:210mm; min-height: 297mm; position:absolute; left: 100000px' id='cert-container' >
            <div style='padding: 0; margin: 0; width:210mm; height: 297mm; position: absolute;' id='cert-doc' data-val='$code'>
                <div>
                    <img src='$image' id='image-template' style='width: 100%; height: 100%' class='image-template'>
                    <div class='owner-name' style='width: 100%; position: absolute; top: 128mm; text-align: center; font-size: 35px;'>
                        <h2 style=' padding-left: 30mm; padding-right: 35mm; font-family: 'Trebuchet MS', Tahoma, san-serif;'>
                            {$data['owner_name']}
                        </h2>
                    </div>
                    <div style= 'position: absolute; top: 140mm; width: 297mm;' class='issued-date-container'>

                        <div class='issued-date-row' style='display: flex; font-size: 30px;'>

                            <div class='issued-date-col1' style='margin-left: 90mm; width: 55mm; text-align: center;'>
                                <span>

                                </span>
                            </div>
                            <div class='issued-date-col2' style='margin-left: 45mm; width: 50mm; text-align:center'>

                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>";
    }
    if ($data['certificate_type'] == "Membership Certificate for Member Cadre") {
        $image = WP_PLUGIN_URL . "/certificate-generator/assets/Member.png";
        $date_month = date('F d', strtotime($data['issue_date']));
        $day = date('Y', strtotime($data['issue_date']));
        $code = $data['certificate_code'];
        $html = "
        <div style='min-width:210mm; min-height: 297mm; position:absolute; left: 100000px' id='cert-container' >
            <div style='padding: 0; margin: 0; width:210mm; height: 297mm; position: absolute;' id='cert-doc' data-val='$code'>
                <div>
                    <img src='$image' id='image-template' style='width: 100%; height: 100%' class='image-template'>
                    <div class='owner-name' style='width: 100%; position: absolute; top: 128mm; text-align: center; font-size: 35px;'>
                        <h2 style=' padding-left: 30mm; padding-right: 35mm; font-family: 'Trebuchet MS', Tahoma, san-serif;'>
                            {$data['owner_name']}
                        </h2>
                    </div>
                    <div style= 'position: absolute; top: 140mm; width: 297mm;' class='issued-date-container'>

                        <div class='issued-date-row' style='display: flex; font-size: 30px;'>

                            <div class='issued-date-col1' style='margin-left: 90mm; width: 55mm; text-align: center;'>
                                <span>

                                </span>
                            </div>
                            <div class='issued-date-col2' style='margin-left: 45mm; width: 50mm; text-align:center'>

                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>";
    }
    if ($data['certificate_type'] == "2023 ICMC Induction Certificate of Membership (Member Cadre)") {
        $image = WP_PLUGIN_URL . "/certificate-generator/assets/2023Member.png";
        $date_month = date('F d', strtotime($data['issue_date']));
        $day = date('Y', strtotime($data['issue_date']));
        $code = $data['certificate_code'];
        $html = "
        <div style='min-width:210mm; min-height: 297mm; position:absolute; left: 100000px' id='cert-container' >
            <div style='padding: 0; margin: 0; width:210mm; height: 297mm; position: absolute;' id='cert-doc' data-val='$code'>
                <div>
                    <img src='$image' id='image-template' style='width: 100%; height: 100%' class='image-template'>
                    <div class='owner-name' style='width: 100%; position: absolute; top: 125mm; text-align: center; font-size: 35px;'>
                        <h2 style=' padding-left: 30mm; padding-right: 35mm; font-family: 'Trebuchet MS', Tahoma, san-serif;'>
                            {$data['owner_name']}
                        </h2>
                    </div>
                    <div style= 'position: absolute; top: 140mm; width: 297mm;' class='issued-date-container'>

                        <div class='issued-date-row' style='display: flex; font-size: 30px;'>

                            <div class='issued-date-col1' style='margin-left: 90mm; width: 55mm; text-align: center;'>
                                <span>

                                </span>
                            </div>
                            <div class='issued-date-col2' style='margin-left: 45mm; width: 50mm; text-align:center'>

                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>";
    }
    if ($data['certificate_type'] == "Membership Certificate for Associate Cadre") {
        $image = WP_PLUGIN_URL . "/certificate-generator/assets/Associate.png";
        $date_month = date('F d', strtotime($data['issue_date']));
        $day = date('Y', strtotime($data['issue_date']));
        $code = $data['certificate_code'];
        $html = "
        <div style='min-width:210mm; min-height: 297mm; position:absolute; left: 100000px' id='cert-container' >
            <div style='padding: 0; margin: 0; width:210mm; height: 297mm; position: absolute;' id='cert-doc' data-val='$code'>
                <div>
                    <img src='$image' id='image-template' style='width: 100%; height: 100%' class='image-template'>
                    <div class='owner-name' style='width: 100%; position: absolute; top: 128mm; text-align: center; font-size: 35px;'>
                        <h2 style=' padding-left: 30mm; padding-right: 35mm; font-family: 'Trebuchet MS', Tahoma, san-serif;'>
                            {$data['owner_name']}
                        </h2>
                    </div>
                    <div style= 'position: absolute; top: 140mm; width: 297mm;' class='issued-date-container'>

                        <div class='issued-date-row' style='display: flex; font-size: 30px;'>

                            <div class='issued-date-col1' style='margin-left: 90mm; width: 55mm; text-align: center;'>
                                <span>

                                </span>
                            </div>
                            <div class='issued-date-col2' style='margin-left: 45mm; width: 50mm; text-align:center'>

                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>";
    }
    if ($data['certificate_type'] == "2023 ICMC Induction Certificate of Membership (Associate Cadre)") {
        $image = WP_PLUGIN_URL . "/certificate-generator/assets/2023Associate.png";
        $date_month = date('F d', strtotime($data['issue_date']));
        $day = date('Y', strtotime($data['issue_date']));
        $code = $data['certificate_code'];
        $html = "
        <div style='min-width:210mm; min-height: 297mm; position:absolute; left: 100000px' id='cert-container' >
            <div style='padding: 0; margin: 0; width:210mm; height: 297mm; position: absolute;' id='cert-doc' data-val='$code'>
                <div>
                    <img src='$image' id='image-template' style='width: 100%; height: 100%' class='image-template'>
                    <div class='owner-name' style='width: 100%; position: absolute; top: 125mm; text-align: center; font-size: 35px;'>
                        <h2 style=' padding-left: 30mm; padding-right: 35mm; font-family: 'Trebuchet MS', Tahoma, san-serif;'>
                            {$data['owner_name']}
                        </h2>
                    </div>
                    <div style= 'position: absolute; top: 140mm; width: 297mm;' class='issued-date-container'>

                        <div class='issued-date-row' style='display: flex; font-size: 30px;'>

                            <div class='issued-date-col1' style='margin-left: 90mm; width: 55mm; text-align: center;'>
                                <span>

                                </span>
                            </div>
                            <div class='issued-date-col2' style='margin-left: 45mm; width: 50mm; text-align:center'>

                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>";
    }
    if ($data['certificate_type'] == "Membership Certificate for Associate Cadre 2023") {
        $image = WP_PLUGIN_URL . "/certificate-generator/assets/Associate23.png";
        $date_month = date('F d', strtotime($data['issue_date']));
        $day = date('Y', strtotime($data['issue_date']));
        $code = $data['certificate_code'];
        $html = "
        <div style='min-width:210mm; min-height: 297mm; position:absolute; left: 100000px' id='cert-container' >
            <div style='padding: 0; margin: 0; width:210mm; height: 297mm; position: absolute;' id='cert-doc' data-val='$code'>
                <div>
                    <img src='$image' id='image-template' style='width: 100%; height: 100%' class='image-template'>
                    <div class='owner-name' style='width: 100%; position: absolute; top: 125mm; text-align: center; font-size: 35px;'>
                        <h2 style=' padding-left: 30mm; padding-right: 35mm; font-family: 'Trebuchet MS', Tahoma, san-serif;'>
                            {$data['owner_name']}
                        </h2>
                    </div>
                    <div style= 'position: absolute; top: 140mm; width: 297mm;' class='issued-date-container'>

                        <div class='issued-date-row' style='display: flex; font-size: 30px;'>

                            <div class='issued-date-col1' style='margin-left: 90mm; width: 55mm; text-align: center;'>
                                <span>

                                </span>
                            </div>
                            <div class='issued-date-col2' style='margin-left: 45mm; width: 50mm; text-align:center'>

                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>";
    }
    if ($data['certificate_type'] == "Certificate of Attendance") {
        $image = WP_PLUGIN_URL . "/certificate-generator/assets/Certificate_of_attendance_for_medact.png";
        $date_month = date('F Y', strtotime($data['issue_date']));
        //echo var_dump($date_month);
        $day = str_replace(" ",", ", date('dS', strtotime($data['issue_date'])));
        $code = $data['certificate_code'];
        $html = "
        <div style='min-width:297mm; min-height: 210mm; position:absolute; left: 100000px ' id='cert-container' >
            <div style='padding: 0; margin: 0; width:297mm; height: 210mm; position: absolute;' id='cert-doc' data-val='$code'>
                <div>
                    <img src='$image' id='image-template' style='width: 100%; height: 100%' class='image-template'>
                    <div class='owner-name' style='width: 100%; position: absolute; top: 70mm; text-align: center; font-size: 35px;'>
                        <h2 style=' padding-left: 30mm; font-size: 40px; padding-right: 35mm; font-family: 'Trebuchet MS', Tahoma, san-serif;'>
                            {$data['owner_name']}
                        </h2>
                    </div>
                    <div style= 'position: absolute; top: 140mm; width: 297mm;' class='issued-date-container'>

                        <div class='issued-date-row' style='display: flex; font-size: 30px;'>

                            <div class='issued-date-col1' style='margin-left: 100mm; width: 55mm; text-align: center;'>
                                <span>
                                    <b> {$day} </b>
                                </span>
                            </div>
                            <div class='issued-date-col2' style='margin-left: 45mm; width: 50mm; text-align:center'>
                               <b> {$date_month} </b>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>";
        
    }

    if ($data['certificate_type'] == "2024 ICMC Induction Certificate of Membership (Fellow Cadre)") {
        $image = WP_PLUGIN_URL . "/certificate-generator/assets/Fellow 2024.png";
        $date_month = date('F', strtotime($data['issue_date']));
        $date_year = date('y', strtotime($data['issue_date']));
        //echo var_dump($date_month);
        $day = str_replace(" ",", ", date('jS', strtotime($data['issue_date'])));
        $code = $data['certificate_code'];
        $html = "
        <div style='min-width:210mm; min-height: 297mm; position:absolute; left: 100000px ' id='cert-container' >
            <div style='padding: 0; margin: 0; width:210mm; height: 297mm; position: absolute;' id='cert-doc' data-val='$code'>
                <div>
                    <img src='$image' id='image-template' style='width: 100%; height: 100%' class='image-template'>
                    <div class='owner-name' style='width: 100%; position: absolute; top: 123mm; text-align: center; font-size: 35px;'>
                        <h2 style=' padding-left: 30mm; font-size: 40px; padding-right: 35mm; font-family: 'Trebuchet MS', Tahoma, san-serif;'>
                            {$data['owner_name']}
                        </h2>
                    </div>
                    <div style= 'position: absolute; top: 202mm; width: 210mm;' class='issued-date-container'>

                        <div class='issued-date-row' style='display: flex; font-size: 25px;'>

                            <div class='issued-date-col1' style='margin-left: 50mm; width: 45mm; text-align: center;'>
                                <span>
                                    <b> {$day} </b>
                                </span>
                            </div>
                            <div class='issued-date-col2' style='margin-left: 10mm; width: 40mm; text-align:center'>
                               <b> {$date_month} </b>
                            </div>

                            <div class='issued-date-col2' style='margin-left: 10mm; width: 30mm; text-align:center'>
                             <b> {$date_year} </b>
                             </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>";
        
    }

    if ($data['certificate_type'] == "2024 ICMC Induction Certificate of Membership (Member Cadre)") {
        $image = WP_PLUGIN_URL . "/certificate-generator/assets/Member 2024.png";
        $date_month = date('F', strtotime($data['issue_date']));
        $date_year = date('y', strtotime($data['issue_date']));
        //echo var_dump($date_month);
        $day = str_replace(" ",", ", date('jS', strtotime($data['issue_date'])));
        $code = $data['certificate_code'];
        $html = "
        <div style='min-width:210mm; min-height: 297mm; position:absolute; left: 100000px ' id='cert-container' >
            <div style='padding: 0; margin: 0; width:210mm; height: 297mm; position: absolute;' id='cert-doc' data-val='$code'>
                <div>
                    <img src='$image' id='image-template' style='width: 100%; height: 100%' class='image-template'>
                    <div class='owner-name' style='width: 100%; position: absolute; top: 123mm; text-align: center; font-size: 35px;'>
                        <h2 style=' padding-left: 30mm; font-size: 40px; padding-right: 35mm; font-family: 'Trebuchet MS', Tahoma, san-serif;'>
                            {$data['owner_name']}
                        </h2>
                    </div>
                    <div style= 'position: absolute; top: 202mm; width: 210mm;' class='issued-date-container'>

                        <div class='issued-date-row' style='display: flex; font-size: 25px;'>

                            <div class='issued-date-col1' style='margin-left: 50mm; width: 45mm; text-align: center;'>
                                <span>
                                    <b> {$day} </b>
                                </span>
                            </div>
                            <div class='issued-date-col2' style='margin-left: 10mm; width: 40mm; text-align:center'>
                               <b> {$date_month} </b>
                            </div>

                            <div class='issued-date-col2' style='margin-left: 10mm; width: 30mm; text-align:center'>
                             <b> {$date_year} </b>
                             </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>";
        
    }

    if ($data['certificate_type'] == "2024 ICMC Induction Certificate of Membership (Associate Cadre)") {
        $image = WP_PLUGIN_URL . "/certificate-generator/assets/Associate 2024.png";
        $date_month = date('F', strtotime($data['issue_date']));
        $date_year = date('y', strtotime($data['issue_date']));
        //echo var_dump($date_month);
        $day = str_replace(" ",", ", date('jS', strtotime($data['issue_date'])));
        $code = $data['certificate_code'];
        $html = "
        <div style='min-width:210mm; min-height: 297mm; position:absolute; left: 100000px ' id='cert-container' >
            <div style='padding: 0; margin: 0; width:210mm; height: 297mm; position: absolute;' id='cert-doc' data-val='$code'>
                <div>
                    <img src='$image' id='image-template' style='width: 100%; height: 100%' class='image-template'>
                    <div class='owner-name' style='width: 100%; position: absolute; top: 123mm; text-align: center; font-size: 35px;'>
                        <h2 style=' padding-left: 30mm; font-size: 40px; padding-right: 35mm; font-family: 'Trebuchet MS', Tahoma, san-serif;'>
                            {$data['owner_name']}
                        </h2>
                    </div>
                    <div style= 'position: absolute; top: 202mm; width: 210mm;' class='issued-date-container'>

                        <div class='issued-date-row' style='display: flex; font-size: 25px;'>

                            <div class='issued-date-col1' style='margin-left: 50mm; width: 45mm; text-align: center;'>
                                <span>
                                    <b> {$day} </b>
                                </span>
                            </div>
                            <div class='issued-date-col2' style='margin-left: 10mm; width: 40mm; text-align:center'>
                               <b> {$date_month} </b>
                            </div>

                            <div class='issued-date-col2' style='margin-left: 10mm; width: 30mm; text-align:center'>
                             <b> {$date_year} </b>
                             </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>";
        
    }

    if ($data['certificate_type'] == "2023 Certificate of Attendance") {
        $image = WP_PLUGIN_URL . "/certificate-generator/assets/2023Attendance.png";
        $date_month = date('F Y', strtotime($data['issue_date']));
        //echo var_dump($date_month);
        $day = str_replace(" ",", ", date('dS', strtotime($data['issue_date'])));
        $code = $data['certificate_code'];
        $html = "
        <div style='min-width:297mm; min-height: 210mm; position:absolute; left: 100000px ' id='cert-container' >
            <div style='padding: 0; margin: 0; width:297mm; height: 210mm; position: absolute;' id='cert-doc' data-val='$code'>
                <div>
                    <img src='$image' id='image-template' style='width: 100%; height: 100%' class='image-template'>
                    <div class='owner-name' style='width: 100%; position: absolute; top: 75mm; text-align: center; font-size: 35px;'>
                        <h2 style=' padding-left: 30mm; font-size: 40px; padding-right: 35mm; font-family: 'Trebuchet MS', Tahoma, san-serif;'>
                            {$data['owner_name']}
                        </h2>
                    </div>
                    <div style= 'position: absolute; top: 140mm; width: 297mm;' class='issued-date-container'>

                        <div class='issued-date-row' style='display: flex; font-size: 30px;'>

                            <div class='issued-date-col1' style='margin-left: 100mm; width: 55mm; text-align: center;'>
                                <span>
                                  
                                </span>
                            </div>
                            <div class='issued-date-col2' style='margin-left: 45mm; width: 50mm; text-align:center'>
                             
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>";
    }

    if ($data['certificate_type'] == "Certificate of Dues Membership") {
        $image = WP_PLUGIN_URL . "/certificate-generator/assets/Certificate of Dues Membership.png";
        $date_month = date('F d', strtotime($data['issue_date']));
        $day = date('Y', strtotime($data['issue_date']));
        $code = $data['certificate_code'];
        $html = "
        <div style='min-width:210mm; min-height: 297mm; position:absolute; left: 100000px ' id='cert-container' >
            <div style='padding: 0; margin: 0; width:210mm; height: 297mm; position: absolute;' id='cert-doc' data-val='$code'>
                <div>
                    <img src='$image' id='image-template' style='width: 100%; height: 100%' class='image-template'>
                    <div class='owner-name' style='width: 100%; position: absolute; top: 164mm; text-align: center; font-size: 35px;'>
                        <h2 style=' padding-left: 25mm; font-size: 40px; padding-right: 25mm; font-family: 'Trebuchet MS', Tahoma, san-serif;'>
                            {$data['owner_name']}
                        </h2>
                    </div>
                    <div style= 'position: absolute; top: 140mm; width: 210mm;' class='issued-date-container'>

                        <div class='issued-date-row' style='display: flex; font-size: 30px;'>

                            <div class='issued-date-col1' style='margin-left: 90mm; width: 55mm; text-align: center;'>
                                <span>

                                </span>
                            </div>
                            <div class='issued-date-col2' style='margin-left: 45mm; width: 50mm; text-align:center'>

                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>";
    }

    
    if ($data['certificate_type'] == "Certificate of Participation") {
        $image = WP_PLUGIN_URL . "/certificate-generator/assets/Certificate_of_Participation.jpg";
        $date_month = date('F d', strtotime($data['issue_date']));
        $day = date('Y', strtotime($data['issue_date']));
        $code = $data['certificate_code'];
        $html = "
        <div style='min-width:210mm; min-height: 297mm; position:absolute; left: 100000px' id='cert-container' >
            <div style='padding: 0; margin: 0; width:210mm; height: 297mm; position: absolute;' id='cert-doc' data-val='$code'>
                <div>
                    <img src='$image' id='image-template' style='width: 100%; height: 100%' class='image-template'>
                    <div class='owner-name' style='width: 100%; position: absolute; top: 160mm; text-align: center; font-size: 35px;'>
                        <h2 style=' padding-left: 30mm; padding-right: 35mm; font-family: 'Trebuchet MS', Tahoma, san-serif;'>
                            {$data['owner_name']}
                        </h2>
                    </div>
                    <div style= 'position: absolute; top: 140mm; width: 297mm;' class='issued-date-container'>

                        <div class='issued-date-row' style='display: flex; font-size: 30px;'>

                            <div class='issued-date-col1' style='margin-left: 90mm; width: 55mm; text-align: center;'>
                                <span>

                                </span>
                            </div>
                            <div class='issued-date-col2' style='margin-left: 45mm; width: 50mm; text-align:center'>

                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>";
    }

    return $html;
}

function load_plugin()
{

    if (is_admin() && get_option('Activated_Plugin') == 'cert-gen') {

        delete_option('Activated_Plugin');

        /* do stuff once right after activation */
        // example: add_action( 'init', 'my_init_function' );
    }
}
function jal_install()
{
    global $wpdb;
    global $jal_db_version;

    $table_name = $wpdb->prefix . 'ma_certificates';

    $charset_collate = $wpdb->get_charset_collate();
    if ($wpdb->get_var("show tables like '$table_name'") != $table_name) {
        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            issue_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            owner_name text NOT NULL,
            owner_email varchar(256) NOT NULL,
            certificate_code varchar(50) NOT NULL,
            certificate_type varchar(50) NOT NULL,
            assset_url varchar(50) NULL,
            batch_id mediumint(9) NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        dbDelta($sql);

    }
    $table_name = $wpdb->prefix . 'ma_certificate_batches';
    $charset_collate = $wpdb->get_charset_collate();
    if ($wpdb->get_var("show tables like '$table_name'") != $table_name) {
        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            batch_name varchar(50) NOT NULL,
            is_sent TINYINT DEFAULT 0,
            shedule_date datetime NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        dbDelta($sql);

    }

    add_option('jal_db_version', $jal_db_version);
}

function jal_install_data()
{
    global $wpdb;

    $welcome_name = 'Mr. WordPress';
    $welcome_text = 'Congratulations, you just completed the installation!';

    $table_name = $wpdb->prefix . 'ma_certificates';

    $wpdb->insert(
        $table_name,
        array(
            'time' => current_time('mysql'),
            'name' => $welcome_name,
            'text' => $welcome_text,
        )
    );
}

function certificate_generator_scripts()
{
    wp_enqueue_style("ma-bootstrap", 'https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css', false, null, 'all');
    wp_enqueue_style("ma-style", WP_PLUGIN_URL . "/certificate-generator/assets/css/style.css", false, null, 'all');
    wp_enqueue_script("ma-bootstrap-js", 'https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js', array('jquery'), false, true);
    wp_enqueue_script('ma-js-pdf', 'https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js');
    wp_enqueue_script('ma-html2canvas', 'https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js');
    wp_enqueue_script('ma-htmlpdf', 'https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.8.1/html2pdf.bundle.min.js');
    wp_enqueue_script('ma-custom', WP_PLUGIN_URL . "/certificate-generator/assets/js/script.js", array('jquery'), false, true);

}

function certficate_generate_form($atts)
{
    $show_message = false;
    $message = '';
    $validation_class = 'text-light p-3 ';
    $validation = [];
    $template = null;

    if (isset($_POST['cert_code'])) {
        $show_message = true;
        $validation = validate_generate_form();
        if (array_key_exists('errors', $validation) && count($validation['errors']) > 0) {
            $message = join('<br>', $validation['errors']);
            $validation_class .= 'bg-danger';
        } else {

            $message = "Certificate gotten successfully. Click download to get your certificate";
            $validation_class .= 'bg-success';
            $template = get_document_template($validation['success'][0]);
        }

    }

    $html .= $show_message ? "<div class='" . $validation_class . "'>" . $message . "</div>" : "";

    $html .= "<form method='post'><div class='mb-3'>

                <label for='cert_coade' class='form-label'>Certificate Code</label>
                <input type='text' class='form-control' placeholder='insert your code here' id='cert_code' name='cert_code'>
            </div>
            <div class='text-center'>
            <button type='submit' class='btn btn-primary'>
                Check
            </button>
            </div>

        </form>";
    if ($template != null) {
        $html .= "<div>
                <button class='btn btn-danger' id='cert-download' data-val-type='{$validation['success'][0]['certificate_type']}'>Download</button>
            </div>";
    }
    $html .= $template;
    return $html;
}

function validate_generate_form()
{
    $message = [];
    if ($_POST['cert_code'] == '') {
        $message['errors'][] = 'The certificate code field is required';
    } else {
        $cert = get_certificate($_POST['cert_code']);
        if ($cert != null) {
            $message['success'] = $cert;
        } else {
            $message['errors'][] = 'certficate not found';
        }

    }

    return $message;
}
function get_all_certificate_bactches()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'ma_certificate_batches';
    $batches = $wpdb->get_results($wpdb->prepare(
        "
            SELECT *
            FROM  $table_name
        "
    ),
        OBJECT
    );
    return $batches;
}
function get_certificate($code)
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'ma_certificates';
    $certficate = $wpdb->get_results($wpdb->prepare(
        "
            SELECT *
            FROM $table_name
            WHERE certificate_code = %s
        ",
        $code
    ),
        ARRAY_A
    );
    return $certficate;
}

add_action('admin_menu', 'certficate_generator_plugin_setup_menu');

function certficate_generator_plugin_setup_menu()
{
    // add_menu_page($page_title:string,$menu_title:string,$capability:string,$menu_slug:string,$callback:callable,$icon_url:string,$position:integer|float|null )
    // add_submenu_page($parent_slug:string,$page_title:string,$menu_title:string,$capability:string,$menu_slug:string,$callback:callable,$position:integer|float|null )
    add_menu_page('Test Plugin Page', 'Certficate Csv Upload', 'manage_options', 'test-plugin', 'test_init');
    add_submenu_page('test-plugin', "Uploaded Certificate", "certficate batches", 'manage_options', "cert-batches", 'get_certificate_batches');
}
function get_certificate_by_batch()
{

}
function get_certificate_batches()
{
    $batches = get_all_certificate_bactches();
    $body = '';
    if ($batches && count($batches) > 0) {
        foreach ($batches as $key => $batch) {
            # code...
            $batch_name = $batch->batch_name;
            $is_sent = $batch->is_sent == 0 ? "No" : "Yes";
            $body .= "<tr>
                        <td>
                            {$batch_name}
                        </td>
                        <td>
                            {$is_sent}
                        </td>
                        <td>
                            <a href='#' class='btn btn-primary cert_batch_view' data-val='$batch->id'>
                                View
                            </a>
                            <a href='#' class='btn btn-danger cert_batch_send' data-val='$batch->id'>
                                Send
                            </a>
                        </td>
                    </tr>";
        }
    }

    echo "<div class='row'>
            <div class='col-md-5'>
               <h2> Uploaded certficates </h2>

                <table class='table'>
                    <thead>
                        <tr>
                            <th scope='col'>
                                Batch name
                            </th>


                            <th scope='col'>
                            Is sent
                            </th>

                            <th scope='col'>
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        {$body}
                    </tbody>
                </table>
            </div>
            <div class='col-md-7'>
            <table class='table'>
                <thead>
                    <tr>
                        <th scope='col'>
                           Owner Email
                        </th>


                        <th scope='col'>
                            Owner Name
                        </th>

                        <th scope='col'>
                            Cerficate Type
                        </th>
                        <th> Issued date </th>
                        <th> Action </th>
                    </tr>
                </thead>
                <tbody id='cert_batch_detail_body'>

                </tbody>

        </table>
        </div>
        <div class='modal' tabindex='-1' role='dialog'>

        <div class='modal-dialog' role='document'>
          <div class='modal-content'>
            <div class='modal-header'>
              <h5 class='modal-title'>Edit Certificate Details</h5>
              <button type='button' class='modal-close close' data-dismiss='modal' aria-label='Close'>
                <span aria-hidden='true'>&times;</span>
              </button>
            </div>
            <div class='modal-body'>
            <div class='bg-danger text-light p-4' id='div-cert-error'>
            </div>
            <input type='hidden' id='cert_id' value='0'>
            <input type='hidden' id='batch_id' value='0'>
            <div id='div-email' class='row mb-3'>
                <div class='col-md-12'>
                    <label class='form-label col-md-12' for='owner_name'>Owner Name</label>
                    <input class='form-control' type='text' placeholder='Owner Full name' name='owner_name' id='owner_name'>
                </div>
            </div>
            <div id='div-email' class='row mb-3'>
                <div class='col-md-12'>
                    <label class='form-label col-md-12' for='email'>Owner Email</label>
                    <input class='form-control' type='email' placeholder='Email Address' name='owner_email' id='owner_email'>
                </div>
            </div>
            <div id='div-email' class='row mb-3'>
                <div class='col-md-12'>
                    <label class='form-label col-md-12' for='email'>Certificate Type</label>
                    <input class='form-control' type='text' placeholder='Certificate Type' name='certificate_type' id='certificate_type'>
                </div>
            </div>
            <div id='div-email' class='row mb-3'>
                <div class='col-md-12'>
                    <label class='form-label col-md-12' for='issue_date'>issue_date</label>
                    <input class='form-control' type='date' placeholder='Issue Date' name='issue_date' id='issue_date' min='2013-01-01'>
                </div>
            </div>
            </div>

            <div class='modal-footer'>
              <button type='button' class='btn btn-primary' id='cert-save-data'><span class='cert-loader'>
              </span> Save </button>
            </div>
          </div>
        </div>
      </div>
        ";
}

function test_init()
{
    $error = '';
    if (isset($_FILES['cert_csv'])) {
        if ($_FILES['cert_csv']['type'] != 'text/csv') {
            echo "<div class= 'text-danger text-center'> The uploaded file format is incorrect </div>";
        } else {
            $message = certificate_csv();
            if (isset($message['errors'])) {
                $msg = $message['errors'];
                echo "<div class= 'text-danger text-center'> " . $msg . " </div>";
            } else {
                $msg = $message['success'];
                echo "<div class= 'text-success text-center'>" . $msg . " </div>";
            }
        }
    }
    echo "<form method='post' enctype='multipart/form-data'>
        <div class='form-group p-4'>
        <div class= 'text-danger text-center'> By clicking on the upload button this save the data of the owner of the certificate </div>
        <div class='p-4'>
        <label for='exampleInputEmail1' class='form-label'>Csv File</label>
            <input class='form-control' type='file' name='cert_csv'>
            <button type='submit' class='btn btn-primary m-2'>
            Upload
        </button>
        </div>

        </div>
    </form>";

}

function certificate_csv()
{
    $file_tmp = $_FILES['cert_csv']['tmp_name'];

    $file_name = time() . '.csv';

    //echo WP_PLUGIN_DIR . "/certificate-generator/uploads/" . $file_name;
    if (move_uploaded_file($file_tmp, WP_PLUGIN_DIR . "/certificate-generator/uploads/" . $file_name)) {
        $uploaded_file = file(WP_PLUGIN_DIR . "/certificate-generator/uploads/" . $file_name);

        $loop = 1;
        $lines = $uploaded_file;
        $batch_id = create_batch();
        if (count($lines) > 1) {
            foreach ($lines as $idx => $line) {
                $data = explode(',', $line);
                if ($idx > 0) {
                    saveUserData($data, $batch_id);
                }

            }
            return $message = ['success' => "Names uploaded successfully"];
        } else {
            return $message = ['errors' => "The uploaded csv file is empty"];
        }

    } else {
        return $message = ['errors' => "couldn't upload file"];
    }

}

function saveUserData($data, $batch_id)
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'ma_certificates';
    $certficate_code = md5(time());
    $code = "CR-" . substr(str_shuffle($certficate_code), 0, 8);
    $wpdb->insert(
        $table_name,
        array(
            'owner_name' => $data[0],
            'owner_email' => $data[1],
            'certificate_type' => $data[2],
            'issue_date' => date('Y-m-d', strtotime(trim($data[3], '/r/n'))),
            'certificate_code' => $code,
            'batch_id' => $batch_id
        )
    );

    //$code = 1;
    //generate_cert_pdf();
    //($data, $code);
}

function checkEmail($email)
{
    $find1 = strpos($email, '@');
    $find2 = strpos($email, '.');
    return ($find1 !== false && $find2 !== false && $find2 > $find1);
}

function create_batch()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'ma_certificate_batches';
    $bach_name = "batch-" . time();

    $wpdb->insert(
        $table_name,
        array(
            'batch_name' => $bach_name,
        )
    );

    return $wpdb->insert_id;
}

function send_email($data, $code)
{
    require 'vendor/autoload.php';

    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);
    //var_dump($data);
    try {
         //Server settings
        //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP(); //Send using SMTP
        $mail->Host = "localhost";//'mail.icmcng.org'; //Set the SMTP server to send through
        $mail->SMTPAuth = true; //Enable SMTP authentication
        $mail->Username = null;//"memberservices@icmcng.org"; //SMTP username
        $mail->Password = null;//"medACT123!"; //SMTP password
        $mail->SMTPSecure = null;//PHPMailer::ENCRYPTION_SMTPS; //Enable implicit TLS encryption
        $mail->Port = 1025;//465; //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
        $email = $data['owner_email'];

        //var_dump($email);
        //Recipients
        $mail->setFrom('noreply@icmcng.org', 'icmcng');
        $mail->addAddress($email, $data['owner_name']); //Add a recipient
        //$mail->addAddress('joshua@icmcng.org');               //Name is optional
        $mail->addReplyTo('info@icmcng.org', 'icmcng.org');
        // $mail->addCC('cc@example.com');
        //$mail->addBCC('bcc@example.com');

        //Attachments
        /*      $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    *///Optional name
        // $image_path = WP_PLUGIN_URL . "/certificate-generator/assets/emeka.png";
        $content = "Dear {$data['owner_name']}, <br/><br/>
        Greetings from the Institute of Chartered Mediators and Conciliators (ICMC).<br/><br/>
        Once again, thank you for attending the Historic 2022 ICMC ADR Conference.
        The Institute is pleased to Inform you that your {$data['certificate_type']} is ready for download. kindly click <a href='https://www.icmcng.org/certificate'>here</a> and use  the code <strong> {$data['certificate_code']}</strong> to download your certificate.<br/><br/>
        Thank you and warm regards.
        ";
        $non_html_content = "Dear {$data['owner_name']}, \n\n
        Greetings from the Institute of Chartered Mediators and Conciliators (ICMC).\n\n
        Once again, thank you for attending the Historic 2022 ICMC ADR Conference.
        The Institute is pleased to Inform you that your {$data['certificate_type']} for download. kindly click <a href='icmcng.org/certificate'>here</a> and use  the code <strong> {$data['certificate_code']}</strong> to download your certificate.\n\n
        Thank you and warm regards.";
        //Content
        $subject = 'ICMC 2022 ADR Conference Certificate of Attendance';

        if($data['certificate_type'] == "Membership Certificate for Fellow Cadre"){
            $subject = "2022 ICMC Induction Certificate of Membership (Fellow Cadre)";
             $content = "Dear {$data['owner_name']}, <br/><br/>
        Greetings from the Institute of Chartered Mediators and Conciliators (ICMC).<br/><br/>
        Once again, congratulations on your successful induction into the Institute.<br/><br/>
        We are pleased to Inform you that your E-copy {$data['certificate_type']} is ready, kindly click <a href='https://www.icmcng.org/certificate'>HERE</a> using this code <strong> {$data['certificate_code']}</strong>.<br/><br/>
        Welcome to ICMC. <br><br> 
        Thank you and warm regards.";
        }

        if($data['certificate_type'] == "2023 ICMC Induction Certificate of Membership (Fellow Cadre)"){
            $subject = "2023 ICMC Induction Certificate of Membership (Fellow Cadre)";
             $content = "Dear {$data['owner_name']}, <br/><br/>
             Greetings from the Institute of Chartered Mediators and Conciliators (ICMC.<br/><br/>
             Once again, congratulations on your successful induction into the Institute.<br/><br/>
            We are pleased to Inform you that your E-copy {$data['certificate_type']} is ready, kindly click <a href='https://www.icmcng.org/certificate'>HERE</a> using this code <strong> {$data['certificate_code']}</strong>.<br/><br/>
            Welcome to ICMC. <br><br> 
            Thank you and warm regards.";
        }
        if($data['certificate_type'] == "Membership Certificate for Associate Cadre"){
            $subject = "2022 ICMC Induction Certificate of Membership (Associate Cadre)";
            
                    $content = "Dear {$data['owner_name']}, <br/><br/>
        Greetings from the Institute of Chartered Mediators and Conciliators (ICMC).<br/><br/>
        Once again, congratulations on your successful induction into the Institute.<br/><br/>
        We are pleased to Inform you that your E-copy {$data['certificate_type']} is ready, kindly click <a href='https://www.icmcng.org/certificate'>HERE</a> using this code <strong> {$data['certificate_code']}</strong>.<br/><br/>
        Welcome to ICMC. <br><br> 
        Thank you and warm regards.
        ";
        }
        if($data['certificate_type'] == "2023 ICMC Induction Certificate of Membership (Associate Cadre)"){
            $subject = "2023 ICMC Induction Certificate of Membership (Associate Cadre)";
             $content = "Dear {$data['owner_name']}, <br/><br/>
             Greetings from the Institute of Chartered Mediators and Conciliators (ICMC.<br/><br/>
             Once again, congratulations on your successful induction into the Institute.<br/><br/>
            We are pleased to Inform you that your E-copy {$data['certificate_type']} is ready, kindly click <a href='https://www.icmcng.org/certificate'>HERE</a> using this code <strong> {$data['certificate_code']}</strong>.<br/><br/>
            Welcome to ICMC. <br><br> 
            Thank you and warm regards.";
        }

        if($data['certificate_type'] == "Membership Certificate for Associate Cadre 2023"){
            $subject = "2023 ICMC Induction Certificate of Membership (Associate Cadre)";
            
                    $content = "Dear {$data['owner_name']}, <br/><br/>
        Greetings from the Institute of Chartered Mediators and Conciliators (ICMC).<br/><br/>
        Once again, congratulations on your successful induction into the Institute.<br/><br/>
        We are pleased to Inform you that your E-copy {$data['certificate_type']} is ready, kindly click <a href='https://www.icmcng.org/certificate'>HERE</a> using this code <strong> {$data['certificate_code']}</strong>.<br/><br/>
        Welcome to ICMC. <br><br> 
        Thank you and warm regards.
        ";
        }

        if($data['certificate_type'] == "Membership Certificate for Member Cadre"){
            $subject = "2022 ICMC Induction Certificate of Membership (Member Cadre)";
            
             $content = "Dear {$data['owner_name']}, <br/><br/>
        Greetings from the Institute of Chartered Mediators and Conciliators (ICMC).<br/><br/>
        Once again, congratulations on your successful induction into the Institute.<br/><br/>
        We are pleased to Inform you that your E-copy {$data['certificate_type']} is ready, kindly click <a href='https://www.icmcng.org/certificate'>HERE</a> using this code <strong> {$data['certificate_code']}</strong>.<br/><br/>
        Welcome to ICMC. <br><br> 
        Thank you and warm regards.";
            
        }

        if($data['certificate_type'] == "2023 ICMC Induction Certificate of Membership (Member Cadre)"){
            $subject = "2023 ICMC Induction Certificate of Membership (Member Cadre)";
             $content = "Dear {$data['owner_name']}, <br/><br/>
             Greetings from the Institute of Chartered Mediators and Conciliators (ICMC.<br/><br/>
             Once again, congratulations on your successful induction into the Institute.<br/><br/>
            We are pleased to Inform you that your E-copy {$data['certificate_type']} is ready, kindly click <a href='https://www.icmcng.org/certificate'>HERE</a> using this code <strong> {$data['certificate_code']}</strong>.<br/><br/>
            Welcome to ICMC. <br><br> 
            Thank you and warm regards.";
        }
        if($data['certificate_type'] == "2023 ICMC Induction Certificate of Membership (Fellow Cadre)"){
            $subject = "2023 ICMC Induction Certificate of Membership (Fellow Cadre)";
             $content = "Dear {$data['owner_name']}, <br/><br/>
             Greetings from the Institute of Chartered Mediators and Conciliators (ICMC.<br/><br/>
             Once again, congratulations on your successful induction into the Institute.<br/><br/>
            We are pleased to Inform you that your E-copy {$data['certificate_type']} is ready, kindly click <a href='https://www.icmcng.org/certificate'>HERE</a> using this code <strong> {$data['certificate_code']}</strong>.<br/><br/>
            Welcome to ICMC. <br><br> 
            Thank you and warm regards.";
        }

        if($data['certificate_type'] == "2024 ICMC Induction Certificate of Membership (Fellow Cadre)"){
            $subject = "2024 ICMC Induction Certificate of Membership (Fellow Cadre)";
             $content = "Dear {$data['owner_name']}, <br/><br/>
             Greetings from the Institute of Chartered Mediators and Conciliators (ICMC.<br/><br/>
             Once again, congratulations on your successful induction into the Institute.<br/><br/>
            We are pleased to Inform you that your E-copy {$data['certificate_type']} is ready, kindly click <a href='https://www.icmcng.org/certificate'>HERE</a> using this code <strong> {$data['certificate_code']}</strong>.<br/><br/>
            Welcome to ICMC. <br><br> 
            Thank you and warm regards.";
        }

        if($data['certificate_type'] == "2024 ICMC Induction Certificate of Membership (Member Cadre)"){
            $subject = "2024 ICMC Induction Certificate of Membership (Member Cadre)";
             $content = "Dear {$data['owner_name']}, <br/><br/>
             Greetings from the Institute of Chartered Mediators and Conciliators (ICMC.<br/><br/>
             Once again, congratulations on your successful induction into the Institute.<br/><br/>
            We are pleased to Inform you that your E-copy {$data['certificate_type']} is ready, kindly click <a href='https://www.icmcng.org/certificate'>HERE</a> using this code <strong> {$data['certificate_code']}</strong>.<br/><br/>
            Welcome to ICMC. <br><br> 
            Thank you and warm regards.";
        }

        if($data['certificate_type'] == "2024 ICMC Induction Certificate of Membership (Associate Cadre)"){
            $subject = "2024 ICMC Induction Certificate of Membership (Associate Cadre)";
             $content = "Dear {$data['owner_name']}, <br/><br/>
             Greetings from the Institute of Chartered Mediators and Conciliators (ICMC.<br/><br/>
             Once again, congratulations on your successful induction into the Institute.<br/><br/>
            We are pleased to Inform you that your E-copy {$data['certificate_type']} is ready, kindly click <a href='https://www.icmcng.org/certificate'>HERE</a> using this code <strong> {$data['certificate_code']}</strong>.<br/><br/>
            Welcome to ICMC. <br><br> 
            Thank you and warm regards.";
        }
        
        if($data['certificate_type'] == "Certificate of Dues Membership"){
            $subject = "CERTIFICATE OF ANNUAL MEMBERSHIP SUBSCRIPTION.";

            $content = "Dear {$data['owner_name']}, <br/><br/>
            Thank you for paying your annual membership dues! <br/><br/>
            We truly appreciate your commitment and dedication to staying connected with the Institute, your adherence enables us to continue providing excellent services, resources, and opportunities for our members <br/><br/>
            As a member, you'll now have access to exclusive privileges and opportunities, such as special discounts, invitations to special events, free monthly webinars, featuring in our weekly MedICON and more! <br/><br/>
            To participate in our MedICON, kindly send a portrait picture to enable us to feature you. Please send your picture to <a href='mailto:joshua@icmcng.org'>joshua@icmcng.org</a>. 
            <br/><br/>

            To download your Membership Subscription Certificate, kindly click <a href='https://www.icmcng.org/certificate'>HERE</a>, then insert this code <strong> {$data['certificate_code']} </strong> to complete the download. 
            <br/><br/>
            Thank you, best regards.

            ";

            $non_html_content = "
            Dear {$data['owner_name']}, \n\n
            Thank you for paying your annual membership dues! \n\n
            We truly appreciate your commitment and dedication to staying connected with the Institute, your adherence enables us to continue providing excellent programming, resources, and opportunities for our members. \n\n
            As a member, you'll now have access to exclusive privileges and opportunities, such as special discounts, invitations to special events, free monthly webinars, featuring in our weekly MedICON and more! \n\n
            To participate in our MedICON, kindly send a portrait picture to enable us to feature you. Please send your picture to joshua@icmcng.org. 
            \n\n
            To download your Membership Subscription Certificate, kindly click HERE, then insert this code (code here) to complete the download. 
            Thank you, best regards.
            ";
        }

        if($data['certificate_type'] == "Certificate of Participation"){
            $subject = "Certificate of Participation";

            $content = "Dear {$data['owner_name']}, <br/><br/>
            Greetings from the Institute of Chartered Mediators and Conciliators (ICMC). <br/><br/>
            On behalf of the ICMC Training Faculty, I thank you for participating in the Institute's Mediation Skills Accreditation and Certification Training.<br><br>
            Kindly visit our website <a href='https://www.icmcng.org/certificate'>HERE</a> and use the code  <strong> {$data['certificate_code']} </strong> to download your certificate of attendance. It is noteworthy to state that this Certificate is not and cannot pass for an Accreditation Certificate.

            
            You can download it using <a href='https://www.icmcng.org/certificate'>Link</a> provided and this  code <strong> {$data['certificate_code']} </strong> to download it
            <br/><br/>
            We are committed to providing high-quality events and resources to our community, and we are thrilled to have had the opportunity to connect with you at this workshop. We look forward to seeing you at future ICMC events and continuing to work together to advance the field of dispute resolution.
            <br/><br/>
            Thank you again for your participation and support.
            <br/><br/>
            Best regards.

            ";

            $non_html_content = 
            "Dear {$data['owner_name']},\n\n
            We would like to express our sincere gratitude for your attendance at the inaugural ICMC workshop for Heads of Multidoor Court Houses and Private Dispute Resolution Centers. Your presence was greatly appreciated and we hope that you found the workshop to be both informative and engaging\n\n
            As a small token of our appreciation, we would like to offer you a certificate of participation. You can download it using <a href='https://www.icmcng.org/certificate'>Link</a> provided and this  code <strong> {$data['certificate_code']} </strong> to download it
           \n\n
            We are committed to providing high-quality events and resources to our community, and we are thrilled to have had the opportunity to connect with you at this workshop. We look forward to seeing you at future ICMC events and continuing to work together to advance the field of dispute resolution.
           \n\n
            Thank you again for your participation and support.
           \n\n
            Best regards.

            ";
        }

        if($data['certificate_type'] == "2023 Certificate of Attendance"){
            $subject = "Certificate of Attendance";

            $content = "Dear {$data['owner_name']}, <br/><br/>
            Greetings from the Institute of Chartered Mediators and Conciliators (ICMC). <br/><br/>
            On behalf of the ICMC Training Faculty, I thank you for participating in the Institute's Mediation Skills Accreditation and Certification Training.<br><br>
            Kindly visit our website <a href='https://www.icmcng.org/certificate'>HERE</a> and use the code  <strong> {$data['certificate_code']} </strong> to download your certificate of attendance. It is noteworthy to state that this Certificate is not and cannot pass for an Accreditation Certificate.
            <br><br>

            Please, notify us if your name has been misspelled, kindly contact:  <a mailto='icmctrainings@gmail.com'>icmctrainings@gmail.com</a>  
            <br><br>

            Please ensure that you have sent your Post Course Assignments to records@icmcng.org and copy icmctrainings@gmail.com. The deadline for submission is two weeks after the training (kindly ignore if you have sent yours). Please it is important that you send your assignment to only this email address. State your name and date of your training when sending in your assignments. If you have turned in your assignment to the correct email, then you do not need to do so again.


            In view of spreading the advocacy of Mediation, Conciliation and Alternative Dispute Resolution, the ICMC Training Faculty is inviting you to nominate one (1) individual who you strongly believe will benefit from our Mediation Skills Accreditation and Certification Training.
            <br>
            <h3> MY ICMC MEDIATION STORY </h3> 
            <br>
            The Institute would love to hear how the Mediation Skills Accreditation and Certification training has impacted your life. Please, let us know how this training has enriched your life; possibly how it has changed your interactions with your spouse, siblings, and colleagues, or how you have been able to resolve a particularly stubborn dispute using your newly acquired Mediation Skills. <br><br>
            You may follow the Institute on our various social media pages in order to keep up with our activities. You can find ICMC on Instagram: @icmcng; Twitter: @icmcng; Facebook: ICMC Nigeria and; LinkedIn: ICMC Nigeria
            <br><br>
            Thank you.
            <br><br>
            Best Regards.

            ";

            $non_html_content = 
            "Dear {$data['owner_name']},\n\n
            Greetings from the Institute of Chartered Mediators and Conciliators (ICMC).\n\n
            On behalf of the ICMC Training Faculty, I thank you for participating in the Institute's Mediation Skills Accreditation and Certification Training.<br><br>
            Kindly visit our website <a href='https://www.icmcng.org/certificate'>HERE</a> and use the code  <strong> {$data['certificate_code']} </strong> to download your certificate of attendance. It is noteworthy to state that this Certificate is not and cannot pass for an Accreditation Certificate.
            \n\n

            Please, notify us if your name has been misspelled, kindly contact:  <a mailto='icmctrainings@gmail.com'>icmctrainings@gmail.com</a>  
            \n\n

            Please ensure that you have sent your Post Course Assignments to records@icmcng.org and copy icmctrainings@gmail.com. The deadline for submission is two weeks after the training (kindly ignore if you have sent yours). Please it is important that you send your assignment to only this email address. State your name and date of your training when sending in your assignments. If you have turned in your assignment to the correct email, then you do not need to do so again.


            In view of spreading the advocacy of Mediation, Conciliation and Alternative Dispute Resolution, the ICMC Training Faculty is inviting you to nominate one (1) individual who you strongly believe will benefit from our Mediation Skills Accreditation and Certification Training.
            \n\n
            <h3> MY ICMC MEDIATION STORY <h3> \n\n

            \n\n

            You may follow the Institute on our various social media pages in order to keep up with our activities. You can find ICMC on Instagram: @icmcng; Twitter: @icmcng; Facebook: ICMC Nigeria and; LinkedIn: ICMC Nigeria
            \n\n
            Thank you.
            \n\n
            Best Regards.";
        }

        if($data['certificate_type'] == "Certificate of Attendance"){
            $subject = "Certificate of Attendance";

            $content = "Dear {$data['owner_name']}, <br/><br/>
            Greetings from the Institute of Chartered Mediators and Conciliators (ICMC). <br/><br/>
            On behalf of the ICMC Training Faculty, I thank you for participating in the Institute's Mediation Skills Accreditation and Certification Training.<br><br>
            Kindly visit our website <a href='https://www.icmcng.org/certificate'>HERE</a> and use the code  <strong> {$data['certificate_code']} </strong> to download your certificate of attendance. It is noteworthy to state that this Certificate is not and cannot pass for an Accreditation Certificate.
            <br><br>

            Please, notify us if your name has been misspelled, kindly contact:  <a mailto='icmctrainings@gmail.com'>icmctrainings@gmail.com</a>  
            <br><br>

            Please ensure that you have sent your Post Course Assignments to records@icmcng.org and copy icmctrainings@gmail.com. The deadline for submission is two weeks after the training (kindly ignore if you have sent yours). Please it is important that you send your assignment to only this email address. State your name and date of your training when sending in your assignments. If you have turned in your assignment to the correct email, then you do not need to do so again.


            In view of spreading the advocacy of Mediation, Conciliation and Alternative Dispute Resolution, the ICMC Training Faculty is inviting you to nominate one (1) individual who you strongly believe will benefit from our Mediation Skills Accreditation and Certification Training.
            <br>
            <h3> MY ICMC MEDIATION STORY </h3> 
            <br>
            The Institute would love to hear how the Mediation Skills Accreditation and Certification training has impacted your life. Please, let us know how this training has enriched your life; possibly how it has changed your interactions with your spouse, siblings, and colleagues, or how you have been able to resolve a particularly stubborn dispute using your newly acquired Mediation Skills. <br><br>
            You may follow the Institute on our various social media pages in order to keep up with our activities. You can find ICMC on Instagram: @icmcng; Twitter: @icmcng; Facebook: ICMC Nigeria and; LinkedIn: ICMC Nigeria
            <br><br>
            Thank you.
            <br><br>
            Best Regards.

            ";

            $non_html_content = 
            "Dear {$data['owner_name']},\n\n
            Greetings from the Institute of Chartered Mediators and Conciliators (ICMC).\n\n
            On behalf of the ICMC Training Faculty, I thank you for participating in the Institute's Mediation Skills Accreditation and Certification Training.<br><br>
            Kindly visit our website <a href='https://www.icmcng.org/certificate'>HERE</a> and use the code  <strong> {$data['certificate_code']} </strong> to download your certificate of attendance. It is noteworthy to state that this Certificate is not and cannot pass for an Accreditation Certificate.
            \n\n

            Please, notify us if your name has been misspelled, kindly contact:  <a mailto='icmctrainings@gmail.com'>icmctrainings@gmail.com</a>  
            \n\n

            Please ensure that you have sent your Post Course Assignments to records@icmcng.org and copy icmctrainings@gmail.com. The deadline for submission is two weeks after the training (kindly ignore if you have sent yours). Please it is important that you send your assignment to only this email address. State your name and date of your training when sending in your assignments. If you have turned in your assignment to the correct email, then you do not need to do so again.


            In view of spreading the advocacy of Mediation, Conciliation and Alternative Dispute Resolution, the ICMC Training Faculty is inviting you to nominate one (1) individual who you strongly believe will benefit from our Mediation Skills Accreditation and Certification Training.
            \n\n
            <h3> MY ICMC MEDIATION STORY <h3> \n\n

            \n\n

            You may follow the Institute on our various social media pages in order to keep up with our activities. You can find ICMC on Instagram: @icmcng; Twitter: @icmcng; Facebook: ICMC Nigeria and; LinkedIn: ICMC Nigeria
            \n\n
            Thank you.
            \n\n
            Best Regards.";
        }

        $mail->isHTML(true); //Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body = $content;
        $mail->AltBody = $non_html_content;

        $mail->send();
        // echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

function send_certficates($data)
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'ma_certificates';
    $certficates = $wpdb->get_results($wpdb->prepare(
        "
                SELECT *
                FROM $table_name
                WHERE batch_id = %d
            ",
        $data["id"]
    ),
        ARRAY_A
    );

    foreach ($certficates as $key => $certificate) {
        # code...
        send_email($certificate, $certficate['cert_code']);
    }
    $id = $data["id"];
    $table_name = $wpdb->prefix . 'ma_certificate_batches';
    $wpdb->query($wpdb->prepare("UPDATE $table_name SET is_sent='1' WHERE id=$id"));

    $res = array('message' => "certificates sent successfully", 'data' => $certficates, 'success' => true);
    // Create the response object
    $response = new WP_REST_Response($res);

    // Add a custom status code
    $response->set_status(200);

    return $response;
}

function fetch_certficates($data)
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'ma_certificates';
    $certficates = $wpdb->get_results($wpdb->prepare(
        "
                SELECT *
                FROM $table_name
                WHERE batch_id = %d
            ",
        $data["id"]
    ),
        ARRAY_A
    );

    $res = array('message' => "certificates sent successfully", 'data' => $certficates, 'success' => true);
    // Create the response object
    $response = new WP_REST_Response($res);

    // Add a custom status code
    $response->set_status(201);

    return $response;
}

function fetch_certificate($data)
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'ma_certificates';
    $certficates = $wpdb->get_results($wpdb->prepare(
        "
                SELECT *
                FROM $table_name
                WHERE batch_id = %d
            ",
        $data["id"]
    ),
        ARRAY_A
    );

    $res = array('message' => "certificates fetched successfully", 'data' => $certficates, 'success' => true);
    // Create the response object
    $response = new WP_REST_Response($res);

    // Add a custom status code
    $response->set_status(201);

    return $response;
}

function update_certificate($data)
{
    /*  global $wpdb;
    $certficates = $wpdb->get_results($wpdb->prepare(
    "
    SELECT *
    FROM wp_ma_certificates
    WHERE batch_id = %d
    ",
    $data["id"]
    ),
    ARRAY_A
    ); */

    $res = array('message' => "certificates fetched successfully", 'data' => $data, 'success' => true);
    // Create the response object
    $response = new WP_REST_Response($res);

    // Add a custom status code
    $response->set_status(201);

    return $response;
}

add_action('rest_api_init', function () {
    register_rest_route('/cert/v1', '/send_mails/(?P<id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'send_certficates',
    ));
});

add_action('rest_api_init', function () {
    register_rest_route('/cert/v1', '/fetch_certficates/(?P<id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'fetch_certficates',
    ));
});

add_action('rest_api_init', function () {
    register_rest_route('/cert/v1', '/fetch_certificate/(?P<id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'fetch_certificate',
    ));
});

add_action('rest_api_init', function () {
    register_rest_route('cert/v1', 'update_certificate', array(
        'methods' => 'POST',
        'callback' => 'update_certificate')
    );
});

add_shortcode('cert-form', 'certficate_generate_form');

add_action("wp_enqueue_scripts", "certificate_generator_scripts");
add_action('admin_enqueue_scripts', 'certificate_generator_scripts');

add_action('admin_init', 'load_plugin')
?>
<?php

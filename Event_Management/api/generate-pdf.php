<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["id"]) || !isset($_SESSION["role"])) {
    header("Location: ../pages/login.php");
    exit;
}

$registration_id = $_GET["registration_id"] ?? $_GET["id"] ?? '';
$user_id = $_SESSION["id"];
$user_role = $_SESSION["role"] ?? 'user';

if (empty($registration_id) || !is_numeric($registration_id)) {
    die("Registration ID is required.");
}

$registration_id = (int)$registration_id;

require_once __DIR__ . "/../config/connection.php";

// Fetch registration and event details (Admins can view any registration, users can view their own)
if ($user_role === 'admin') {
    $sql = "SELECT re.id AS registration_id, re.phone, re.status, u.name AS user_name,
        u.email AS user_email, e.title AS event_title, c.category_name, e.venus, e.event_date
        FROM register_events re
        LEFT JOIN users u ON re.user_id = u.id
        LEFT JOIN events e ON re.event_id = e.id
        LEFT JOIN categories c ON e.category_id = c.id
        WHERE re.id = ?";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $registration_id);
} else {
    $sql = "SELECT re.id AS registration_id, re.phone, re.status, u.name AS user_name,
        u.email AS user_email, e.title AS event_title, c.category_name, e.venus, e.event_date
        FROM register_events re
        LEFT JOIN users u ON re.user_id = u.id
        LEFT JOIN events e ON re.event_id = e.id
        LEFT JOIN categories c ON e.category_id = c.id
        WHERE re.id = ? AND re.user_id = ?";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $registration_id, $user_id);
}

mysqli_stmt_execute($stmt);

$res = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($res) === 0) {
    die("Registration record not found.");
}

$data = mysqli_fetch_assoc($res);

// Only allow PDF confirmation for approved registrations
if ($data["status"] !== "approved") {
    die("Confirmation PDF is only available for approved registrations.");
}

// Load Dompdf autoloader
if (file_exists(__DIR__ . "/../vendor/autoload.php")) {
    require_once __DIR__ . "/../vendor/autoload.php";
} elseif (file_exists("C:/xampp/htdocs/Learning_PHP/PHP_mailer/vendor/autoload.php")) {
    require_once "C:/xampp/htdocs/Learning_PHP/PHP_mailer/vendor/autoload.php";
} elseif (file_exists(__DIR__ . "/../../PHP_mailer/vendor/autoload.php")) {
    require_once __DIR__ . "/../../PHP_mailer/vendor/autoload.php";
} else {
    die("Dompdf library not found.");
}

use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);

// Format HTML exactly as required
$html = '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registration Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #212529;
            margin: 30px 40px;
            font-size: 13px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
            color: #0d6efd;
            font-size: 22px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .divider {
            border: 0;
            border-top: 2px solid #333;
            margin: 15px 0 20px 0;
        }
        .section-header {
            font-size: 14px;
            font-weight: bold;
            color: #495057;
            margin-top: 15px;
            margin-bottom: 8px;
            text-transform: uppercase;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }
        th, td {
            padding: 8px 12px;
            border: 1px solid #ced4da;
            vertical-align: middle;
        }
        th {
            width: 30%;
            background-color: #f8f9fa;
            color: #495057;
            font-weight: bold;
            text-align: left;
        }
        td {
            color: #212529;
        }
        .badge-approved {
            display: inline-block;
            padding: 4px 12px;
            background-color: #198754;
            color: #ffffff;
            font-weight: bold;
            border-radius: 4px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 11px;
            color: #6c757d;
            border-top: 1px dashed #ced4da;
            padding-top: 10px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>Registration Confirmation</h2>
    </div>

    <hr class="divider">

    <div class="section-header">User Information</div>
    <table>
        <tr>
            <th>Name</th>
            <td>' . htmlspecialchars($data['user_name']) . '</td>
        </tr>
        <tr>
            <th>Email</th>
            <td>' . htmlspecialchars($data['user_email']) . '</td>
        </tr>
        <tr>
            <th>Phone</th>
            <td>' . htmlspecialchars($data['phone']) . '</td>
        </tr>
    </table>

    <div class="section-header">Event Details</div>
    <table>
        <tr>
            <th>Event</th>
            <td>' . htmlspecialchars($data['event_title']) . '</td>
        </tr>
        <tr>
            <th>Category</th>
            <td>' . htmlspecialchars($data['category_name'] ?? 'General') . '</td>
        </tr>
        <tr>
            <th>Venue</th>
            <td>' . htmlspecialchars($data['venus']) . '</td>
        </tr>
        <tr>
            <th>Event Date</th>
            <td>' . htmlspecialchars($data['event_date'] ?? '-') . '</td>
        </tr>
    </table>

    <div class="section-header">Registration Status</div>
    <table>
        <tr>
            <th>Registration Status</th>
            <td><span class="badge-approved">Approved</span></td>
        </tr>
    </table>

    <div class="footer">
        <p>Registration ID: #' . htmlspecialchars($data['registration_id']) . ' | Confirmation generated on ' . date('Y-m-d H:i:s') . '</p>
    </div>

</body>
</html>
';

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Output the generated PDF (Attachment => 0 allows preview and download in browser)
$filename = "Registration_Confirmation_" . $registration_id . ".pdf";
$dompdf->stream($filename, ["Attachment" => 0]);

<?php

$conn = mysqli_connect("localhost", "root", "", "emp_db");

if (!$conn) {
    die("Connection Failed" . mysqli_connect_error());
}

require("./vendor/autoload.php");

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

$spreadsheet = new Spreadsheet();

//export data
if (isset($_POST["export-btn"])) {
    $ext = $_POST["export_file_type"];
    $filename = "emp_file_" . time();

    $sql = "SELECT * FROM employees";
    $stmt_exp = mysqli_prepare($conn, $sql);

    mysqli_stmt_execute($stmt_exp);
    $res_exp = mysqli_stmt_get_result($stmt_exp);

    if (mysqli_num_rows($res_exp) > 0) {

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'id');
        $sheet->setCellValue('B1', 'username');
        $sheet->setCellValue('C1', 'email');
        $sheet->setCellValue('D1', 'phone');
        $sheet->setCellValue('E1', 'salary');
        $sheet->setCellValue('F1', 'role');

        $count = 2;

        foreach ($res_exp as $data) {

            $sheet->setCellValue('A' . $count, $data["id"]);
            $sheet->setCellValue('B' . $count, $data["username"]);
            $sheet->setCellValue('C' . $count, $data["email"]);
            $sheet->setCellValue('D' . $count, $data["phone"]);
            $sheet->setCellValue('E' . $count, $data["salary"]);
            $sheet->setCellValue('F' . $count, $data["role"]);
            $count++;
        }

        if ($ext === "xlsx") {
            $writer = new Xlsx($spreadsheet);
            $final_file_name = $filename . '.xlsx';

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename = "' . urlencode($final_file_name) . '"');
            $writer->save('php://output');
            exit;
        } else if ($ext === "xls") {
            $writer = new Xls($spreadsheet);
            $final_file_name = $filename . '.xls';

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename = "' . urlencode($final_file_name) . '"');
            $writer->save('php://output');
            exit;
        } else if ($ext === "csv") {
            $writer = new Csv($spreadsheet);
            $final_file_name = $filename . '.csv';

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename = "' . urlencode($final_file_name) . '"');
            $writer->save('php://output');
            exit;
        }
    } else {
        echo "<script>alert('Data not found in export');
        window.location.href='excel.php';
        </script>";
    }
}

if (isset($_POST["submit"])) {

    $file = $_FILES["import_file"];
    $allowed_ext = ["xls", "xlsx", "csv"];
    $ext  = pathinfo($file["name"], PATHINFO_EXTENSION);


    if (in_array($ext, $allowed_ext)) {

        $file_path = $_FILES["import_file"]["tmp_name"];
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file_path); //load the file in this path
        $data = $spreadsheet->getActiveSheet()->toarray(); //check the active spreadsheet and convert data into array
?>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
        <div class="container mt-5">
            <table class="table table-bordered  table-striped-columns" border="1" cellspacing="0" cellpadding="10px">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Salary</th>
                        <th>Role</th>
                    </tr>
                </thead>

                <?php
                $count = 0;
                foreach ($data as $row) {

                    if ($count > 0) {

                        $id = $row["0"];
                        $username = $row["1"];
                        $password = password_hash($row["2"], PASSWORD_DEFAULT);
                        $email = $row["3"];
                        $phone = $row["4"];
                        $salary = $row["5"];
                        $role = $row["6"];
                ?>

                        <tbody>
                            <tr>
                                <td><?php echo $id; ?></td>
                                <td><?php echo $username; ?></td>
                                <td><?php echo $email; ?></td>
                                <td><?php echo $phone; ?></td>
                                <td><?php echo $salary; ?></td>
                                <td><?php echo $role; ?></td>
                            </tr>
                        </tbody>

                <?php
                        $check_student = "SELECT id FROM employees WHERE id='$id'"; //check excel id match to table id
                        $stmt_student = mysqli_prepare($conn, $check_student);

                        mysqli_stmt_execute($stmt_student);

                        $res_student = mysqli_stmt_get_result($stmt_student);

                        //if id match then excel data to update in the table
                        if (mysqli_num_rows($res_student) > 0) {
                            $update = "UPDATE employees SET username='$username',password='$password',email='$email',
                            phone ='$phone',salary='$salary',role='$role' WHERE id='$id'";

                            $stmt_update = mysqli_prepare($conn, $update);
                            mysqli_stmt_execute($stmt_update);

                            mysqli_stmt_close($stmt_update);
                        } else {

                            $insert = "INSERT INTO employees (username,password,email,phone,salary,role)
                            VALUES ('$username','$password','$email','$phone','$salary','$role')";

                            $stmt_insert = mysqli_prepare($conn, $insert);

                            if (!mysqli_stmt_execute($stmt_insert)) {
                                echo "<script>
                                alert('File not imported');
                                window.location.href = 'excel.php';
                                </script>";
                                exit;
                            }
                            mysqli_stmt_close($stmt_insert);
                        }
                    } else {
                        $count = 1;
                    }
                }
                ?>

            </table>
        </div>

<?php
        echo "<script>
              alert('File imported successfully');
              </script>";
        exit;
    } else {
        echo "<script>
            alert('Invalid file format');
            window.location.href = 'excel.php';
            </script>";
        exit;
    }
}
?>
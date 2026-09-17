<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <title>Excel Data Import & Export</title>
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body class="p-3 m-0 border-0 bd-example m-0 border-0">
    <div class="container mt-5">
        <div class="card shadow mx-auto" style="max-width: 500px;">

            <div class="card-header">
                <h4 class="mb-0">Excel Data Import</h4>
            </div>

            <div class="card-body">

                <form action="upload.php" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="file" class="form-label">
                            Import Excel Data
                        </label>

                        <input type="file" name="import_file" class="form-control">
                        <buttontype="submit" name="submit" class="btn btn-primary mt-3">Upload / Import
                            </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow mx-auto mt-4" style="width: 500px;">

            <div class="card-header">
                <h4 class="mb-0">Export Data</h4>
            </div>

            <div class="card-body">
                <form action="upload.php" method="POST">
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-6">
                                <select name="export_file_type" class="form-control w-100">
                                    <option value="select Format">select Format</option>
                                    <option value="xlsx">xlsx</option>
                                    <option value="xls">xls</option>
                                    <option value="csv">csv</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <button type="submit" name="export-btn" class="btn btn-primary">Export</button>
                            </div>
                </form>
            </div>
        </div>
    </div>
    </div>
    </div>
</body>

</html>
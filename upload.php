<?php
/**
 * Constants
 */
define('ALLOWED_FORMAT', ['image/jpeg', 'image/png', 'image/gif']);
define('UPLOAD_DIR', './uploads/');
define('MAX_FILE_SIZE', 1000000);
/**
 * Variables
 */
$extension = '';
$uploadFile = '';
$originName = '';
$mime = '';
$size = 0;
$errors = '';
/**
 * Delete picture
 */
if(isset($_GET['delete']) && $_GET['delete'] === 'true' && isset($_GET['file']) && !empty($_GET['file']))
{
    
    if(file_exists($_GET['file']))
    {
        unlink($_GET['file']);
        header('Location: /upload.php');
    }
}
/**
 * Testing the pictures if the form is submited
 */
if(isset($_FILES['pictures']) && !empty($_FILES['pictures']))
{
    foreach($_FILES['pictures']['tmp_name'] as $index => $tmpName)
    {
        $originName = $_FILES['pictures']['name'][$index];
        $mime = $_FILES['pictures']['type'][$index];
        $size = $_FILES['pictures']['size'][$index];
        if($size > MAX_FILE_SIZE) $errors .= '<br><span style="color:red">L\'image "'.$originName.'" est trop lourde !</span><br>';
        if(!in_array($mime, ALLOWED_FORMAT)) $errors .= '<br><span style="color:red">Le fichier "'. $originName .'" n\'est pas au bon format.</span><br>';

        if($size < MAX_FILE_SIZE && in_array($mime, ALLOWED_FORMAT))
        {
            $extension = implode(array_slice(explode('/', $mime), 1, 1));
            $uploadFile = UPLOAD_DIR . uniqid() . '.' . $extension;
            move_uploaded_file($tmpName, $uploadFile);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WCS - Odyssey - PHP : Laisse pas traîner ton file</title>
</head>
<body>
    <h1>Importes tes images</h1>
    <form action="#" method="post" enctype="multipart/form-data">
        <label for="pictures">Sélectionnes les images à uploader</label>    
        <input type="file" name="pictures[]" id="pictures" multiple="multiple"/>
        <?= $errors ?>
        <button>Send</button>
    </form>
    <br><br>
    <h2>Liste des images uploadées :</h2>
    <ul>
        <?php
        $pictures = new FilesystemIterator(UPLOAD_DIR);
        if(!empty($pictures->getFilename())){
            foreach ($pictures as $file)
            {?>
            <li>
                <figure>
                    <img src="<?= UPLOAD_DIR . $pictures->getFilename() ?>" alt="<?= $pictures->getFilename() ?>">
                    <figcaption><?= $pictures->getFilename() ?></figcaption>
                    <a href="?delete=true&file=<?= $pictures->getFileInfo()?>">Delete</a>
                </figure>
            </li>
            <?php }
        } else{ ?>
            <h2>Pas d'image uploadés</h2>
        <?php } ?>
    </ul>
</body>
</html>
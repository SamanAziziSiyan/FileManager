<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

</body>

</html>

<?php
include "functions.php";
?>
<div class="top-bar">
    <form action="" method="POST">
        <select name="select" id="slc">
            <option value="d:/">D Drive</option>
            <option value="c:/"> C Drive</option>
            <option value="f:/">F Drive</option>
            <option value="m:/">M Drive</option>
        </select>
        <input type="submit" name="btn" value="رفتن به">
    </form>
</div>
<?php

// checks if the delete btn has been clicked
if (isset($_GET['rm'])) {
    $file2Remove = $_GET['rm'];
    if (is_dir($file2Remove)) {
        rmDirectory($file2Remove);
        echo "<div class='msg'>Directory $file2Remove and its content successfully deleted ...</div>";
    } else {
        @unlink($file2Remove);
        echo "<div class='msg'>File $file2Remove successfully deleted ...</div>";
    }
}

if (isset($_GET['dir'])) {
    $currDir = $_GET['dir'];
} else {
    $currDir = "";
}

if (isset($_POST['btn'])) {
    switch ($_POST['select']) {
        case "c:/":
            $currDir = "c:/";
            break;
        case "f:/":
            $currDir = "f:/";
            break;
        case "d:/":
            $currDir = "d:/";
            break;
        case "m:/":
            $currDir = "m:/";
            break;
        default:
            $currDir = "";
            break;
    }
}
// Get the last char of file or folder
if (substr($currDir, strlen($currDir) - 1) != "/") {
    $currDir .= '/';
}


echo '<h3>List of files in folder : <span style="color:brown">' . $currDir . '</span></h3>';
echo '<ul>';

// Go Back Btn
echo '<li class="folder back">';
$parentDir = dirname($currDir);
echo '<span class="filename"><a href="?dir=' . $parentDir . '"> Go Back to ' . str_replace($currDir, '', $parentDir) . "</a></span>";
echo '</li>';


// load all files and folders
foreach (glob($currDir . '*') as $filename) {
    $fileFormat = '';
    if (is_dir($filename)) {
        $type = 'folder';
    } else {
        $type = 'file';
        // Get the File Extension
        $dotPosition = strrpos($filename, ".");
        if ($dotPosition !== false) {
            $fileFormat = substr($filename, $dotPosition + 1);
        }
    }
    echo '<li class="' . $type . " $fileFormat " . '">';

    // list the folders and files in a cool way
    if (is_dir($filename)) {
        echo '<span class="filename"><a href="?dir=' . $filename . '">' . str_replace($currDir, '', $filename) . "</a></span>";
    } else {
        echo '<span class="filename">' . str_replace($currDir, '', $filename) . "</span>";
    }


    // Loading the delete btn For all Files and Folders
    echo '<span class="actions">';
    echo "<a class = 'delete' href='?dir={$currDir}&rm={$filename}' " . ' onclick="return confirm(\'Are you sure to remove ' . $filename . ' \')" ' . "> delete </a>";

    //    checks if fileformat is txt or php or html or htm and it will add an edit btn to it
    if (in_array($fileFormat, array('txt', 'php', 'html', 'htm', 'css'))) {
        echo "<a class='edit' href='?dir={$currDir}&edit={$filename}' > &nbsp; &nbsp;  edit </a>";
    }
    echo '</span>';
    // Gets the file size by GetNiceFilesize Function
    echo '<span class="infos">';
    if (is_file($filename)) {
        echo getNiceFileSize($filename);
    } 
    echo '</span>';
    echo '</li>';
}
echo '</ul>';


// write entered Data By user
if (isset($_POST['filepath'], $_POST['filecontent'])) {
    file_put_contents($_POST['filepath'], $_POST['filecontent']);
    echo "<div class='msg'>File {$_POST['filepath']} successfully saved ...</div>";

}

// if edit btn clicked
if (isset($_GET['edit'])) {
    $file2edit = $_GET['edit'];
    if (!file_exists($file2edit)) {
        echo "<div class='msg'><b>Error :</b> File $file2edit not exists !</div>";
    } else {
        echo "Editing file $file2edit : <br>";
?>
        <form action="" method="post" id="editFrm">
            <textarea name="filecontent" cols="120" rows="30"><?php echo file_get_contents($file2edit); ?></textarea><br>
            <input name="filepath" type="hidden" value="<?php echo $file2edit; ?>" />
            <input type="submit" name="" value=" Save " id="btn-save">
        </form>

<?php
    }
}

?>

</body>

</html>
<h2><?php _e('Pauker Settings', 'pauker'); ?></h2>
<?php
require_once('pauker-includes.php');


function saveFile($name) {
        if (!file_exists(PAUKERUPLOADPATH)) {
                mkdir(PAUKERUPLOADPATH, 0700);
        }
        if (!move_uploaded_file($_FILES[$name]['tmp_name'], PAUKERUPLOADPATH.PAUKERFILE.".tmp")) {
                return(_e('Upload failed.', 'pauker'));
        }
        $gzFile = gzopen(PAUKERUPLOADPATH.PAUKERFILE.".tmp", "r");
	if (!$gzFile) {
		return(_e('Not a valid file.', 'pauker'));
	}
        $data = gzread($gzFile, 20*filesize(PAUKERUPLOADPATH.PAUKERFILE.".tmp"));
        gzclose($gzFile);
	if (!simplexml_load_string($data)) {
		return(_e('Not a valid file.', 'pauker'));
	}
        unlink(PAUKERUPLOADPATH.PAUKERFILE.".tmp");
        $destFile = fopen(PAUKERUPLOADPATH.PAUKERFILE, 'w');
        fwrite($destFile, $data);
        fclose($destFile);
	return(_e('Changes saved.', 'pauker'));
}
if (isset($_POST['sent'])) {
?>
<div id="message" class="updated fade">
	<p>
		<strong><?php saveFile('pauker-file'); ?></strong>
	</p>
</div>
<?php
}
?>
<form action="" method="post" enctype="multipart/form-data">
	<input type="hidden" name="sent" value="1" />
	<p>
		<label for="pauker-file"><?php _e('Pauker file:', 'pauker'); ?></label>
		<input type="file" name="pauker-file" id="pauker-file" />
	</p>
	<p>
		<input type="submit" value="<?php _e('Save Changes', 'pauker'); ?>" name="Submit" />
	</p>
</form>
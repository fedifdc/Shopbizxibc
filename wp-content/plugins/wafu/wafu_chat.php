<?php
if (!defined('WAFU_SCRIPT')) { die();  exit; }
?> 
<div class="wrap">
<h1 class="wp-heading-inline">WAFU Auto Chat</h1>
<?php 
if (isset($_POST['keyword']) && $_POST['keyword'] != '' && isset($_POST['jawaban']) && $_POST['jawaban'] != '') {
	$chat_key = trim(strtolower($_POST['keyword']));
	if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
		$idkey = $wpdb->get_var("SELECT `chat_id` FROM `wafu_chat` WHERE `chat_key`='".$chat_key."' AND `chat_id`!=".$_GET['edit']);
	} else {
		$idkey = $wpdb->get_var("SELECT `chat_id` FROM `wafu_chat` WHERE `chat_key`='".$chat_key."'");
	}

	if (is_numeric($idkey)) {
		echo '
		<div class="notice notice-error is-dismissible">
			<p>Keyword sudah dipakai sebelumnya, gunakan keyword lain.</p>
		</div>';
	} else {
		if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
			$wpdb->query("UPDATE `wafu_chat` SET `chat_key`='".$chat_key."', `chat_jawab`='".sanitize_textarea_field($_POST['jawaban'])."' WHERE `chat_id`=".$_GET['edit']);			
		} else {
			$wpdb->query("INSERT INTO `wafu_chat` (`chat_key`,`chat_jawab`) VALUES ('".$chat_key."','".sanitize_textarea_field($_POST['jawaban'])."')");			
		}
		echo '
		<div class="notice notice-success is-dismissible">
			<p>Keyword telah disimpan.</p>
		</div>';
	}
} elseif (isset($_GET['del']) && is_numeric($_GET['del'])) {
	$wpdb->query("DELETE FROM `wafu_chat` WHERE `chat_id`=".$_GET['del']);
	echo '
	<div class="notice notice-success is-dismissible">
		<p>Keyword telah dihapus.</p>
	</div>';
}

if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
	$data = $wpdb->get_row("SELECT * FROM `wafu_chat` WHERE `chat_id`=".$_GET['edit']);
}
 
?>
<form action="" method="post">
	<div class="form-group row">
		<label class="col-sm-2 col-form-label">Keyword</label>
	    <div class="col-sm-5">
		    <input type="text" name="keyword" class="form-control" value="<?php echo $data->chat_key ?? '';?>" required/>
		    <small class="form-text text-muted">Masukkan Keyword Chat. Gunakan huruf kecil semua <br/>
			Gunakan <code>&</code> utk mewajibkan 2/lebih kata. contoh: <code>harga&wafu</code><br/>
			Gunakan <code>|</code> utk pilihan keyword dg jawaban yg sama. contoh: <code>halo|hello|hai</code></small>
	    </div>
	</div>
	<div class="form-group row">
		<label class="col-sm-2 col-form-label">Jawaban</label>
	    <div class="col-sm-5">
		    <textarea name="jawaban" class="form-control" rows="7" required><?php echo $data->chat_jawab ?? '';?></textarea>
		    <small class="form-text text-muted">Masukkan Chat Jawaban.</small>
	    </div>
	</div>
	
	<?php
		wp_nonce_field( 'acme-settings-save', 'acme-custom-message' );
		submit_button('Simpan');
	?>
</form>

<h3>Daftar Keyword</h3>
<table class="table">
	<thead class="thead thead-dark">
		<tr><th width="20%">Keyword</th>
			<th width="60%">Jawaban</th>
			<th width="20%">Action</th>
		</tr>
	</thead>
	<tbody>
		<?php 
		$data = $wpdb->get_results("SELECT * FROM `wafu_chat`");
		if (count($data) > 0) {
			foreach ($data as $data) {
				echo '<tr><td>'.$data->chat_key.'</td>
				<td>'.wafu_pendekin($data->chat_jawab).'</td>
				<td><a href="admin.php?page=wafu_chat&edit='.$data->chat_id.'" class="btn btn-sm btn-success">Edit</a> &nbsp; 
				<a href="admin.php?page=wafu_chat&del='.$data->chat_id.'" class="btn btn-sm btn-danger">Delete</a></td>
				</tr>';
			}
		}
		?>
	</tbody>
</table>
</div>
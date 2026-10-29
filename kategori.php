<?php
require_once "config/database.php"; require_once "config/auth.php"; require_login();
$error="";
if($_SERVER['REQUEST_METHOD']==='POST'){
 $nama=trim($_POST['nama_kategori']??'');
 if($nama){try{$pdo->prepare("INSERT INTO kategori(nama_kategori) VALUES(?)")->execute([$nama]);}catch(Exception $e){$error="Kategori sudah ada.";}}
 header("Location: kategori.php");exit;
}
$rows=$pdo->query("SELECT k.*, COUNT(a.id) jumlah FROM kategori k LEFT JOIN arsip a ON a.kategori_id=k.id GROUP BY k.id ORDER BY k.nama_kategori")->fetchAll();
include "partials/header.php";
?>
<div class="page-head"><div><h2>Kategori Arsip</h2><p class="muted">Kelompokkan dokumen agar mudah ditemukan.</p></div></div>
<div class="card"><form method="post" class="inline-form"><input name="nama_kategori" placeholder="Nama kategori baru" required><button class="btn primary">Tambah</button></form>
<div class="table-wrap"><table><thead><tr><th>Kategori</th><th>Jumlah Arsip</th></tr></thead><tbody>
<?php foreach($rows as $r):?><tr><td><?=htmlspecialchars($r['nama_kategori'])?></td><td><?=$r['jumlah']?></td></tr><?php endforeach;?>
</tbody></table></div></div>
<?php include "partials/footer.php"; ?>

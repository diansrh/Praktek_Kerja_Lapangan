<?php
require_once "config/database.php"; require_once "config/auth.php"; require_login();
$q = trim($_GET['q'] ?? '');
$sql = "SELECT a.*, k.nama_kategori FROM arsip a LEFT JOIN kategori k ON k.id=a.kategori_id";
$params = [];
if ($q !== '') {
    $sql .= " WHERE a.nomor_dokumen LIKE ? OR a.judul LIKE ? OR a.pengirim LIKE ? OR k.nama_kategori LIKE ?";
    $like="%$q%"; $params=[$like,$like,$like,$like];
}
$sql .= " ORDER BY a.created_at DESC";
$stmt=$pdo->prepare($sql); $stmt->execute($params); $rows=$stmt->fetchAll();
include "partials/header.php";
?>
<div class="page-head"><div><h2>Arsip Dokumen</h2><p class="muted">Kelola, cari, dan unduh dokumen digital.</p></div><a class="btn primary" href="arsip_form.php">+ Tambah Arsip</a></div>
<div class="card">
<form class="search" method="get"><input name="q" value="<?=htmlspecialchars($q)?>" placeholder="Cari nomor, judul, pengirim, atau kategori..."><button class="btn">Cari</button><?php if($q): ?><a class="btn" href="arsip.php">Reset</a><?php endif; ?></form>
<div class="table-wrap"><table><thead><tr><th>No.</th><th>Nomor</th><th>Judul</th><th>Kategori</th><th>Tanggal</th><th>Pengirim</th><th>Aksi</th></tr></thead><tbody>
<?php foreach($rows as $r): ?>
<tr><td><?=$r['id']?></td><td><?=htmlspecialchars($r['nomor_dokumen'])?></td><td><?=htmlspecialchars($r['judul'])?></td><td><?=htmlspecialchars($r['nama_kategori'] ?? '-')?></td><td><?=htmlspecialchars($r['tanggal_dokumen'] ?? '-')?></td><td><?=htmlspecialchars($r['pengirim'] ?? '-')?></td>
<td class="actions"><a class="btn small" href="download.php?id=<?=$r['id']?>">Unduh</a><a class="btn small" href="arsip_form.php?id=<?=$r['id']?>">Edit</a><a class="btn small danger-text" href="arsip_hapus.php?id=<?=$r['id']?>" onclick="return confirm('Hapus arsip ini?')">Hapus</a></td></tr>
<?php endforeach; if(!$rows): ?><tr><td colspan="7" class="empty">Data tidak ditemukan.</td></tr><?php endif; ?>
</tbody></table></div></div>
<?php include "partials/footer.php"; ?>

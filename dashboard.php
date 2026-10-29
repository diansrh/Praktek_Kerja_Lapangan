<?php
require_once "config/database.php"; require_once "config/auth.php"; require_login();
$total = $pdo->query("SELECT COUNT(*) FROM arsip")->fetchColumn();
$kategori = $pdo->query("SELECT COUNT(*) FROM kategori")->fetchColumn();
$users = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$recent = $pdo->query("SELECT a.*, k.nama_kategori FROM arsip a LEFT JOIN kategori k ON k.id=a.kategori_id ORDER BY a.created_at DESC LIMIT 8")->fetchAll();
include "partials/header.php";
?>
<div class="page-head"><div><h2>Dashboard</h2><p class="muted">Ringkasan pengelolaan arsip dokumen digital.</p></div>
<a class="btn primary" href="arsip_form.php">+ Tambah Arsip</a></div>
<div class="stats">
<div class="stat"><span>Total Arsip</span><strong><?=$total?></strong></div>
<div class="stat"><span>Kategori</span><strong><?=$kategori?></strong></div>
<div class="stat"><span>Pengguna</span><strong><?=$users?></strong></div>
</div>
<div class="card"><div class="card-head"><h3>Arsip Terbaru</h3><a href="arsip.php">Lihat semua</a></div>
<div class="table-wrap"><table><thead><tr><th>No. Dokumen</th><th>Judul</th><th>Kategori</th><th>Tanggal</th><th>Aksi</th></tr></thead><tbody>
<?php foreach($recent as $r): ?>
<tr><td><?=htmlspecialchars($r['nomor_dokumen'])?></td><td><?=htmlspecialchars($r['judul'])?></td><td><?=htmlspecialchars($r['nama_kategori'] ?? '-')?></td><td><?=htmlspecialchars($r['tanggal_dokumen'] ?? '-')?></td><td><a class="btn small" href="download.php?id=<?=$r['id']?>">Download</a></td></tr>
<?php endforeach; if (!$recent): ?><tr><td colspan="5" class="empty">Belum ada arsip.</td></tr><?php endif; ?>
</tbody></table></div></div>
<?php include "partials/footer.php"; ?>

<?php
require_once "config/database.php"; require_once "config/auth.php"; require_login();
$id=(int)($_GET['id']??0);$s=$pdo->prepare("SELECT * FROM arsip WHERE id=?");$s->execute([$id]);$r=$s->fetch();
if(!$r || !is_file($r['file_path'])) die("File tidak ditemukan.");
$mime=function_exists('mime_content_type')?mime_content_type($r['file_path']):'application/octet-stream';
header("Content-Type: ".$mime); header('Content-Disposition: attachment; filename="'.basename($r['nama_file']).'"'); header("Content-Length: ".filesize($r['file_path']));
readfile($r['file_path']); exit;

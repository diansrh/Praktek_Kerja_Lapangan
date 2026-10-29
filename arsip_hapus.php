<?php
require_once "config/database.php"; require_once "config/auth.php"; require_login();
$id=(int)($_GET['id']??0); $s=$pdo->prepare("SELECT file_path FROM arsip WHERE id=?");$s->execute([$id]);$r=$s->fetch();
if($r){$pdo->prepare("DELETE FROM arsip WHERE id=?")->execute([$id]);if(is_file($r['file_path']))@unlink($r['file_path']);}
header("Location: arsip.php");exit;

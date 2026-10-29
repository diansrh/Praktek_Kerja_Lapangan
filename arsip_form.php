<?php
require_once "config/database.php"; require_once "config/auth.php"; require_login();
$id=(int)($_GET['id'] ?? 0); $edit=$id>0; $error="";
$data=['nomor_dokumen'=>'','judul'=>'','kategori_id'=>'','tanggal_dokumen'=>'','pengirim'=>'','keterangan'=>'','nama_file'=>''];
if($edit){$s=$pdo->prepare("SELECT * FROM arsip WHERE id=?");$s->execute([$id]);$data=$s->fetch();if(!$data)die("Arsip tidak ditemukan.");}
if($_SERVER['REQUEST_METHOD']==='POST'){
    foreach(['nomor_dokumen','judul','tanggal_dokumen','pengirim','keterangan','kategori_id'] as $f) $data[$f]=trim($_POST[$f]??'');
    $file=$_FILES['dokumen']??null; $path=$data['file_path']??'';
    if(!$edit && (!$file || $file['error']!==UPLOAD_ERR_OK)) $error="Dokumen wajib diunggah.";
    $allowed=['pdf','doc','docx','xls','xlsx','ppt','pptx'];
    if(!$error && $file && $file['error']===UPLOAD_ERR_OK){
        $ext=strtolower(pathinfo($file['name'],PATHINFO_EXTENSION));
        if(!in_array($ext,$allowed)) $error="Format file tidak didukung.";
        elseif($file['size']>10*1024*1024) $error="Ukuran file maksimal 10 MB.";
        else{
            if(!is_dir("uploads")) mkdir("uploads",0755,true);
            $new=uniqid("arsip_",true).".".$ext;
            if(move_uploaded_file($file['tmp_name'],"uploads/".$new)) $path="uploads/".$new;
            else $error="Gagal menyimpan file.";
        }
    }
    if(!$error){
        if($edit){
            $sql="UPDATE arsip SET nomor_dokumen=?,judul=?,kategori_id=?,tanggal_dokumen=?,pengirim=?,keterangan=?";
            $params=[$data['nomor_dokumen'],$data['judul'],$data['kategori_id']?:null,$data['tanggal_dokumen']?:null,$data['pengirim'],$data['keterangan']];
            if($file && $file['error']===UPLOAD_ERR_OK){$sql.=",nama_file=?,file_path=?";$params[]=$file['name'];$params[]=$path;}
            $sql.=" WHERE id=?";$params[]=$id;$pdo->prepare($sql)->execute($params);
        } else {
            $pdo->prepare("INSERT INTO arsip(nomor_dokumen,judul,kategori_id,tanggal_dokumen,pengirim,keterangan,nama_file,file_path,uploaded_by) VALUES(?,?,?,?,?,?,?,?,?)")
                ->execute([$data['nomor_dokumen'],$data['judul'],$data['kategori_id']?:null,$data['tanggal_dokumen']?:null,$data['pengirim'],$data['keterangan'],$file['name'],$path,$_SESSION['user']['id']]);
        }
        header("Location: arsip.php");exit;
    }
}
$cats=$pdo->query("SELECT * FROM kategori ORDER BY nama_kategori")->fetchAll();
include "partials/header.php";
?>
<div class="page-head"><div><h2><?=$edit?'Edit Arsip':'Tambah Arsip'?></h2><p class="muted">Isi metadata dokumen dan unggah file.</p></div></div>
<div class="card form-card">
<?php if($error): ?><div class="alert danger"><?=htmlspecialchars($error)?></div><?php endif; ?>
<form method="post" enctype="multipart/form-data">
<div class="grid2"><div><label>Nomor Dokumen *</label><input name="nomor_dokumen" required value="<?=htmlspecialchars($data['nomor_dokumen'])?>"></div>
<div><label>Judul Dokumen *</label><input name="judul" required value="<?=htmlspecialchars($data['judul'])?>"></div>
<div><label>Kategori</label><select name="kategori_id"><option value="">-- Pilih --</option><?php foreach($cats as $c): ?><option value="<?=$c['id']?>" <?=$data['kategori_id']==$c['id']?'selected':''?>><?=htmlspecialchars($c['nama_kategori'])?></option><?php endforeach;?></select></div>
<div><label>Tanggal Dokumen</label><input type="date" name="tanggal_dokumen" value="<?=htmlspecialchars($data['tanggal_dokumen']??'')?>"></div>
<div><label>Pengirim/Sumber</label><input name="pengirim" value="<?=htmlspecialchars($data['pengirim']??'')?>"></div>
<div><label>File Dokumen <?=$edit?'(opsional untuk mengganti)':'*'?></label><input type="file" name="dokumen" <?=$edit?'':'required'?> accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx"><small>PDF/DOC/DOCX/XLS/XLSX/PPT/PPTX, maks. 10 MB.</small></div></div>
<label>Keterangan</label><textarea name="keterangan" rows="5"><?=htmlspecialchars($data['keterangan']??'')?></textarea>
<div class="form-actions"><a class="btn" href="arsip.php">Batal</a><button class="btn primary">Simpan</button></div>
</form></div>
<?php include "partials/footer.php"; ?>

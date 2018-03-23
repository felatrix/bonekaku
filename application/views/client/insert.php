<!DOCTYPE html>
<html>
<head>
    <?php echo $this->load->view('head'); ?>
</head>
<body>

<?php echo $this->load->view('header'); ?>

<div class="container-fluid">
<?php // Change the css classes to suit your needs    

$attributes = array('class' => '', 'id' => '', 'class' => 'form-horizontal');

if($mode == 'insert') {
    echo form_open('client/insert', $attributes);
} else {
    echo form_open('client/insert', $attributes, $hidden);
}
?>
    
<div class="control-group">    
    <div class="text-center text-info col-sm-12"><?php echo validation_errors(); //echo $success; ?></div>
</div>
<div class="control-group row-fluid">
    <label class="offset3 control-label" for="nama">Nama <span class="required">*</span></label>
    <div class="controls">
        <input class="offset1" id="nama" type="text" name="nama" maxlength="100" value="<?php echo set_value('nama'); echo (!empty($data['nama'])) ? $data['nama'] : ''; ?>"  />
    </div>
</div>
    
<div class="control-group row-fluid">
    <label class="control-label offset3" for="jenis_kontak">Jenis Kontak</label>
    <div class="controls">
        <input class="offset1" id="jenis_kontak" type="text" name="jenis_kontak" maxlength="20" value="<?php echo set_value('jenis_kontak'); echo (!empty($data['jeniskontak'])) ? $data['jeniskontak'] : ''; ?>"  />
    </div>
</div>

<div class="control-group row-fluid">
    <label class="control-label offset3" for="nomer">Nomer</label>
    <div class="controls">
        <input class="offset1" id="nomer" type="text" name="nomer" value="<?php echo set_value('nomer'); echo (!empty($data['nomer'])) ? $data['nomer'] : ''; ?>"  />
    </div>
</div>

<div class="control-group row-fluid">
    <label class="control-label offset3" for="alamat">Alamat</label>
    <div class="controls">
        <textarea rows="3" class="offset1" id="alamat" name="alamat"><?php echo set_value('alamat'); echo (!empty($data['alamat'])) ? $data['alamat'] : ''; ?></textarea>
        <!--input class="offset1" id="alamat" type="text" name="alamat" value="<?php echo set_value('alamat'); echo (!empty($data['alamat'])) ? $data['alamat'] : ''; ?>"  /-->
    </div>
</div>
    
<div class="control-group row-fluid">
    <label class="control-label offset3"></label>
    <div class="controls">
    <button type="submit" class="btn btn-primary offset1">Simpan</button>
    </div>
</div>

<?php echo form_close(); ?>
</div>
    
<?php echo $this->load->view('footer'); ?>

</body>
</html>

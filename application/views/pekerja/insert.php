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
    echo form_open('pekerja/insert', $attributes);
} else {
    echo form_open('pekerja/insert', $attributes, $hidden);
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
    <label class="control-label offset3" for="bagian">Bagian <span class="required">*</span></label>
    <?php $options = array(
                        ''  => 'Silahkan pilih',
                        'Cutting'    => 'Cutting',
                        'Finishing'    => 'Finishing',
                        'Pretel'    => 'Pretel',
                        'Sewing'    => 'Sewing'
                    );
            $attr = "class='offset1'";
            $bagian = (!empty(set_value('bagian'))) ? set_value('bagian') : ((!empty($data['jenisactivity'])) ? $data['jenisactivity'] : '');
    ?>
    <div class="controls">
        <?php echo form_dropdown('bagian', $options, $bagian, $attr); ?>
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

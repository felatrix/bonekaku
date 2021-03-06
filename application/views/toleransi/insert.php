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
$ctlr = "toleransi";

if($mode == 'insert') {
    echo form_open($ctlr . '/insert', $attributes);
} else {
    echo form_open($ctlr . '/insert', $attributes, $hidden);
}
?>
    
<div class="control-group">    
    <div class="text-center text-info col-sm-12"><?php echo validation_errors(); echo $success; ?></div>
</div>
        
<div class="control-group row-fluid">
    <label class="offset3 control-label" for="i1">Toleransi kirim (hari)<span class="required">*</span></label>
    <div class="controls">
        <input class="offset1" id="i1" type="text" name="hari" maxlength="11" value="<?php echo set_value('hari'); echo (!empty($data['hari'])) ? $data['hari'] : ''; ?>"  />
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

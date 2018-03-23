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
$ctlr = "bbhn";

if($mode == 'insert') {
    echo form_open($ctlr . '/insert', $attributes);
} else {
    echo form_open($ctlr . '/insert', $attributes, $hidden);
}
?>
    
<div class="control-group">    
    <div class="text-center text-info col-sm-12"><?php echo validation_errors(); //echo $success; ?></div>
</div>
<div class="control-group row-fluid">
    <label class="offset3 control-label" for="input1">Bahan <span class="required">*</span></label>
    <div class="controls">
        <input class="offset1" id="input1" type="text" name="bahan" maxlength="100" value="<?php echo set_value('bahan'); echo (!empty($data['bahan'])) ? $data['bahan'] : ''; ?>"  />
    </div>
</div>
    
<div class="control-group row-fluid">
    <label class="control-label offset3" for="input2">Harga per Yard <span class="required">*</span></label>
    <div class="controls">
        <input class="offset1" id="input2" type="text" name="harga" maxlength="11" value="<?php echo set_value('harga'); echo (!empty($data['harga'])) ? $data['harga'] : ''; ?>"  />
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

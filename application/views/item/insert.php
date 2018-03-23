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
$ctlr = "item";

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
    <label class="offset3 control-label" for="input1">Item Jahitan <span class="required">*</span></label>
    <div class="controls">
        <input class="offset1" id="input1" type="text" name="itemjahitan" maxlength="100" value="<?php echo set_value('itemjahitan'); echo (!empty($data['itemjahitan'])) ? $data['itemjahitan'] : ''; ?>"  />
    </div>
</div>
    
<div class="control-group row-fluid">
    <label class="control-label offset3" for="input2">Min. Performance (item/hari) <span class="required">*</span></label>
    <div class="controls">
        <input class="offset1" id="input2" type="text" name="minperform" maxlength="11" value="<?php echo set_value('minperform'); echo (!empty($data['minperform'])) ? $data['minperform'] : ''; ?>"  />
    </div>
</div>

<div class="control-group row-fluid">
    <label class="control-label offset3" for="input3">Bonus</label>
    <div class="controls">
        <input class="offset1" id="input3" type="text" name="bonus" value="<?php echo set_value('bonus'); echo (!empty($data['bonus'])) ? $data['bonus'] : ''; ?>"  />
    </div>
</div>

<div class="control-group row-fluid">
    <label class="control-label offset3" for="input4">Penalty</label>
    <div class="controls">
        <input class="offset1" id="input4" type="text" name="penalty" value="<?php echo set_value('penalty'); echo (!empty($data['penalty'])) ? $data['penalty'] : ''; ?>"  />
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

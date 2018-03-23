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
$ctlr = "kapasitas";

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
    <label class="control-label offset3" for="bagian">Bagian <span class="required">*</span></label>
    <?php $options = array(
                        ''  => 'Silahkan pilih',
                        'Cutting'    => 'Cutting',
                        'Finishing'    => 'Finishing',
                        'Pretel'    => 'Pretel',
                        'Sewing'    => 'Sewing'
                    );
            $attr = "class='offset1'";
            $bagian = (!empty(set_value('activity'))) ? set_value('activity') : ((!empty($data['activity'])) ? $data['activity'] : '');
    ?>
    <div class="controls">
        <?php echo form_dropdown('activity', $options, $bagian, $attr); ?>
    </div>
</div>
    
<div class="control-group row-fluid">
    <label class="offset3 control-label" for="i1">Kapasitas (pcs/hari)<span class="required">*</span></label>
    <div class="controls">
        <input class="offset1" id="i1" type="text" name="kapasitas" maxlength="11" value="<?php echo set_value('kapasitas'); echo (!empty($data['kapasitas'])) ? $data['kapasitas'] : ''; ?>"  />
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

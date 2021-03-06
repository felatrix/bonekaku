<!DOCTYPE html>
<html>
<head>
    <?php echo $this->load->view('head'); ?>
    <link href="<?php echo base_url().RES_DIR; ?>/bootstrap/css/datepicker.css" rel="stylesheet"/>
</head>
<body>

<?php echo $this->load->view('header'); ?>

<div class="container-fluid">
<?php // Change the css classes to suit your needs    

$attributes = array('class' => '', 'id' => '', 'class' => 'form-horizontal');
$ctlr = "bhnin";

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
    <label class="control-label offset3" for="jenis">Jenis Bahan <span class="required">*</span></label>
    <?php   $attr = "id='jenis' class='offset1'";
            $defval = (!empty(set_value('jenis'))) ? set_value('jenis') : ((!empty($data['bahanid'])) ? $data['bahanid'] : '');
    ?>
    <div class="controls">
        <?php echo form_dropdown('jenis', $bahans, $defval, $attr); ?>
        <?php echo anchor_popup('bbhn/insert', '<i class="icon-plus"></i> Tambah Bahan</a>', array('class'=>'btn'));?>
    </div>
</div>

<div class="control-group row-fluid">
    <label class="offset3 control-label" for="input1">Warna <span class="required">*</span></label>
    <?php   $attr = "id='input1' class='offset1'";
            $defval = (!empty(set_value('warnaid'))) ? set_value('warnaid') : ((!empty($data['warnaid'])) ? $data['warnaid'] : '');
    ?>
    <div class="controls">
        <?php echo form_dropdown('warnaid', $colors, $defval, $attr); ?>
        <?php echo anchor_popup('color/insert', '<i class="icon-plus"></i> Tambah Warna</a>', array('class'=>'btn'));?>
    </div>
</div>
    
<div class="control-group row-fluid">
    <label class="control-label offset3" for="inputp">Panjang (Yard)</label>
    <div class="controls">
        <input class="offset1" id="inputp" type="text" name="panjang" value="<?php echo set_value('panjang'); echo (!empty($data['masuk'])) ? $data['masuk'] : ''; ?>"  />
    </div>
</div>
    
<div class="control-group row-fluid">
    <label class="offset3 control-label" for="tgl">Tgl. Update</label>
    <div class="controls">
        <div class="input-append date offset1" id="dpa" data-date="<?php echo date('Y-m-d'); ?>" data-date-format="yyyy-mm-dd">
            <input type="text" id="tgl" name="tgl" value="<?php echo (!empty(set_value('tgl'))) ? set_value('tgl') : ( (!empty($data['tgl'])) ? $data['tgl'] : date('Y-m-d') ) ?>"  />
            <span class="add-on"><i class="icon-th"></i></span>
        </div>
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
<script src="<?php echo base_url().RES_DIR; ?>/bootstrap/js/bootstrap-datepicker.js"></script>
<script src="<?php echo base_url().RES_DIR; ?>/js/tls.js"></script>

</body>
</html>

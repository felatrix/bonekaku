<!DOCTYPE html>
<html>
<head>
    <?php echo $this->load->view('head'); ?>
</head>
<body>

<?php echo $this->load->view('header'); ?>

<div class="container-fluid">
<div class="tab-content">
<div class="table-responsive">
   <table class="table table-hover">
       <thead>
      <tr>
          <td class="success">Orderid: </td><td class="success"><?php echo $data2['id']; ?></td>
        <td class="warning">Item: </td><td class="warning"><?php echo $data2['itemjahitan']. ' ' .$data2['bahanboneka']. ' ' .$data2['warnaboneka']; ?></td>
        <td class="info">Jumlah: </td><td class="info"><?php echo $data2['jumlah']; ?></td>
      </tr>
      </thead>
   </table>
</div></div></div>
<div class="container-fluid">
<?php // Change the css classes to suit your needs    

$attributes = array('class' => '', 'id' => '', 'class' => 'form-horizontal');

if($mode == 'insert') {
    echo form_open('bhn/insert', $attributes);
} else {
    echo form_open('bhn/insert', $attributes, $hidden);
}
?>
    
<div class="control-group">    
    <div class="text-center text-info col-sm-12"><?php echo validation_errors(); ?></div>
</div>
<!--<div class="control-group row-fluid">
    <label class="control-label offset3" for="jenis">Jenis Bahan <span class="required">*</span></label>
    <div class="controls">
        <input class="offset1" id="jenis" type="text" maxlength="50" name="jenis" value="<?php echo set_value('jenis'); echo (!empty($data['jenis'])) ? $data['jenis'] : ''; ?>"  />
        <input id="oid" type="hidden" name="oid" value="<?php echo set_value('oid'); echo (!empty($data['oid'])) ? $data['oid'] : ''; ?>"  />
    </div>
</div>

<div class="control-group row-fluid">
    <label class="control-label offset3" for="warna">Warna <span class="required">*</span></label>
    <div class="controls">
        <input class="offset1" id="warna" type="text" name="warna" maxlength="20" value="<?php echo (!empty(set_value('warna'))) ? set_value('warna') : ((!empty($data['warna'])) ? $data['warna'] : ''); ?>"  />
    </div>
</div>-->

<div class="control-group row-fluid">
    <label class="control-label offset3" for="jenis">Jenis Bahan <span class="required">*</span></label>
    <?php   $attr = "id='jenis' class='offset1'";
            $defval = (!empty(set_value('jenis'))) ? set_value('jenis') : ((!empty($data['jenis'])) ? $data['jenis'] : '');
    ?>
    <div class="controls">
        <?php echo form_dropdown('jenis', $bahans, $defval, $attr); ?>
        <?php echo anchor_popup('bbhn/insert', '<i class="icon-plus"></i> Tambah Bahan</a>', array('class'=>'btn'));?>
    </div>
</div>
    
<div class="control-group row-fluid">
    <label class="control-label offset3" for="warna">Warna <span class="required">*</span></label>
    <?php   $attr = "id='warna' class='offset1'";
            $defval = (!empty(set_value('warna'))) ? set_value('warna') : ((!empty($data['warna'])) ? $data['warna'] : '');
    ?>
    <div class="controls">
        <?php echo form_dropdown('warna', $colors, $defval, $attr); ?>
        <?php echo anchor_popup('color/insert', '<i class="icon-plus"></i> Tambah Warna</a>', array('class'=>'btn'));?>
    </div>
</div>

<div class="control-group row-fluid">
    <label class="offset3 control-label" for="panjang">Panjang (Yard) <span class="required">*</span></label>
    <div class="controls">
        <input class="offset1" type="text" id="panjang" name="panjang" value="<?php echo set_value('panjang'); echo (!empty($data['panjang'])) ? $data['panjang'] : ''; ?>"  />
    </div>
</div>
    
<div class="control-group row-fluid">
    <label class="control-label offset3"></label>
    <div class="controls">
    <input id="oid" type="hidden" name="oid" value="<?php echo set_value('oid'); echo (!empty($data['oid'])) ? $data['oid'] : ''; ?>"  />
    <button type="submit" class="btn btn-primary offset1">Simpan</button>
    </div>
</div>

<?php echo form_close(); ?>
</div>
    
<?php echo $this->load->view('footer'); ?>
<script src="<?php echo base_url().RES_DIR; ?>/js/update.js"></script>

</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <?php echo $this->load->view('head'); ?>
    <link href="<?php echo base_url().RES_DIR; ?>/bootstrap/css/datepicker.css" rel="stylesheet"/>
</head>
<body>

<?php echo $this->load->view('header'); $ctlr = "tulisan"; ?>

<!--div class="container-fluid">
<div class="tab-content">
<div class="table-responsive">
   <table class="table table-hover">
       <thead>
      <tr>
          <td class="success">Orderid: </td><td class="success"><?php echo $data2['id']; ?></td>
        <td class="warning">Item: </td><td class="warning"><?php echo $data2['itemboneka']; ?></td>
        <td class="info">Jumlah: </td><td class="info"><?php echo $data2['jumlah']; ?></td>
      </tr>
      </thead>
   </table>
</div></div></div-->
<?php echo $this->load->view($ctlr . '/mohead', ['data' => $data2, 'ctlr' => $ctlr]); ?>    
<div class="container-fluid">
<?php // Change the css classes to suit your needs    

$attributes = array('class' => '', 'id' => '', 'class' => 'form-horizontal');

if($mode == 'insert') {
    echo form_open($ctlr . '/insert', $attributes);
} else {
    echo form_open($ctlr . '/insert', $attributes, $hidden);
}
?>
    
<div class="control-group">    
    <div class="text-center text-info col-sm-12"><?php echo validation_errors(); ?></div>
</div>
<div class="control-group row-fluid">
    <label class="control-label offset3" for="kegiatan">Kegiatan <span class="required">*</span></label>
    <div class="controls">
        <input class="offset1" id="kegiatan" type="text" maxlength="50" name="kegiatan" value="<?php echo set_value('kegiatan'); echo (!empty($data['kegiatan'])) ? $data['kegiatan'] : ''; ?>"  />
        <input id="oid" type="hidden" name="oid" value="<?php echo set_value('oid'); echo (!empty($data['oid'])) ? $data['oid'] : ''; ?>"  />
    </div>
</div>

<div class="control-group row-fluid">
    <label class="offset3 control-label" for="antar">Tgl Antar <span class="required">*</span></label>
    <div class="controls">
        <div class="input-append date offset1" id="dpa" data-date="<?php echo date('Y-m-d'); ?>" data-date-format="yyyy-mm-dd">
            <input type="text" id="antar" name="antar" placeholder="yyyy-mm-dd" value="<?php echo set_value('antar'); echo (!empty($data['antar'])) ? $data['antar'] : ''; ?>"  />
            <span class="add-on"><i class="icon-th"></i></span>
        </div>
    </div>
</div>

<div class="control-group row-fluid">
    <label class="offset3 control-label" for="kembali">Tgl Kembali</label>
    <div class="controls">
        <div class="input-append date offset1" id="dpk" data-date="<?php echo date('Y-m-d'); ?>" data-date-format="yyyy-mm-dd">
            <input type="text" id="kembali" name="kembali" placeholder="Di isi setelah kembali" value="<?php echo set_value('kembali'); echo (!empty($data['kembali'])) ? $data['kembali'] : ''; ?>"  />
            <span class="add-on"><i class="icon-th"></i></span>
        </div>
    </div>
</div>
    
<div class="control-group row-fluid">
    <label class="offset3 control-label" for="jumlah">Jumlah</label>
    <div class="controls">
        <input class="offset1" type="text" id="jumlah" name="jumlah" placeholder="Di isi setelah kembali" value="<?php echo set_value('jumlah'); echo (!empty($data['jumlah'])) ? $data['jumlah'] : ''; ?>"  />
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

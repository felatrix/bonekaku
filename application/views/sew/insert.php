<!DOCTYPE html>
<html>
<head>
    <?php echo $this->load->view('head'); ?>
    <link href="<?php echo base_url().RES_DIR; ?>/bootstrap/css/datepicker.css" rel="stylesheet"/>
</head>
<body>

<?php
echo $this->load->view('header'); $ctlr = "sew"; 
$sum_data = $this->sew_model->sum_data_by_oid($data2['oid'], $hidden['coid'], 'jmlsetor');
$sisa = $data2['jml'] - $sum_data['jmlsetor'];
?>

<div class="container-fluid">
<div class="tab-content">
<div class="table-responsive">
   <table class="table table-hover">
       <thead>
      <tr>
        <td class="success">Orderid: </td><td class="success"><?php echo $data2['oid']; ?></td>
        <td class="warning">Item: </td><td class="warning"><?php echo $data2['itemjahitan']. ' ' .$data2['bahanboneka']. ' ' .$data2['warnaboneka']; ?></td>
        <td class="info">Jumlah: </td><td class="info"><?php echo $data2['jml']; ?></td>
        <td class="error">Sisa: </td><td class="error"><?php echo $sisa; ?></td>
      </tr>
      </thead>
   </table>
</div></div></div>
<div class="container-fluid">
<?php // Change the css classes to suit your needs    

$attributes = array('class' => '', 'id' => '', 'class' => 'form-horizontal');

//if($mode == 'insert') {
//    echo form_open($ctlr . '/insert', $attributes);
//} else {
    echo form_open($ctlr . '/insert', $attributes, $hidden);
//}
?>
    
<div class="control-group">    
    <div class="text-center text-info col-sm-12"><?php echo validation_errors(); //echo $success; ?></div>
</div>
<!--<div class="control-group row-fluid">
    <label class="control-label offset3" for="search_nama">Pekerja <span class="required">*</span></label>
    <div class="controls">
        <input class="offset1 typeahead" id="search_nama" autocomplete="off" type="text" name="search_nama" value="<?php echo set_value('search_nama'); echo (!empty($data['nama'])) ? $data['nama'].'-'.$data['jenisactivity'] : ''; ?>"  />
        <?php echo anchor_popup('pekerja/insert', '<i class="icon-user"></i> Buat pekerja</a>', array('class'=>'btn'));?>
        <input id="pekerjaid" type="hidden" name="pekerjaid" value="<?php echo set_value('pekerjaid'); echo (!empty($data['pekerjaid'])) ? $data['pekerjaid'] : ''; ?>"  />
        <input id="baseurl" type="hidden" value="<?php echo site_url(''); ?>"  />
        <input id="oid" type="hidden" name="oid" value="<?php echo set_value('oid'); echo (!empty($data['oid'])) ? $data['oid'] : ''; ?>"  />
    </div>
</div>-->

<div class="control-group row-fluid">
    <label class="control-label offset3" for="i_nama">Pekerja <span class="required">*</span></label>
    <?php   $attr = "id='i_nama' class='offset1'";
            $defval = (!empty(set_value('pekerjaid'))) ? set_value('pekerjaid') : ((!empty($data['pekerjaid'])) ? $data['pekerjaid'] : '');
    ?>
    <div class="controls">
        <?php echo form_dropdown('pekerjaid', $options, $defval, $attr); ?>
        <?php echo anchor_popup('pekerja/insert', '<i class="icon-user"></i> Buat pekerja</a>', array('class'=>'btn'));?>
        <input id="baseurl" type="hidden" value="<?php echo site_url(''); ?>" />
        <input id="oid" type="hidden" name="oid" value="<?php echo set_value('oid'); echo (!empty($data['oid'])) ? $data['oid'] : ''; ?>"  />
    </div>
</div>
    
<div class="control-group row-fluid">
    <label class="offset3 control-label" for="tgl2">Tgl. Ambil <span class="required">*</span></label>
    <div class="controls">
        <div class="input-append date offset1" id="dpta" data-date="<?php echo date('Y-m-d'); ?>" data-date-format="yyyy-mm-dd">
            <input type="text" id="tgl2" name="tgla" value="<?php echo set_value('tgla'); echo (!empty($data['startdate'])) ? $data['startdate'] : ''; ?>"  />
            <span class="add-on"><i class="icon-th"></i></span>
        </div>
    </div>
</div>

<div class="control-group row-fluid">
    <label class="offset3 control-label" for="tgl">Tgl. Setor</label>
    <div class="controls">
        <div class="input-append date offset1" id="dpto" data-date="<?php echo date('Y-m-d'); ?>" data-date-format="yyyy-mm-dd">
            <input type="text" id="tgl" name="tgl" placeholder="Di isi setelah selesai." value="<?php echo set_value('tgl'); echo (!empty($data['finishdate'])) ? $data['finishdate'] : ''; ?>"  />
            <span class="add-on"><i class="icon-th"></i></span>
        </div>
    </div>
</div>

<div class="control-group row-fluid">
    <label class="control-label offset3" for="jumlah">Jumlah</label>
    <div class="controls">
        <input class="offset1" id="jumlah" type="text" name="jumlah" maxlength="11" value="<?php echo (!empty(set_value('jumlah'))) ? set_value('jumlah') : ((!empty($data['jmlsetor'])) ? $data['jmlsetor'] : ''/*$sisa*/); ?>"  />
    </div>
</div>

<!--<div class="control-group row-fluid">
    <label class="control-label offset3" for="status">Status</label>
    <div class="controls">
    <?php $options = array(
                        '0'  => 'Pengerjaan',
                        '1' => 'Selesai',
                    );
        $attr = "class='offset1'";
        $defVal = (!empty(set_value('status'))) ? set_value('status') : ((!empty($data['status'])) ? $data['status'] : '');    
        echo form_dropdown('status', $options, $defVal, $attr);
    ?>
    </div>    
</div>-->
    
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
<script src="<?php echo base_url().RES_DIR; ?>/bootstrap/js/bootstrap-typeahead.min.js"></script>
<script src="<?php echo base_url().RES_DIR; ?>/js/cut.js"></script>

</body>
</html>

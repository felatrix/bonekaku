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

if($mode == 'insert') {
    echo form_open('main/insert', $attributes);
} else {
    echo form_open('main/insert', $attributes, $hidden);
}
?>
    
<div class="control-group">    
    <div class="text-center text-info col-sm-12"><?php echo validation_errors(); //echo $success; ?></div>
</div>
<div class="control-group row-fluid">
    <label class="offset3 control-label" for="tgl_order">Tgl Order <span class="required">*</span></label>
    <?php //print_r($mainorder);//echo form_error('tgl_order'); ?>
    <div class="controls">
        <div class="input-append date offset1" id="dpto" data-date="<?php echo date('Y-m-d'); ?>" data-date-format="yyyy-mm-dd">
            <input type="text" id="tgl_order" name="tgl_order" placeholder="Tgl. Order" value="<?php echo set_value('tgl_order'); echo (!empty($mainorder['tglorder'])) ? $mainorder['tglorder'] : ''; ?>"  />
            <span class="add-on"><i class="icon-th"></i></span>
        </div>
    </div>
</div>
    
<div class="control-group row-fluid">
    <label class="control-label offset3" for="tgl_target">Tgl Target</label>
    <?php //echo form_error('tgl_target'); ?>
    <div class="controls">
        <div class="input-append date offset1" id="dptt" data-date="<?php echo date('Y-m-d'); ?>" data-date-format="yyyy-mm-dd">
            <input type="text" id="tgl_target" name="tgl_target" placeholder="Tgl. Target" value="<?php echo set_value('tgl_target'); echo (!empty($mainorder['targetdate'])) ? $mainorder['targetdate'] : ''; ?>"  />
            <span class="add-on"><i class="icon-th"></i></span>
        </div>
    </div>
</div>

<!--<div class="control-group row-fluid">
    <label class="control-label offset3" for="search_nama">Nama</label>
    <div class="controls">
        <input class="offset1 typeahead" id="search_nama" autocomplete="off" type="text" name="search_nama" value="<?php echo set_value('search_nama'); echo (!empty($mainorder['nama'])) ? $mainorder['nama'].'-'.$mainorder['nomer'] : ''; ?>"  />
        <?php echo anchor_popup('client/insert', '<i class="icon-user"></i> Buat pelanggan</a>', array('class'=>'btn'));?>
        <input id="pemesanid" type="hidden" name="pemesanid" value="<?php echo set_value('pemesanid'); echo (!empty($mainorder['pemesanid'])) ? $mainorder['pemesanid'] : ''; ?>"  />
        <input id="baseurl" type="hidden" value="<?php echo site_url(''); ?>" />
    </div>
</div>-->

<div class="control-group row-fluid">
    <label class="control-label offset3" for="i_nama">Nama <span class="required">*</span></label>
    <?php   $attr = "id='i_nama' class='offset1'";
            $defval = (!empty(set_value('pemesanid'))) ? set_value('pemesanid') : ((!empty($mainorder['pemesanid'])) ? $mainorder['pemesanid'] : '');
    ?>
    <div class="controls">
        <?php echo form_dropdown('pemesanid', $clients, $defval, $attr); ?>
        <?php echo anchor_popup('client/insert', '<i class="icon-user"></i> Buat pelanggan</a>', array('class'=>'btn'));?>
        <input id="baseurl" type="hidden" value="<?php echo site_url(''); ?>" />
    </div>
</div>

<!--<div class="control-group row-fluid">
    <label class="control-label offset3" for="item_boneka">Item Boneka</label>
    <div class="controls">
        <input class="offset1" id="item_boneka" type="text" name="item_boneka" maxlength="20" value="<?php echo set_value('item_boneka'); echo (!empty($mainorder['itemboneka'])) ? $mainorder['itemboneka'] : ''; ?>"  />
    </div>
</div>-->
    
<div class="control-group row-fluid">
    <label class="control-label offset3" for="input99">Jenis Boneka <span class="required">*</span></label>
    <?php   $attr = "id='input99' class='offset1'";
            $jenistulisan = (!empty(set_value('itemid'))) ? set_value('itemid') : ((!empty($mainorder['itemid'])) ? $mainorder['itemid'] : '');
    ?>
    <div class="controls">
        <?php echo form_dropdown('itemid', $items, $jenistulisan, $attr); ?>
        <?php echo anchor_popup('item/insert', '<i class="icon-plus"></i> Buat Boneka</a>', array('class'=>'btn'));?>
    </div>
</div>

<div class="control-group row-fluid">
    <label class="control-label offset3" for="input1">Warna Boneka</label>
    <div class="controls">
        <input class="offset1" id="input1" type="text" name="warna_boneka" value="<?php echo set_value('warna_boneka'); echo (!empty($mainorder['warnaboneka'])) ? $mainorder['warnaboneka'] : ''; ?>"  />
    </div>
</div>

<div class="control-group row-fluid">
    <label class="control-label offset3" for="input2">Bahan Boneka</label>
    <div class="controls">
        <input class="offset1" id="input2" type="text" name="bahan_boneka" value="<?php echo set_value('bahan_boneka'); echo (!empty($mainorder['bahanboneka'])) ? $mainorder['bahanboneka'] : ''; ?>"  />
    </div>
</div>

<div class="control-group row-fluid">
    <label class="control-label offset3" for="jumlah">Jumlah</label>
    <?php //echo form_error('jumlah'); ?>
    <div class="controls">
        <input class="offset1" id="jumlah" type="text" name="jumlah" maxlength="11" value="<?php echo set_value('jumlah'); echo (!empty($mainorder['jumlah'])) ? $mainorder['jumlah'] : ''; ?>"  />
    </div>
</div>

<div class="control-group row-fluid">
    <label class="control-label offset3" for="aksesoris">Aksesoris</label>
    <?php //echo form_error('aksesoris'); ?>
    <div class="controls">
        <input class="offset1" id="aksesoris" type="text" name="aksesoris"  value="<?php echo set_value('aksesoris'); echo (!empty($mainorder['aksesoris'])) ? $mainorder['aksesoris'] : ''; ?>"  />
    </div>
</div>
    
<div class="control-group row-fluid">
    <label class="control-label offset3" for="jenis_tulisan">Jenis Tulisan</label>
    <?php //echo form_error('jenis_tulisan'); ?>
<!--    <?php $options = array(
                        ''  => 'Silahkan pilih',
                        'Bordir'    => 'Bordir',
                        'Sablon'    => 'Sablon',
                        'Printing'    => 'Printing'
                    );
            $attr = "class='offset1'";
            $jenistulisan = (!empty(set_value('jenis_tulisan'))) ? set_value('jenis_tulisan') : ((!empty($mainorder['jenistulisan'])) ? $mainorder['jenistulisan'] : '');
    ?>
    <div class="controls">
        <?php echo form_dropdown('jenis_tulisan', $options, $jenistulisan, $attr); ?>
    </div>-->
    <?php   $attr = "id='jenis_tulisan' class='offset1'";
            $jenistulisan = (!empty(set_value('jenis_tulisan'))) ? set_value('jenis_tulisan') : ((!empty($mainorder['jenistulisan'])) ? $mainorder['jenistulisan'] : '');
    ?>
    <div class="controls">
        <?php echo form_dropdown('jenis_tulisan', $jenis, $jenistulisan, $attr); ?>
        <?php echo anchor_popup('jenis/insert', '<i class="icon-plus"></i> Buat Jenis Tulisan</a>', array('class'=>'btn'));?>
    </div>

</div>
    
<div class="control-group row-fluid">
    <label class="control-label offset3" for="tulisan">Tulisan</label>
    <?php //echo form_error('tulisan'); ?>
    <div class="controls">
        <input class="offset1" id="tulisan" type="text" name="tulisan" maxlength="100" value="<?php echo set_value('tulisan'); echo (!empty($mainorder['tulisan'])) ? $mainorder['tulisan'] : ''; ?>"  />
    </div>
</div>
    
<div class="control-group row-fluid">
    <label class="control-label offset3" for="warna_bordir">Warna Bordir</label>
    <?php //echo form_error('warna_bordir'); ?>
    <div class="controls">
        <input class="offset1" id="warna_bordir" type="text" name="warna_bordir" maxlength="20" value="<?php echo set_value('warna_bordir'); echo (!empty($mainorder['warnabordir'])) ? $mainorder['warnabordir'] : ''; ?>"  />
    </div>
</div>
    
<div class="control-group row-fluid">
    <label class="control-label offset3" for="lain_lain">Lain - lain</label>
    <?php //echo form_error('lain_lain'); ?>
    <div class="controls">
        <input class="offset1" id="lain_lain" type="text" name="lain_lain"  value="<?php echo set_value('lain_lain'); echo (!empty($mainorder['lainlain'])) ? $mainorder['lainlain'] : ''; ?>"  />
    </div>
</div>
    
<div class="control-group row-fluid">
    <label class="control-label offset3" for="harga">Harga</label>
    <?php //echo form_error('harga'); ?>
    <div class="controls">
        <input class="offset1" id="harga" type="text" name="harga" maxlength="11" value="<?php echo set_value('harga'); echo (!empty($mainorder['harga'])) ? $mainorder['harga'] : ''; ?>"  />
    </div>
</div>
    
<div class="control-group row-fluid">
    <label class="control-label offset3" for="tgl_dp">Tgl DP</label>
    <?php //echo form_error('tgl_dp'); ?>
    <div class="controls">
        <div class="input-append date offset1" id="dptd" data-date="<?php echo date('Y-m-d'); ?>" data-date-format="yyyy-mm-dd">
            <input id="tgl_dp" type="text" name="tgl_dp" placeholder="Tgl. DP" value="<?php echo set_value('tgl_dp'); echo (!empty($mainorder['tgldp'])) ? $mainorder['tgldp'] : ''; ?>"  />
            <span class="add-on"><i class="icon-th"></i></span>
        </div>
    </div>
</div>
    
<div class="control-group row-fluid">
    <label class="control-label offset3" for="jml_dp">Jml DP</label>
    <?php //echo form_error('jml_dp'); ?>
    <div class="controls">
        <input class="offset1" id="jml_dp" type="text" name="jml_dp" maxlength="11" value="<?php echo set_value('jml_dp'); echo (!empty($mainorder['jmldp'])) ? $mainorder['jmldp'] : ''; ?>"  />
    </div>
</div>
    
<div class="control-group row-fluid">
    <label class="control-label offset3" for="tgl_pelunasan">Tgl Pelunasan</label>
    <?php //echo form_error('tgl_pelunasan'); ?>
    <div class="controls">
        <div class="input-append date offset1" id="dptp" data-date="<?php echo date('Y-m-d'); ?>" data-date-format="yyyy-mm-dd">
            <input id="tgl_pelunasan" type="text" placeholder="Tgl. Pelunasan" name="tgl_pelunasan"  value="<?php echo set_value('tgl_pelunasan'); echo (!empty($mainorder['tglpelunasan'])) ? $mainorder['tglpelunasan'] : ''; ?>"  />
            <span class="add-on"><i class="icon-th"></i></span>
        </div>
    </div>        
</div>
    
<div class="control-group row-fluid">
    <label class="control-label offset3" for="jml_pelunasan">Jml Pelunasan</label>
    <?php //echo form_error('jml_pelunasan'); ?>
    <div class="controls">
        <input class="offset1" id="jml_pelunasan" type="text" name="jml_pelunasan" maxlength="11" value="<?php echo set_value('jml_pelunasan'); echo (!empty($mainorder['jmlpelunasan'])) ? $mainorder['jmlpelunasan'] : ''; ?>"  />
    </div>
</div>
    
<div class="control-group row-fluid">
    <label class="control-label offset3" for="ongkos_kirim">Ongkos Kirim</label>
    <?php //echo form_error('ongkos_kirim'); ?>
    <div class="controls">
        <input class="offset1" id="ongkos_kirim" type="text" name="ongkos_kirim" maxlength="11" value="<?php echo set_value('ongkos_kirim'); echo (!empty($mainorder['ongkoskirim'])) ? $mainorder['ongkoskirim'] : ''; ?>"  />
    </div>
</div>
    
<div class="control-group row-fluid">
    <label class="control-label offset3" for="kirim_via">Kirim Via</label>
    <?php //echo form_error('kirim_via'); ?>
    <div class="controls">
        <input class="offset1" id="kirim_via" type="text" name="kirim_via" maxlength="20" value="<?php echo set_value('kirim_via'); echo (!empty($mainorder['kirimvia'])) ? $mainorder['kirimvia'] : ''; ?>"  />
    </div>
</div>
    
<div class="control-group row-fluid">
    <label class="control-label offset3" for="tgl_kirim">Tgl Kirim Aktual</label>
    <?php //echo form_error('tgl_kirim'); ?>
    <div class="controls">
        <div class="input-append date offset1" id="dptk" data-date="<?php echo date('Y-m-d'); ?>" data-date-format="yyyy-mm-dd">
            <input id="tgl_kirim" placeholder="Diisi setelah dikirim" type="text" name="tgl_kirim"  value="<?php echo set_value('tgl_kirim'); echo (!empty($mainorder['tglkirim'])) ? $mainorder['tglkirim'] : ''; ?>"  />
            <span class="add-on"><i class="icon-th"></i></span>
        </div>
    </div>
</div>
    
<!--<div class="control-group row-fluid">
    <label class="control-label offset3" for="cancel">Cancel</label>
    <div class="controls">
        <input class="offset1" id="cancel" type="text" name="cancel" maxlength="1" value="<?php echo set_value('cancel'); echo (!empty($mainorder['cancel'])) ? $mainorder['cancel'] : ''; ?>"  />
    </div>
</div>-->

<div class="control-group row-fluid">
    <label class="control-label offset3" for="cancel">Cancel</label>
    <div class="controls">
    <?php $options = ['0' => 'Tidak', '1' => 'Ya'];
        $attr = "id='cancel' class='offset1'";
        $defVal = (set_value('cancel') != '') ? set_value('cancel') : ((!empty($mainorder['cancel'])) ? $mainorder['cancel'] : '0');
        echo form_dropdown('cancel', $options, $defVal, $attr);
    ?>
    </div>    
</div>

<!--<div class="control-group row-fluid">
    <label class="control-label offset3" for="waitingconfirm">Tunggu Konfirmasi</label>
    <div class="controls">
        <input class="offset1" id="waitingconfirm" type="text" name="waitingconfirm" maxlength="1" value="<?php echo set_value('waitingconfirm'); echo (!empty($mainorder['waitingconfirm'])) ? $mainorder['waitingconfirm'] : ''; ?>"  />
    </div>
</div>-->

<div class="control-group row-fluid">
    <label class="control-label offset3" for="waitingconfirm">Tunggu Konfirmasi</label>
    <div class="controls">
    <?php $options = ['0' => 'Tidak', '1' => 'Ya'];
        $attr = "id='waitingconfirm' class='offset1'";
        $defVal = (set_value('waitingconfirm') != '') ? set_value('waitingconfirm') : ((isset($mainorder['waitingconfirm'])) ? $mainorder['waitingconfirm'] : '1');
        echo form_dropdown('waitingconfirm', $options, $defVal, $attr);
    ?>
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
<script src="<?php echo base_url().RES_DIR; ?>/bootstrap/js/bootstrap-typeahead.min.js"></script>
<script src="<?php echo base_url().RES_DIR; ?>/js/maino.js"></script>

</body>
</html>

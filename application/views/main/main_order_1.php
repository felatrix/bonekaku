
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
echo form_open('Main', $attributes); ?>

<div class="control-group">
    <label class="control-label" for="tgl_order">Tgl Order <span class="required">*</span></label>
    <?php echo form_error('tgl_order'); ?>
    <div class="controls">
      <input id="tgl_order" type="text" name="tgl_order" placeholder="Tgl. Order" value="<?php echo set_value('tgl_order'); ?>"  />
    </div>
  </div>
    <label for="tgl_order">Tgl Order <span class="required">*</span></label>
    <?php echo form_error('tgl_order'); ?>
    <br /><input id="tgl_order" type="text" name="tgl_order"  value="<?php echo set_value('tgl_order'); ?>"  />

    <label for="tgl_target">Tgl Target</label>
    <?php echo form_error('tgl_target'); ?>
    <br /><input id="tgl_target" type="text" name="tgl_target"  value="<?php echo set_value('tgl_target'); ?>"  />

    <label for="item_boneka">Item Boneka</label>
    <?php echo form_error('item_boneka'); ?>
    <br /><input id="item_boneka" type="text" name="item_boneka" maxlength="20" value="<?php echo set_value('item_boneka'); ?>"  />

    <label for="jumlah">Jumlah</label>
    <?php echo form_error('jumlah'); ?>
    <br /><input id="jumlah" type="text" name="jumlah" maxlength="11" value="<?php echo set_value('jumlah'); ?>"  />

    <label for="aksesoris">Aksesoris</label>
    <?php echo form_error('aksesoris'); ?>
    <br /><input id="aksesoris" type="text" name="aksesoris"  value="<?php echo set_value('aksesoris'); ?>"  />

    
<p>
        <label for="jenis_tulisan">Jenis Tulisan</label>
        <?php echo form_error('jenis_tulisan'); ?>
        
        <?php // Change the values in this array to populate your dropdown as required ?>
        <?php $options = array(
                                                  ''  => 'Please Select',
                                                  'B'    => 'Bordir',
                                                  'S'    => 'Sablon',
                                                  'P'    => 'Printing'
                                                ); ?>

        <br /><?php echo form_dropdown('jenis_tulisan', $options, set_value('jenis_tulisan'))?>
</p>                                             
                        
<p>
        <label for="tulisan">Tulisan</label>
        <?php echo form_error('tulisan'); ?>
        <br /><input id="tulisan" type="text" name="tulisan" maxlength="100" value="<?php echo set_value('tulisan'); ?>"  />
</p>

<p>
        <label for="warna_bordir">Warna Bordir</label>
        <?php echo form_error('warna_bordir'); ?>
        <br /><input id="warna_bordir" type="text" name="warna_bordir" maxlength="20" value="<?php echo set_value('warna_bordir'); ?>"  />
</p>

<p>
        <label for="lain_lain">Lain - lain</label>
        <?php echo form_error('lain_lain'); ?>
        <br /><input id="lain_lain" type="text" name="lain_lain"  value="<?php echo set_value('lain_lain'); ?>"  />
</p>

<p>
        <label for="harga">Harga</label>
        <?php echo form_error('harga'); ?>
        <br /><input id="harga" type="text" name="harga" maxlength="11" value="<?php echo set_value('harga'); ?>"  />
</p>

<p>
        <label for="tgl_dp">Tgl DP</label>
        <?php echo form_error('tgl_dp'); ?>
        <br /><input id="tgl_dp" type="text" name="tgl_dp"  value="<?php echo set_value('tgl_dp'); ?>"  />
</p>

<p>
        <label for="jml_dp">Jml DP</label>
        <?php echo form_error('jml_dp'); ?>
        <br /><input id="jml_dp" type="text" name="jml_dp" maxlength="11" value="<?php echo set_value('jml_dp'); ?>"  />
</p>

<p>
        <label for="tgl_pelunasan">Tgl Pelunasan</label>
        <?php echo form_error('tgl_pelunasan'); ?>
        <br /><input id="tgl_pelunasan" type="text" name="tgl_pelunasan"  value="<?php echo set_value('tgl_pelunasan'); ?>"  />
</p>

<p>
        <label for="jml_pelunasan">Jml Pelunasan</label>
        <?php echo form_error('jml_pelunasan'); ?>
        <br /><input id="jml_pelunasan" type="text" name="jml_pelunasan" maxlength="11" value="<?php echo set_value('jml_pelunasan'); ?>"  />
</p>

<p>
        <label for="ongkos_kirim">Ongkos Kirim</label>
        <?php echo form_error('ongkos_kirim'); ?>
        <br /><input id="ongkos_kirim" type="text" name="ongkos_kirim" maxlength="11" value="<?php echo set_value('ongkos_kirim'); ?>"  />
</p>

<p>
        <label for="kirim_via">Kirim Via</label>
        <?php echo form_error('kirim_via'); ?>
        <br /><input id="kirim_via" type="text" name="kirim_via" maxlength="20" value="<?php echo set_value('kirim_via'); ?>"  />
</p>

<p>
        <label for="tgl_kirim">Tgl Kirim</label>
        <?php echo form_error('tgl_kirim'); ?>
        <br /><input id="tgl_kirim" type="text" name="tgl_kirim"  value="<?php echo set_value('tgl_kirim'); ?>"  />
</p>

<p>
        <label for="cancel">Cancel</label>
        <?php echo form_error('cancel'); ?>
        <br /><input id="cancel" type="text" name="cancel" maxlength="1" value="<?php echo set_value('cancel'); ?>"  />
</p>

<p>
        <label for="waitingconfirm">waitingconfirm</label>
        <?php echo form_error('waitingconfirm'); ?>
        <br /><input id="waitingconfirm" type="text" name="waitingconfirm" maxlength="1" value="<?php echo set_value('waitingconfirm'); ?>"  />
</p>


<p>
        <?php echo form_submit( 'submit', 'Submit'); ?>
</p>

<?php echo form_close(); ?>
</div>
    
<?php echo $this->load->view('footer'); ?>

</body>
</html>

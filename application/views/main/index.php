<!DOCTYPE html>
<html>
<head>
    <?php echo $this->load->view('head'); ?>
    <!--link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap-editable/css/bootstrap-editable.css" rel="stylesheet"/>
    <script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap-editable/js/bootstrap-editable.min.js"></script-->
    <script src="<?php echo base_url().RES_DIR; ?>/js/main.js"></script>
</head>
<body>
<?php
echo $this->load->view('header'); 
$ctlr = (!empty($arsip)) ? 'main/arsip' : 'main';
?>
<!--div class = "page-header"-->
<!--h2 class="text-center"><?php /*echo $title;*/ $i = $no; ?></h2>
<br-->
<!--/div-->
<h4 class="text-center text-success"><?php echo $success; ?></h4>
<div class="container-fluid">
<div class="tab-content">
<div class="table-responsive">
   <table class="table table-bordered table-hover">
    <thead>
      <tr class="info">
         <th rowspan="2" style="vertical-align: inherit;" class=' fixed-10'><?php echo anchor($ctlr . '/0/tglorder/'.$order, 'No.', 'title="Urutkan"'); ?></th>
<!--         <th class=''><?php echo anchor($ctlr . '/0/tglorder/'.$order, 'Tgl. Order', 'title="Urutkan Tgl. Order"'); ?></th>
         <th class=''><?php echo anchor($ctlr . '/0/targetdate/'.$order, 'Tgl. Target', 'title="Urutkan Tgl. Target"'); ?></th>-->
         <?php if ($this->authorization->is_permitted(['update_mainorder'])): ?>
         <th rowspan="2" style="vertical-align: inherit;" class=' fixed-10'>Edit</th>
        <?php endif; ?>
         <th colspan="2" style="text-align: -webkit-center;">Tanggal</th>
         <th rowspan="2" style="vertical-align: inherit;"><?php echo anchor($ctlr . '/0/nama/'.$order, 'Nama', 'title="Urutkan Nama"'); ?></th>
         <th rowspan="2" style="vertical-align: inherit;"><?php echo anchor($ctlr . '/0/itemjahitan/'.$order, 'Item Boneka', 'title="Urutkan Item Boneka"'); ?></th>
         <th rowspan="2" style="vertical-align: inherit;"><?php echo anchor($ctlr . '/0/jumlah/'.$order, 'Jumlah', 'title="Urutkan Jumlah"'); ?></th>
         <th rowspan="2" style="vertical-align: inherit;">Aksesoris</th>
         <th colspan="2" style="text-align: -webkit-center;">Tulisan</th>
<!--         <th rowspan="2" style="vertical-align: inherit;"><?php echo anchor($ctlr . '/0/jenistulisan/'.$order, 'Jenis Tulisan', 'title="Urutkan Jenis Tulisan"'); ?></th>
         <th rowspan="2" style="vertical-align: inherit;">Tulisan</th>-->
         <th rowspan="2" style="vertical-align: inherit;">Warna Bordir</th>
         <th rowspan="2" style="vertical-align: inherit;">Lain-Lain</th>
         <th rowspan="2" style="vertical-align: inherit;">Harga</th>
         <th colspan="2" style="text-align: -webkit-center;">DP</th>
<!--         <th rowspan="2" style="vertical-align: inherit;"><?php echo anchor($ctlr . '/0/tgldp/'.$order, 'Tgl. DP', 'title="Urutkan Tgl. DP"'); ?></th>
         <th rowspan="2" style="vertical-align: inherit;"><?php echo anchor($ctlr . '/0/jmldp/'.$order, 'Jml. DP', 'title="Urutkan Jml. DP"'); ?></th>-->
         <th colspan="2" style="text-align: -webkit-center;">Pelunasan</th>
<!--         <th rowspan="2" style="vertical-align: inherit;"><?php echo anchor($ctlr . '/0/tglpelunasan/'.$order, 'Tgl. Pelunasan', 'title="Urutkan Tgl. Pelunasan"'); ?></th>
         <th rowspan="2" style="vertical-align: inherit;"><?php echo anchor($ctlr . '/0/jmlpelunasan/'.$order, 'Jml. Pelunasan', 'title="Urutkan Jml. Pelunasan"'); ?></th>-->
         <th colspan="3" style="text-align: -webkit-center;">Kirim</th>
<!--         <th rowspan="2" style="vertical-align: inherit;">Onkos Kirim</th>
         <th rowspan="2" style="vertical-align: inherit;">Kirim Via</th>
         <th rowspan="2" style="vertical-align: inherit;">Tgl. Kirim</th>-->
         <th rowspan="2" style="vertical-align: inherit;">Cancel</th>
         <th rowspan="2" style="vertical-align: inherit;">Tunggu Konfirmasi</th>
        
        <?php if ($this->authorization->is_permitted(['delete_mainorder'])): ?>
         <th rowspan="2" style="vertical-align: inherit;" class=' fixed-10'>Del</th>
        <?php endif; ?>
      </tr>
      <tr class="info">
         <th><?php echo anchor($ctlr . '/0/tglorder/'.$order, 'Order', 'title="Urutkan Tgl. Order"'); ?></th>
         <th><?php echo anchor($ctlr . '/0/targetdate/'.$order, 'Target', 'title="Urutkan Tgl. Target"'); ?></th>
         <th><?php echo anchor($ctlr . '/0/jenistulisan/'.$order, 'Jenis', 'title="Urutkan Jenis Tulisan"'); ?></th>
         <th>Kata</th>
         <th><?php echo anchor($ctlr . '/0/tgldp/'.$order, 'Tgl.', 'title="Urutkan Tgl. DP"'); ?></th>
         <th><?php echo anchor($ctlr . '/0/jmldp/'.$order, 'Jml.', 'title="Urutkan Jml. DP"'); ?></th>
         <th><?php echo anchor($ctlr . '/0/tglpelunasan/'.$order, 'Tgl.', 'title="Urutkan Tgl. Pelunasan"'); ?></th>
         <th><?php echo anchor($ctlr . '/0/jmlpelunasan/'.$order, 'Jml.', 'title="Urutkan Jml. Pelunasan"'); ?></th>
         <th>Ongkos</th>
         <th>Via</th>
         <th>Tgl.</th>
      </tr>      
    </thead>
    <tbody>
    <?php foreach ($records as $record): ?>
        <tr class="<?php $class = (($i % 2) == 0) ? "success" : "warning"; echo $class; ?>">
        <td class=' fixed-10'><?php echo $i++ ?></td>
        <td class=' fixed-10'>
        <?php echo form_open('main/edit', $attributes, $hiddenid); ?>
            <button class="btn btn-primary btn-mini" type="submit">
                <i class="icon-pencil icon-white"></i>
            </button>
        </form>
        </td>
        <td class=''><?php echo $record['tglorder']; ?></td>
        <td class=''><?php echo ($record['targetdate'] != '0000-00-00') ? $record['targetdate'] : ''; ?></td>
        <td class=''><a href="javascript:void(0);" data-toggle="popover" data-html="true" data-content="<?php echo nl2br($record['title']); ?>"><?php echo $record['nama']; ?></a></td>
        <!--<td class=''><?php echo $record['itemjahitan']. ' ' .$record['bahanboneka']. ' ' .$record['warnaboneka']; ?></td>-->
        <td class=''><?php echo $record['itemjahitan'] . ' (oid: ' .$record['id'] . ')'; ?></td>
        <td class=''><?php echo number_format($record['jumlah'], 0, ',', '.'); ?></td>
        <td class=''><?php echo $record['aksesoris']; ?></td>
        <td class=''><?php echo $record['jenistulisan']; ?></td>
        <td class=''><?php echo $record['tulisan']; ?></td>
        <td class=''><?php echo $record['warnabordir']; ?></td>
        <td class=''><?php echo $record['lainlain']; ?></td>
        <td class=''><?php echo number_format($record['harga'], 0, ',', '.'); ?></td>
        <td class=''><?php echo ($record['tgldp'] != '0000-00-00') ? $record['tgldp'] : ''; ?></td>
        <td class=''><?php echo number_format($record['jmldp'], 0, ',', '.'); ?></td>
        <!--<td class=''><button class="btn btn-primary btn-mini" type='submit'><i class="icon-pencil icon-white"></i></button></td>-->
        <td class=''><?php echo ($record['tglpelunasan'] != '0000-00-00') ? $record['tglpelunasan'] : ''; ?></td>
        <td class=''><?php echo number_format($record['jmlpelunasan'], 0, ',', '.'); ?><button class="btn btn-primary btn-mini" type='submit'><i class="icon-pencil icon-white"></i></button></td>
        <td class=''><?php echo number_format($record['ongkoskirim'], 0, ',', '.'); ?></td>
        <td class=''><?php echo $record['kirimvia']; ?></td>
        <td class=''><?php echo ($record['tglkirim'] != '0000-00-00') ? $record['tglkirim'] : ''; ?><button class="btn btn-primary btn-mini" type='submit'><i class="icon-pencil icon-white"></i></button></td>
        <td class=''><?php echo ($record['cancel'] == '1') ? 'Ya' : 'Tidak'; ?></td>
        <td class=''><?php echo ($record['waitingconfirm'] == '1') ? 'Ya' : 'Tidak'; ?></td>
        <?php $hiddenid = array('orderid' => $record['id']); ?>
        <?php if ($this->authorization->is_permitted(['update_mainorder'])): ?>
        <!--<td class=' fixed-10'>
        <?php echo form_open('main/edit', $attributes, $hiddenid); ?>
            <button class="btn btn-primary btn-mini" type="submit">
                <i class="icon-pencil icon-white"></i>
            </button>
        </form>
        </td>
        <?php endif; ?>-->
        <?php if ($this->authorization->is_permitted(['delete_mainorder'])): ?>
        <td class=' fixed-10'>
        <?php echo form_open('main/del', $attributes, $hiddenid); ?>
            <button type="button" class="btn btn-danger btn-mini delete-event" data-dismiss="modal" data-url="#" data-confirmation="Yakin akan menghapus order '<?php echo $record['itemjahitan']. ' (oid: ' .$record['id'] . ')';?>' ini?" data-confirmation-title="Konfirmasi">
                <i class="icon-trash icon-white"></i>
            </button>
        </form>
        </td>
        <?php endif; ?>
        </tr>
    <?php endforeach; ?>
    <?php if (empty($record)) { ?>
        <tr>
            <td colspan="23" class="danger text-center"><?php echo $result; ?></td>
        </tr>
    <?php } ?>
    </tbody>
   </table>
</div>
</div>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-12 text-center">
            <?php echo $pagination; ?>
        </div>
    </div>
</div>

<?php echo $this->load->view('footer'); ?>

<div id="delModal" class="modal hide fade">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4>Konfirmasi</h4>
  </div>
  <div class="modal-body">
    <p>Yakin akan menghapus order ini?</p>
  </div>
  <div class="modal-footer">
    <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Tidak</button>
    <button class="btn btn-primary" data-dismiss="modal">Ya</button>  
  </div>
</div>

</body>
</html>

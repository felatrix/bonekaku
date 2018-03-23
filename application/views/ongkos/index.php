<!DOCTYPE html>
<html>
<head>
    <?php echo $this->load->view('head'); ?>
    <script src="<?php echo base_url().RES_DIR; ?>/js/main.js"></script>
</head>
<body>
<?php echo $this->load->view('header'); ?>
<?php $i = $no; $ctlr = "ongkos"; ?>
<h4 class="text-center text-success"><?php echo $success; ?></h4>
<div class="container-fluid">
<div class="tab-content">
<div class="table-responsive">
   <table class="table table-bordered table-hover">
    <thead>
        <tr class="info">
         <th rowspan="2" style="vertical-align: inherit;" class=' fixed-10'><?php echo anchor($ctlr . '/0/itemjahitan/'.$order, 'No.', 'title="Urutkan No"'); ?></th>
         <th rowspan="2" style="vertical-align: inherit;" class=''><?php echo anchor($ctlr . '/0/itemjahitan/'.$order, 'Item Jahitan', 'title="Urutkan Item Jahitan"'); ?></th>
<!--         <th rowspan="2" style="vertical-align: inherit;" class=''><?php echo anchor($ctlr . '/0/bahan/'.$order, 'Bahan', 'title="Urutkan Bahan"'); ?></th>-->
         <th rowspan="2" style="vertical-align: inherit;" class=''><?php echo anchor($ctlr . '/0/ongkospretel/'.$order, 'Harga Pretel', 'title="Urutkan Harga Pretel"'); ?></th>
         <th rowspan="2" style="vertical-align: inherit;" class=''><?php echo anchor($ctlr . '/0/ongkosjahit/'.$order, 'Harga Jahit', 'title="Urutkan Harga Jahit"'); ?></th>
         <th rowspan="2" style="vertical-align: inherit;" class=''><?php echo anchor($ctlr . '/0/ongkosfinishing/'.$order, 'Harga Finishing', 'title="Urutkan Harga Finishing"'); ?></th>
        <th colspan="2" style="text-align: -webkit-center;">Konsumsi Bahan</th>
        <th rowspan="2" style="vertical-align: inherit;" class=''><?php echo anchor($ctlr . '/0/tanggalupdate/'.$order, 'Update', 'title="Urutkan Update"'); ?></th>
        <?php if ($this->authorization->is_permitted(['update_ongkos'])): ?>
         <th rowspan="2" style="vertical-align: inherit;" class=' fixed-10'>Edit</th>
        <?php endif; ?>
        <?php if ($this->authorization->is_permitted(['delete_ongkos'])): ?>
         <th rowspan="2" style="vertical-align: inherit;" class=' fixed-10'>Del</th>
        <?php endif; ?>
      </tr>
      <tr class="info">
         <th class=''><?php echo anchor($ctlr . '/0/satuan/'.$order, 'Satuan (pcs)', 'title="Urutkan satuan"'); ?></th>
         <th class=''><?php echo anchor($ctlr . '/0/panjang/'.$order, 'Panjang (yard)', 'title="Urutkan panjang"'); ?></th>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($records as $record): ?>
        <tr class="<?php echo $class = (($i % 2) == 0) ? "success" : "warning"; ?>">
        <td class=' fixed-10'><?php echo $i++ ?></td>
        <td class=''><?php echo $record['itemjahitan']; ?></td>
<!--        <td class=''><?php echo $record['bahan']; ?></td>-->
        <td class=''><div class="right"><?php echo number_format($record['ongkospretel'], 0, ',', '.'); ?></div></td>
        <td class=''><div class="right"><?php echo number_format($record['ongkosjahit'], 0, ',', '.'); ?></div></td>
        <td class=''><div class="right"><?php echo number_format($record['ongkosfinishing'], 0, ',', '.'); ?></div></td>
        <td class=''><div class="right"><?php echo number_format($record['satuan'], 0, ',', '.'); ?></div></td>
        <td class=''><div class="right"><?php echo number_format($record['panjang'], 0, ',', '.'); ?></div></td>
        <td class=''><?php echo $record['tanggalupdate']; ?></td>
        <?php $hiddenid = array('dataid' => $record['id']); ?>
        <?php if ($this->authorization->is_permitted(['update_ongkos'])): ?>
        <td class=' fixed-10'>
        <?php echo form_open($ctlr . '/edit', $attributes, $hiddenid); ?>
            <button class="btn btn-primary btn-mini" type="submit">
                <i class="icon-pencil icon-white"></i>
            </button>
        </form>
        </td>
        <?php endif; ?>
        <?php if ($this->authorization->is_permitted(['delete_ongkos'])): ?>
        <td class=' fixed-10'>
        <?php echo form_open($ctlr . '/del', $attributes, $hiddenid); ?>
            <button type="button" class="btn btn-danger btn-mini delete-event" data-dismiss="modal" data-url="#" data-confirmation="Yakin akan menghapus '<?php echo $record['itemjahitan']/*.' '.$record['bahan']*/;?>'?" data-confirmation-title="Konfirmasi">
                <i class="icon-trash icon-white"></i>
            </button>
        </form>
        </td>
        <?php endif; ?>
        </tr>
    <?php endforeach; ?>
    <?php if (empty($record)) { ?>
        <tr>
            <td colspan="10" class="danger text-center"><?php echo $result; ?></td>
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

<!DOCTYPE html>
<html>
<head>
    <?php echo $this->load->view('head'); ?>
    <script src="<?php echo base_url().RES_DIR; ?>/js/main.js"></script>
</head>
<body>
<?php 
    echo $this->load->view('header'); $i = $no; $ctlr = "fin"; $total = 0; 
    $sum_data = $this->fin_model->sum_data_by_oid($data['oid'], $data['id'], 'selesai');
    $sisa = $data['jml'] - $sum_data['selesai'];
?>
<h4 class="text-center text-success"><?php echo $success; ?></h4>
<div class="container-fluid">
<div class="tab-content">
<div class="table-responsive">
   <table class="table table-hover">
       <thead>
      <tr>
          <td class="success">Orderid: </td><td class="success"><?php echo $data['oid']; ?></td>
        <td class="warning">Item: </td><td class="warning"><?php echo $data['itemjahitan']. ' ' .$data['bahanboneka']. ' ' .$data['warnaboneka']; ?></td>
        <td class="info">Jumlah: </td><td class="info"><?php echo $data['jml']; ?></td>
        <td class="success">Sisa: </td><td class="success"><?php echo $sisa; ?></td>
        <?php if ($this->authorization->is_permitted(['create_fo'])): ?>
        <td class="error fixed-10">Data</td>
        <td class='error fixed-10'>
        <?php echo form_open($ctlr . '/insert', ['role' => 'form', 'style' => 'margin: auto'], ['oid' => $data['oid'], 'coid' => $data['id']]); ?>
            <!--input type="hidden" name="oid" value="<?php echo $data['id']; ?>"/-->
            <button class="btn btn-primary btn-mini" title="Tambah distribusi" type="submit">
                <i class="icon-plus icon-white"></i>
            </button>
        </form>
        </td>
        <?php endif; ?>
      </tr>
      </thead>
   </table>
</div></div></div>
<div class="container-fluid">
<div class="tab-content">
<div class="table-responsive">
   <table class="table table-hover">
    <thead>
      <tr>
         <th class='success fixed-10'><?php echo anchor($ctlr . '/set/0/tanggal/'.$order, 'No.', 'title="Urutkan"'); ?></th>
         <th class='info'><?php echo anchor($ctlr . '/set/0/nama/'.$order, 'Pekerja', 'title="Urutkan Pekerja"'); ?></th>
         <th class='success'><?php echo anchor($ctlr . '/set/0/selesai/'.$order, 'Selesai', 'title="Urutkan Selesai"'); ?></th>
         <th class='info'>Sisa</th>
         <th class='success'><?php echo anchor($ctlr . '/set/0/tanggal/'.$order, 'Tanggal', 'title="Urutkan Tanggal"'); ?></th>
        <?php if ($this->authorization->is_permitted(['update_fo'])): ?>
         <th class='info fixed-10'>Edit</th>
        <?php endif; ?>
        <?php if ($this->authorization->is_permitted(['delete_fo'])): ?>
         <th class='success fixed-10'>Del</th>
        <?php endif; ?>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($records as $record): ?>
        <tr>
        <td class='success fixed-10'><?php echo $i++ ?></td>
        <td class='info'><?php echo $record['nama']; ?></td>
        <td class='success'><?php echo $record['selesai']; $total += $record['selesai']; ?></td>
        <td class='info'><?php if ($record === end($records)) echo ($data['jml'] - $total); ?></td>
        <td class='success'><?php echo $record['tanggal']; ?></td>
        <?php $hiddenid = ['oid' => $record['oid'], 'dataid' => $record['id'], 'coid' => $record['coid']]; ?>
        <?php if ($this->authorization->is_permitted(['update_fo'])): ?>
        <td class='info fixed-10'>
            <?php echo form_open($ctlr . '/edit', $attributes, $hiddenid); ?>
            <button class="btn btn-primary btn-mini" type="submit">
                <i class="icon-pencil icon-white"></i>
            </button>
        </form>
        </td>
        <?php endif; ?>
        <?php if ($this->authorization->is_permitted(['delete_fo'])): ?>
        <td class='success fixed-10'>
        <?php echo form_open($ctlr . '/del', $attributes, $hiddenid); ?>
            <button type="button" class="btn btn-danger btn-mini delete-event" data-dismiss="modal" data-url="#" data-confirmation="Yakin akan menghapus penugasan kepada '<?php echo $record['nama'];?>'?" data-confirmation-title="Konfirmasi">
                <i class="icon-trash icon-white"></i>
            </button>
        </form>
        </td>
        <?php endif; ?>
        </tr>
    <?php endforeach; ?>
    <?php if (empty($record)) { ?>
        <tr>
            <td colspan="9" class="danger text-center"><?php echo $result; ?></td>
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

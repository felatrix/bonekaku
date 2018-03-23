<!DOCTYPE html>
<html>
<head>
    <?php echo $this->load->view('head'); ?>
    <script src="<?php echo base_url().RES_DIR; ?>/js/main.js"></script>
</head>
<body>
<?php echo $this->load->view('header'); $i = $no; $ctlr = "tulisan"; ?>
<h4 class="text-center text-success"><?php echo $success; ?></h4>
<?php echo $this->load->view($ctlr . '/mohead', ['data' => $data, 'ctlr' => $ctlr, 'mode' => 'list']); ?>
<!--div class="container-fluid">
<div class="tab-content">
<div class="table-responsive">
   <table class="table table-hover">
       <thead>
      <tr>
          <td class="success">Orderid: </td><td class="success"><?php echo $data['id']; ?></td>
        <td class="warning">Item: </td><td class="warning"><?php echo $data['itemboneka']; ?></td>
        <td class="info">Jumlah: </td><td class="info"><?php echo $data['jumlah']; ?></td>
        <td class="success">Jenis Tulisan: </td><td class="success"><?php echo $data['jenistulisan']; ?></td>
        <td class="warning">Tulisan: </td><td class="warning"><?php echo $data['tulisan']; ?></td>
        <td class="error fixed-10">Kegiatan</td>
        <td class='error fixed-10'>
        <?php echo form_open($ctlr . '/insert', ['role' => 'form', 'style' => 'margin: auto'], ['oid' => $data['id']]); ?>
            <button class="btn btn-primary btn-mini" title="Tambah pemakaian" type="submit">
                <i class="icon-plus icon-white"></i>
            </button>
        </form>
        </td>
      </tr>
      </thead>
   </table>
</div></div></div-->
<div class="container-fluid">
<div class="tab-content">
<div class="table-responsive">
   <table class="table table-hover">
    <thead>
      <tr>
         <th class='success fixed-10'><?php echo anchor($ctlr . '/set/0/id/'.$order, 'No.', 'title="Urutkan"'); ?></th>
         <th class='info'><?php echo anchor($ctlr . '/set/0/kegiatan/'.$order, 'Kegiatan', 'title="Urutkan Kegiatan"'); ?></th>
         <th class='success'><?php echo anchor($ctlr . '/set/0/antar/'.$order, 'Tanggal Antar', 'title="Urutkan Tanggal Antar"'); ?></th>
         <th class='info'><?php echo anchor($ctlr . '/set/0/kembali/'.$order, 'Tanggal Kembali', 'title="Urutkan Tanggal Kembali"'); ?></th>
         <th class='success'><?php echo anchor($ctlr . '/set/0/jumlah/'.$order, 'Jumlah', 'title="Urutkan Jumlah"'); ?></th>
        <?php if ($this->authorization->is_permitted(['update_tulisan'])): ?>
         <th class='info fixed-10'>Edit</th>
        <?php endif; ?>
        <?php if ($this->authorization->is_permitted(['delete_tulisan'])): ?>
         <th class='success fixed-10'>Del</th>
        <?php endif; ?>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($records as $record): ?>
        <tr>
        <td class='success fixed-10'><?php echo $i++ ?></td>
        <td class='info'><?php echo $record['kegiatan']; ?></td>
        <td class='success'><?php echo $record['antar']; ?></td>
        <td class='info'><?php echo $record['kembali']; ?></td>
        <td class='success'><?php echo $record['jumlah']; ?></td>
        <?php $hiddenid = ['oid' => $record['oid'], 'dataid' => $record['id']];?>
        <?php if ($this->authorization->is_permitted(['update_tulisan'])): ?>
        <td class='info fixed-10'>
        <?php echo form_open($ctlr . '/edit', $attributes, $hiddenid); ?>
            <button class="btn btn-primary btn-mini" type="submit">
                <i class="icon-pencil icon-white"></i>
            </button>
        </form>
        </td>
        <?php endif; ?>
        <?php if ($this->authorization->is_permitted(['delete_tulisan'])): ?>
        <td class='success fixed-10'>
        <?php echo form_open($ctlr . '/del', $attributes, $hiddenid); ?>
            <button type="button" class="btn btn-danger btn-mini delete-event" data-dismiss="modal" data-url="#" data-confirmation="Yakin akan menghapus kegiatan '<?php echo $record['kegiatan'];?>'?" data-confirmation-title="Konfirmasi">
                <i class="icon-trash icon-white"></i>
            </button>
        </form>
        </td>
        <?php endif; ?>
        </tr>
    <?php endforeach; ?>
    <?php if (empty($record)) { ?>
        <tr>
            <td colspan="7" class="danger text-center"><?php echo $result; ?></td>
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

<!DOCTYPE html>
<html>
<head>
    <?php echo $this->load->view('head'); ?>
    <script src="<?php echo base_url().RES_DIR; ?>/js/main.js"></script>
</head>
<body>
<?php echo $this->load->view('header'); $i = $no; $ctlr = "cut"; ?>
<h4 class="text-center text-success"><?php echo $success; ?></h4>
<div class="container-fluid">
<div class="tab-content">
<div class="table-responsive">
   <table class="table table-hover">
       <thead>
      <tr>
          <td class="success">Orderid: </td><td class="success"><?php echo $data['id']; ?></td>
        <td class="warning">Item: </td><td class="warning"><?php echo $data['itemboneka']; ?></td>
        <td class="info">Jumlah: </td><td class="info"><?php echo $data['jumlah']; ?></td>
        <td class="error fixed-10">Penugasan</td>
        <td class='error fixed-10'>
        <?php echo form_open($ctlr . '/insert', ['role' => 'form', 'style' => 'margin: auto'], ['oid' => $data['id']]); ?>
            <!--input type="hidden" name="oid" value="<?php echo $data['id']; ?>"/-->
            <button class="btn btn-primary btn-mini" title="Tambah penugasan" type="submit">
                <i class="icon-plus icon-white"></i>
            </button>
        </form>
        </td>
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
         <th class='success fixed-10'><?php echo anchor($ctlr . '/set/0/bywho/'.$order, 'No.', 'title="Urutkan"'); ?></th>
         <th class='info'><?php echo anchor($ctlr . '/set/0/bywho/'.$order, 'Pekerja', 'title="Urutkan Pekerja"'); ?></th>
         <th class='success'><?php echo anchor($ctlr . '/set/0/jml/'.$order, 'Jumlah', 'title="Urutkan Jumlah"'); ?></th>
         <th class='info'><?php echo anchor($ctlr . '/set/0/date/'.$order, 'Tanggal', 'title="Urutkan Tanggal"'); ?></th>
         <th class='success fixed-10'>Edit</th>
         <th class='info fixed-10'>Del</th>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($records as $record): ?>
        <tr>
        <td class='success fixed-10'><?php echo $i++ ?></td>
        <td class='info'><?php echo $record['nama']; ?></td>
        <td class='success'><?php echo $record['jml']; ?></td>
        <td class='info'><?php echo $record['date']; ?></td>
        <td class='success fixed-10'>
        <?php $hiddenid = ['oid' => $record['oid'], 'dataid' => $record['id']];
              echo form_open($ctlr . '/edit', $attributes, $hiddenid); ?>
            <button class="btn btn-primary btn-mini" type="submit">
                <i class="icon-pencil icon-white"></i>
            </button>
        </form>
        </td>
        <td class='info fixed-10'>
        <?php echo form_open($ctlr . '/del', $attributes, $hiddenid); ?>
            <button type="button" class="btn btn-danger btn-mini delete-event" data-dismiss="modal" data-url="#" data-confirmation="Yakin akan menghapus penugasan kepada '<?php echo $record['nama'];?>'?" data-confirmation-title="Konfirmasi">
                <i class="icon-trash icon-white"></i>
            </button>
        </form>
        </td>
        </tr>
    <?php endforeach; ?>
    <?php if (empty($record)) { ?>
        <tr>
            <td colspan="6" class="danger text-center"><?php echo $result; ?></td>
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

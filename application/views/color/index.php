<!DOCTYPE html>
<html>
<head>
    <?php echo $this->load->view('head'); ?>
    <script src="<?php echo base_url().RES_DIR; ?>/js/main.js"></script>
</head>
<body>
<?php echo $this->load->view('header'); ?>
<?php $i = $no; $ctlr = "color"; ?>
<h4 class="text-center text-success"><?php echo $success; ?></h4>
<div class="container-fluid">
<div class="tab-content">
<div class="table-responsive">
   <table class="table table-bordered table-hover">
    <thead>
      <tr class="info">
         <th class=' fixed-10'><?php echo anchor($ctlr . '/0/warna/'.$order, 'No.', 'title="Urutkan"'); ?></th>
         <th class=''><?php echo anchor($ctlr . '/0/warna/'.$order, 'Warna', 'title="Urutkan Warna"'); ?></th>
        <?php if ($this->authorization->is_permitted(['update_warna'])): ?>
         <th class=' fixed-10'>Edit</th>
        <?php endif; ?>
        <?php if ($this->authorization->is_permitted(['delete_warna'])): ?>
         <th class=' fixed-10'>Del</th>
        <?php endif; ?>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($records as $record): ?>
        <tr class="<?php $class = (($i % 2) == 0) ? "success" : "warning"; echo $class; ?>">
        <td class=' fixed-10'><?php echo $i++ ?></td>
        <td class=''><?php echo $record['warna']; ?></td>
        <?php $hiddenid = array('dataid' => $record['id']); ?>
        <?php if ($this->authorization->is_permitted(['update_warna'])): ?>
        <td class=' fixed-10'>
        <?php echo form_open($ctlr . '/edit', $attributes, $hiddenid); ?>
            <button class="btn btn-primary btn-mini" type="submit">
                <i class="icon-pencil icon-white"></i>
            </button>
        </form>
        </td>
        <?php endif; ?>
        <?php if ($this->authorization->is_permitted(['delete_warna'])): ?>
        <td class=' fixed-10'>
        <?php echo form_open($ctlr . '/del', $attributes, $hiddenid); ?>
            <button type="button" class="btn btn-danger btn-mini delete-event" data-dismiss="modal" data-url="#" data-confirmation="Yakin akan menghapus '<?php echo $record['warna'];?>'?" data-confirmation-title="Konfirmasi">
                <i class="icon-trash icon-white"></i>
            </button>
        </form>
        </td>
        <?php endif; ?>
        </tr>
    <?php endforeach; ?>
    <?php if (empty($record)) { ?>
        <tr>
            <td colspan="4" class="danger text-center"><?php echo $result; ?></td>
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

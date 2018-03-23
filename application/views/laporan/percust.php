<!DOCTYPE html>
<html>
<head>
    <?php echo $this->load->view('head'); ?>
    <script src="<?php echo base_url().RES_DIR; ?>/js/main.js"></script>
</head>
<body>
<?php echo $this->load->view('header'); $i = $no; $ctlr = "laporan/percust"; ?>
<h4 class="text-center text-success"><?php echo $success; ?></h4>
<div class="container-fluid">
<div class="tab-content">
<div class="table-responsive">
   <table class="table table-hover">
    <caption><?php echo 'Laporan Item Boneka per customer Tanggal ' .$start.' sampai dengan '.$stop; ?></caption>
    <thead>
      <tr>
         <th class='success fixed-10'><?php echo anchor($ctlr . '/0/itemjahitan/'.$order, 'No.', 'title="Urutkan No"'); ?></th>
         <th class='warning'><?php echo anchor($ctlr . '/0/nama/'.$order, 'Nama', 'title="Urutkan Nama"'); ?></th>
         <th class='success'><?php echo anchor($ctlr . '/0/itemjahitan/'.$order, 'Item', 'title="Urutkan Item"'); ?></th>
         <th class='warning'><?php echo anchor($ctlr . '/0/jml/'.$order, 'Jumlah', 'title="Urutkan Jumlah"'); ?></th>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($records as $record): ?>
        <tr>
        <td class='success fixed-10'><?php echo $i++ ?></td>
        <td class='warning'><a href="javascript:void(0);" data-toggle="popover" data-html="true" data-content="<?php echo nl2br($record['title']); ?>"><?php echo $record['nama']; ?></a></td>
        <td class='success'><?php echo $record['itemjahitan'].' '.$record['bahanboneka'].' '.$record['warnaboneka']; ?></td>
        <td class='warning'><div class="right"><?php echo number_format($record['jml'], 0, ',', '.'); ?></div></td>
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

</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <?php echo $this->load->view('head'); ?>
</head>
<body>
<?php echo $this->load->view('header'); $i = $no; $ctlr = "laporan/avgbhn"; ?>
<h4 class="text-center text-success"><?php echo $success; ?></h4>
<div class="container-fluid">
<div class="tab-content">
<div class="table-responsive">
   <table class="table table-hover">
    <caption><?php echo 'Laporan Rata2 Konsumsi Bahan dari Tanggal '.$start.' sampai dengan '.$stop; ?></caption>
    <thead>
      <tr>
         <th class='success fixed-10'><?php echo anchor($ctlr . '/0/itemjahitan/'.$order, 'No.', 'title="Urutkan No"'); ?></th>
         <th class='warning'><?php echo anchor($ctlr . '/0/itemjahitan/'.$order, 'Item', 'title="Urutkan Item"'); ?></th>
         <th class='success'><?php echo anchor($ctlr . '/0/bahan/'.$order, 'Bahan', 'title="Urutkan Bahan"'); ?></th>
         <th class='warning'><?php echo anchor($ctlr . '/0/color/'.$order, 'Warna', 'title="Urutkan Warna"'); ?></th>
         <th class='success'><?php echo anchor($ctlr . '/0/ratarata/'.$order, 'Rata2 Item per Yard', 'title="Urutkan Rata-Rata"'); ?></th>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($records as $record): ?>
        <tr>
        <td class='success fixed-10'><?php echo $i++ ?></td>
        <td class='warning'><?php echo $record['itemjahitan']; ?></td>
        <td class='success'><?php echo $record['bahan']; ?></td>
        <td class='warning'><?php echo $record['color']; ?></td>
        <td class='success'><div class="right"><?php echo number_format($record['ratarata'], 2, ',', '.'); ?></div></td>
        </tr>
    <?php endforeach; ?>
    <?php if (empty($record)) { ?>
        <tr>
            <td colspan="5" class="danger text-center"><?php echo $result; ?></td>
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

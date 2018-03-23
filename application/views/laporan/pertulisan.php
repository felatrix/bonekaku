<!DOCTYPE html>
<html>
<head>
    <?php echo $this->load->view('head'); ?>
</head>
<body>
<?php echo $this->load->view('header'); $i = $no; $ctlr = "laporan/pertulisan"; ?>
<h4 class="text-center text-success"><?php echo $success; ?></h4>
<div class="container-fluid">
<div class="tab-content">
<div class="table-responsive">
   <table class="table table-hover">
    <caption><?php echo 'Laporan Jenis Tulisan \''.$jenis.'\' Tanggal '.$start.' sampai dengan '.$stop; ?></caption>
    <thead>
      <tr>
         <th class='success fixed-10'><?php echo anchor($ctlr . '/0/itemjahitan/'.$order, 'No.', 'title="Urutkan No"'); ?></th>
         <th class='warning'><?php echo anchor($ctlr . '/0/itemjahitan/'.$order, 'Item', 'title="Urutkan Item"'); ?></th>
         <th class='success'><?php echo anchor($ctlr . '/0/jenis/'.$order, 'Jenis Tulisan', 'title="Urutkan Jenis Tulisan"'); ?></th>
         <th class='warning'><?php echo anchor($ctlr . '/0/kata/'.$order, 'Kata', 'title="Urutkan Kata"'); ?></th>
         <th class='success'><?php echo anchor($ctlr . '/0/jml/'.$order, 'Jumlah', 'title="Urutkan Jumlah"'); ?></th>
         <th class='warning'><?php echo anchor($ctlr . '/0/total/'.$order, 'Total', 'title="Urutkan Total"'); ?></th>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($records as $record): ?>
        <tr>
        <td class='success fixed-10'><?php echo $i++ ?></td>
        <td class='warning'><?php echo $record['itemjahitan'] .' (oid: '.$record['id'].')'; ?></td>
        <td class='success'><?php echo $record['jenis']; ?></td>
        <td class='warning'><?php echo $record['kata']; ?></td>
        <td class='success'><div class="right"><?php echo number_format($record['jml'], 0, ',', '.'); ?></div></td>
        <td class='warning'><div class="right"><?php echo number_format($record['total'], 0, ',', '.'); ?></div></td>
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

</body>
</html>

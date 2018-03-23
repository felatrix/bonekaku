<!DOCTYPE html>
<html>
<head>
    <?php echo $this->load->view('head'); ?>
</head>
<body>
<?php echo $this->load->view('header'); ?>
<?php $i = $no; $ctlr = "laporan/stokbhn"; ?>
<h4 class="text-center text-success"><?php echo $success; ?></h4>
<div class="container-fluid">
<div class="tab-content">
<div class="table-responsive">
   <table class="table table-bordered table-hover">
    <thead>
        <tr class="info">
         <th class=' fixed-10'><?php echo anchor($ctlr . '/0/bahan/'.$order, 'No.', 'title="Urutkan No"'); ?></th>
         <th class=''><?php echo anchor($ctlr . '/0/bahan/'.$order, 'Bahan', 'title="Urutkan Bahan"'); ?></th>
         <th class=''><?php echo anchor($ctlr . '/0/color/'.$order, 'Warna', 'title="Urutkan Warna"'); ?></th>
         <th class=''><?php echo anchor($ctlr . '/0/masuk/'.$order, 'Bahan Masuk (Yard)', 'title="Urutkan Bahan Masuk"'); ?></th>
         <th class=''><?php echo anchor($ctlr . '/0/pemakaian/'.$order, 'Pemakaian Bahan (Yard)', 'title="Urutkan Pemakaian Masuk"'); ?></th>
         <th class=''><?php echo anchor($ctlr . '/0/sisa/'.$order, 'Sisa Bahan (Yard)', 'title="Urutkan Sisa Bahan"'); ?></th>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($records as $record): ?>
        <tr class="<?php echo $class = (($i % 2) == 0) ? "success" : "warning"; ?>">
        <td class=' fixed-10'><?php echo $i++ ?></td>
        <td class=''><?php echo $record['bahan']; ?></td>
        <td class=''><?php echo $record['color']; ?></td>
        <td class=''><div class="right"><?php echo number_format($record['masuk'], 0, ',', '.'); ?></div></td>
        <td class=''><div class="right"><?php echo number_format($record['pemakaian'], 0, ',', '.'); ?></div></td>
        <td class=''><div class="right"><?php echo number_format($record['sisa'], 0, ',', '.'); ?></div></td>
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

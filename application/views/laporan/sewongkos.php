<!DOCTYPE html>
<html>
<head>
    <?php echo $this->load->view('head'); ?>
</head>
<body>
<?php echo $this->load->view('header'); $i = $no; $ctlr = "laporan/sewongkos"; ?>
<h4 class="text-center text-success"><?php echo $success; ?></h4>
<div class="container-fluid">
<div class="tab-content">
<div class="table-responsive">
   <table class="table table-hover">
    <caption><?php echo 'Laporan Ongkos Sewing '.$nama.' dari '.$start.' sampai dengan '.$stop; ?></caption>
    <thead>
      <tr>
         <th class='success fixed-10'><?php echo anchor($ctlr . '/0/itemjahitan/'.$order, 'No.', 'title="Urutkan No"'); ?></th>
         <th class='warning'><?php echo anchor($ctlr . '/0/namaitem/'.$order, 'Item', 'title="Urutkan Item"'); ?></th>
         <th class='success'><?php echo anchor($ctlr . '/0/startdate/'.$order, 'Tgl.Ambil', 'title="Urutkan Tgl.Ambil"'); ?></th>
         <th class='warning'><?php echo anchor($ctlr . '/0/finishdate/'.$order, 'Tgl.Setor', 'title="Urutkan Tgl.Setor"'); ?></th>
         <th class='success'><?php echo anchor($ctlr . '/0/jmlsetor/'.$order, 'Jml.Setor', 'title="Urutkan Jml.Setor"'); ?></th>
         <th class='warning'><?php echo anchor($ctlr . '/0/ongkos/'.$order, 'Harga Jahit', 'title="Urutkan Harga Jahit"'); ?></th>
         <th class='success'><?php echo anchor($ctlr . '/0/subtotal/'.$order, 'Sub Total', 'title="Urutkan Sub Total"'); ?></th>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($records as $record): ?>
        <tr>
        <td class='success fixed-10'><?php echo $i++ ?></td>
        <td class='warning'><?php echo $record['namaitem'];//$record['itemjahitan']; ?></td>
        <td class='success'><?php echo $record['startdate']; ?></td>
        <td class='warning'><?php echo $record['finishdate']; ?></td>
        <td class='success'><div class="right"><?php echo $record['jmlsetor']; ?></div></td>
        <td class='warning'><div class="right"><?php echo number_format($record['ongkos'], 0, ',', '.'); ?></div></td>
        <td class='success'><div class="right"><?php echo number_format($record['subtotal'], 0, ',', '.'); ?></div></td>
        </tr>
    <?php endforeach;?>
    <?php if (($i-1) == $rows && !empty($total)): ?>
        <tr class="info">
            <td colspan="6"><div class="right">Total :</div></td>
            <td><div class="right"><?php echo number_format($total, 0, ',', '.'); ?></div></td>
        </tr>
    <?php endif; ?>
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

</body>
</html>

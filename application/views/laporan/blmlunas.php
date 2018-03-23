<!DOCTYPE html>
<html>
<head>
    <?php echo $this->load->view('head'); ?>
    <script src="<?php echo base_url().RES_DIR; ?>/js/main.js"></script>
</head>
<body>
<?php echo $this->load->view('header'); $i = $no; $ctlr = "laporan/blmlunas"; ?>
<h4 class="text-center text-success"><?php echo $success; ?></h4>
<div class="container-fluid">
<div class="tab-content">
<div class="table-responsive">
   <table class="table table-hover">
    <caption><?php echo 'Laporan Customer yg belum Lunas Tanggal ' .$start.' sampai dengan '.$stop; ?></caption>
    <thead>
      <tr>
         <th class='success fixed-10'>No.</th>
         <th class='warning'><?php echo anchor($ctlr . '/0/nama/'.$order, 'Nama', 'title="Urutkan Nama"'); ?></th>
         <th class='success'><?php echo anchor($ctlr . '/0/itemjahitan/'.$order, 'Item', 'title="Urutkan Item"'); ?></th>
         <th class='warning'><?php echo anchor($ctlr . '/0/jml/'.$order, 'Jumlah', 'title="Urutkan Jumlah"'); ?></th>
         <th class='success'><?php echo anchor($ctlr . '/0/lainlain/'.$order, 'Keterangan', 'title="Urutkan Keterangan"'); ?></th>
         <th class='warning'><?php echo anchor($ctlr . '/0/jmldp/'.$order, 'Jumlah DP', 'title="Urutkan Jumlah DP"'); ?></th>
         <th class='success'><?php echo anchor($ctlr . '/0/tgldp/'.$order, 'Tanggal DP', 'title="Urutkan Tanggal DP"'); ?></th>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($records as $record): ?>
        <tr>
        <td class='success fixed-10'><?php echo $i++ ?></td>
        <td class='warning'><a href="javascript:void(0);" data-toggle="popover" data-html="true" data-content="<?php echo nl2br($record['title']); ?>"><?php echo $record['nama']; ?></a></td>
        <td class='success'><?php echo $record['itemjahitan'] .' (oid: '.$record['id'].')'; ?></td>
        <td class='warning'><?php echo $record['jml']; ?></td>
        <td class='success'><?php echo $record['lainlain']; ?></td>
        <td class='warning'><?php echo $record['jmldp']; ?></td>
        <td class='success'><?php echo $record['tgldp']; ?></td>
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

</body>
</html>

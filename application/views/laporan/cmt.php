<!DOCTYPE html>
<html>
<head>
    <?php echo $this->load->view('head'); ?>
    <link href="<?php echo base_url() . RES_DIR; ?>/css/cmt.css" rel="stylesheet"/>
</head>
<body>
<?php echo $this->load->view('header'); $i = $no; $ctlr = "laporan/cmt"; ?>
<h4 class="text-center text-success"><?php echo $success; ?></h4>
<div class="container-fluid">
<div class="tab-content">
<div class="table-responsive">
   <table class="table table-hover">
    <caption><?php echo 'Laporan CMT dari '.$start.' sampai dengan '.$stop; ?></caption>
    <thead>
      <tr>
         <th class='success fixed-10'><?php echo anchor($ctlr . '/0/nama/'.$order, 'No.', 'title="Urutkan No"'); ?></th>
         <th class='warning'><?php echo anchor($ctlr . '/0/nama/'.$order, 'Nama', 'title="Urutkan Nama"'); ?></th>
         <th class='success'><?php echo anchor($ctlr . '/0/itemjahitan/'.$order, 'Item', 'title="Urutkan Item"'); ?></th>
         <th class='warning'><?php echo anchor($ctlr . '/0/jml/'.$order, 'Split', 'title="Urutkan Split"'); ?></th>
         <th class='success'><?php echo anchor($ctlr . '/0/jmlsetor/'.$order, 'Jml.Setor', 'title="Urutkan Jml.Setor"'); ?></th>
         <th class='warning'><?php echo anchor($ctlr . '/0/startdate/'.$order, 'Tgl.Ambil', 'title="Urutkan Tgl.Ambil"'); ?></th>
         <th class='success'><?php echo anchor($ctlr . '/0/progress/'.$order, 'Progress (hari)', 'title="Urutkan Progress"'); ?></th>
         <th class='warning'><?php echo anchor($ctlr . '/0/target/'.$order, 'Target (hari)', 'title="Urutkan Target"'); ?></th>
         <th class='success'>Status</th>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($records as $record): ?>
        <tr>
        <td class='success fixed-10'><?php echo $i++ ?></td>
        <td class='warning'><?php echo $record['nama']; ?></td>
        <td class='success'><?php echo $record['itemjahitan']; ?></td>
        <td class='warning'><div class="right"><?php echo number_format($record['jml'], 0, ',', '.'); ?></div></td>
        <td class='success'><div class="right"><?php echo number_format($record['jmlsetor'], 0, ',', '.'); ?></div></td>
        <td class='warning'><?php echo $record['startdate']; ?></td>
        <td class='success'><div class="right"><?php echo number_format($record['progress'], 0, ',', '.'); ?></div></td>
        <td class='warning'><div class="right"><?php echo number_format($record['target'], 0, ',', '.'); ?></div></td>
        <td class='success'>
        <?php if(!empty($record['progress']) && !empty($record['target'])): ?>
            <?php
                $snum = ceil($record['progress']/$record['target']*100) ;
                $cstat = 'success';
                if($snum > '100'){
                    $sstat = 'Lewat';
                } else {
                    $sstat = $record['progress'].'/'.$record['target'];                                
                }                    
                if(!empty($record['jmlsetor'])){
                    $sstat = 'Selesai';
                    $cstat = 'info';
                }
//                $sstat = ($snum > '100') ? 'Lewat' : $record['progress'].'/'.$record['target']; 
            ?>
            <div class="progress progress-<?php echo $cstat;?> progress-striped prog-con">
              <div class="bar" style="width: <?php echo $snum;?>%"></div>
              <span style="text-align: center"><?php echo $sstat;?></span>
            </div>
        <?php endif; ?>        
        </td>
        </tr>
    <?php endforeach;?>
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

</body>
</html>

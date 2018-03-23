<!DOCTYPE html>
<html>
<head>
    <?php echo $this->load->view('head'); ?>
    <script src="<?php echo base_url().RES_DIR; ?>/js/main.js"></script>
</head>
<body>
<?php echo $this->load->view('header'); $i = $no; $ctlr = (empty($arsip)) ? "control" : "control/arsip"; ?>
<div class="container-fluid" >
  <script>
  $(function(){
    $('#toggle').click(function() {
                $('.gray').toggle();                
       });
    });
  </script>
<h4 class="text-right text-success"><?php echo $success; ?><button id="toggle" type="button" class="btn btn-default">Show Hide</button></h4>
</div>
<div class="container-fluid">
<div class="tab-content">
<div class="table-responsive">
   <table class="table table-bordered table-hover">
    <thead>
      <tr class="info">
          <th rowspan="2" style="vertical-align: inherit;" class='fixed-10'>No.</th>
         <th rowspan="2" style="vertical-align: inherit;" class=''><?php echo anchor($ctlr . '/0/tglorder/'.$order, 'Tgl. Order', 'title="Urutkan tgl"'); ?></th>
         <th rowspan="2" style="vertical-align: inherit;" class=''><?php echo anchor($ctlr . '/0/estdate/'.$order, 'Target Kirim', 'title="Urutkan tgl"'); ?></th>
         <th rowspan="2" style="vertical-align: inherit;" class=''><?php echo anchor($ctlr . '/0/nama/'.$order, 'Pemesan', 'title="Urutkan Nama"'); ?></th>
         <th rowspan="2" style="vertical-align: inherit;" class=''><?php echo anchor($ctlr . '/0/itemjahitan/'.$order, 'Item Boneka', 'title="Urutkan Item Boneka"'); ?></th>
         <th rowspan="2" style="vertical-align: inherit;" class=''><?php echo anchor($ctlr . '/0/jumlah/'.$order, 'Jumlah', 'title="Urutkan Jumlah"'); ?></th>
         <th rowspan="2" style="vertical-align: inherit;" class=''>Aksesoris</th>
          <th colspan="6" style="text-align: -webkit-center;">Status</th>
      </tr>
      <tr class="info">
          <th class=''>Cutting</th>
         <th class=''>Pretel</th>
         <th class=''>Sewing</th>
         <th class=''>Finishing</th>
         <th class=''>Delivered</th>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($records as $record): ?>
        <?php
            $mo_data = $this->main_model->get_single_order($record['id']);
            $split = count($this->cut_model->task_by_oid($record['id']));
            $sew_fin = count($this->sew_model->count_finish_percoid($record['id']));
            $fin_fin = count($this->fin_model->count_finish_percoid($record['id']));
            $prtl_fin = count($this->prtl_model->count_finish_percoid($record['id']));
            $cut_fin = count($this->cut_model->count_finish_percoid($record['id']));
            $cnum = $cstat = '';
            $pnum = $pstat = '';
            $snum = $sstat = '';
            $fnum = $fstat = '';
            $dnum = $dstat = '';
            if(!empty($cut_fin)) {$ccol = 'error'; $cnum = ceil($cut_fin/$split*100) ;$cstat = ($cnum == '100') ? 'Selesai' : "$cut_fin/$split";}
            if(!empty($prtl_fin)) {$pcol = 'error'; $pnum = ceil($prtl_fin/$split*100) ;$pstat = ($pnum == '100') ? 'Selesai' : "$prtl_fin/$split";}
            if(!empty($sew_fin)) {$scol = 'error'; $snum = ceil($sew_fin/$split*100) ;$sstat = ($snum == '100') ? 'Selesai' : "$sew_fin/$split";}
            if(!empty($mo_data['tglkirim']) && $mo_data['tglkirim'] != '0000-00-00') {$dcol = 'error'; $dnum = '100'; $dstat = 'Selesai';}
            if(!empty($fin_fin)) {$fcol = 'error'; $fnum = ceil($fin_fin/$split*100) ;$fstat = ($fnum == '100') ? 'Selesai' : "$fin_fin/$split";}        
        ?>

        <tr class="<?php 
            $datetime1 = new DateTime("now");
            $datetime2 = new DateTime($record['estdate']);
            $interval = $datetime1->diff($datetime2);
            $test = $interval->format('%r%a');
            $toleransi_data = $this->toleransi_model->get_single_data('1');            
            if($dnum == 100){
                $class = "gray";
            } elseif($test < (-1 * $toleransi_data['hari'])){
                $class = "merah";
            } elseif ($test >= (-1 * $toleransi_data['hari']) && $test <= -1){
                $class = "kuning";
            } else {
                $class = (($i % 2) == 0) ? "success" : "warning";
            }
            echo $class; 
            ?>">
        <td class=' fixed-10'><?php echo $i++ ?></td>
        <td class=''><?php echo $record['tglorder']; ?></td>
        <td class=''>
            <?php echo $record['estdate']; ?>
        </td>
        <td class=''><a href="javascript:void(0);" data-toggle="popover" data-html="true" data-content="<?php echo nl2br($record['title']); ?>"><?php echo $record['nama']; ?></a></td>
        <!--<td class=''><?php echo $record['itemjahitan']. ' ' .$record['bahanboneka']. ' ' .$record['warnaboneka']; ?></td>-->
        <!--<td class=''><?php echo $record['itemjahitan'] . ' (oid: ' .$record['id'] . ')'; ?></td>-->
        <td class=''><a href="javascript:void(0);" data-toggle="popover" data-html="true" data-content="<?php echo nl2br($record['itemjahitan']. ' (oid: ' .$record['id'] . ')'); ?>"><?php echo $record['bahanboneka']; ?></a></td>
        <td class=''><?php echo $record['jumlah']; ?></td>
        <td class=''>
        <?php
            echo $record['aksesoris']; 
        ?>
        </td>
        <td>
       <?php if(!empty($cut_fin)){ ?>
            <div class="progress progress-success progress-striped prog-con">
              <div class="bar" style="width: <?php echo $cnum;?>%"><?php echo $cstat;?></div>
            </div>
        <?php } ?>                    
        </td>
        <td>
       <?php if(!empty($prtl_fin)){ ?>
            <div class="progress progress-success progress-striped prog-con">
              <div class="bar" style="width: <?php echo $pnum;?>%"><?php echo $pstat;?></div>
            </div>
        <?php } ?>        
        </td>
        <td class=''><a href="javascript:void(0);" data-toggle="popover" data-html="true" data-content="<?php echo nl2br($record['tglkirim']); ?>">
        <?php if(!empty($sew_fin)){ ?>
            <div class="progress progress-success progress-striped prog-con">
              <div class="bar" style="width: <?php echo $snum;?>%"><?php echo $sstat;?></div>
            </div>
        <?php } ?></a>
        </td>
        <td>
        <?php if(!empty($fin_fin)){ ?>
            <div class="progress progress-success progress-striped prog-con">
              <div class="bar" style="width: <?php echo $fnum;?>%"><?php echo $fstat;?></div>
            </div>
        <?php } ?>        
        </td>
        <td>
        <?php if(!empty($mo_data['tglkirim']) && $mo_data['tglkirim'] != '0000-00-00'){ ?>
            <div class="progress progress-success progress-striped prog-con">
              <div class="bar" style="width: <?php echo $dnum;?>%"><?php echo $dstat;?></div>
            </div>                
        <?php } ?>
        </td>
        </tr>
    <?php endforeach; ?>
    <?php if (empty($record)) { ?>
        <tr>
            <td colspan="12" class="danger text-center"><?php echo $result; ?></td>
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

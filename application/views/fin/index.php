<!DOCTYPE html>
<html>
<head>
    <?php echo $this->load->view('head'); ?>
    <script src="<?php echo base_url().RES_DIR; ?>/js/main.js"></script>
</head>
<body>
<?php echo $this->load->view('header'); $i = $no; $ctlr = (empty($arsip)) ? "fin" : "fin/arsip"; ?>
<h4 class="text-center text-success"><?php echo $success; ?></h4>
<div class="container-fluid">
<div class="tab-content">
<div class="table-responsive">
   <table class="table table-bordered table-hover">
    <thead>
      <tr class="info">
         <th class='fixed-10'>No.</th>
         <th class=''><?php echo anchor($ctlr . '/0/itemjahitan/'.$order, 'Item Boneka', 'title="Urutkan Item Boneka"'); ?></th>
         <th class=''><?php echo anchor($ctlr . '/0/estdate/'.$order, 'Target Kirim', 'title="Urutkan tgl"'); ?></th>
         <th class=''><?php echo anchor($ctlr . '/0/jumlah/'.$order, 'Jumlah', 'title="Urutkan Jumlah"'); ?></th>
         <th class=''>Aksesoris</th>
         <th class=''>Jenis Tulisan</th>
         <th class=''>Tulisan</th>
         <th class=''>Lain-Lain</th>
         <th class=''>Selesai</th>
         <th class=''>Sisa</th>
         <th class=''>Pekerja</th>
         <th class=''>Tgl. Update</th>
         <th class=' fixed-10'>Set</th>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($records as $record): ?>
        <tr class="<?php $class = (($i % 2) == 0) ? "success" : "warning"; echo $class; ?>">
        <td class=' fixed-10'><?php echo $i++ ?></td>
        <td class=''><?php echo $record['itemjahitan'] . ' (oid: ' .$record['oid'] . ')'; ?></td>
        <td class=''>
            <?php // echo $record['estdate']; ?>
        </td>
        <td class=''><?php // echo $record['jml']; ?></td>
        <td class=''><?php echo $record['aksesoris']; ?></td>
        <td class=''><?php echo $record['jenistulisan']; ?></td>
        <td class=''><?php echo $record['tulisan']; ?></td>
        <td class=''><?php echo $record['lainlain']; ?></td>
        <td class=''>
        <?php
//            $staff = $this->fin_model->pekerja_by_oid($record['id']);
//            $tgl = $this->fin_model->get_data_by_oid($record['id'], FALSE, 1, '', 'tanggal', 'desc');
//            $sum_data = $this->fin_model->sum_data_by_oid($record['id'], 'selesai');
//            $sum_selesai = (!empty($sum_data)) ? $sum_data['selesai'] : '';
//            echo $sum_selesai;
        ?>
        </td>
        <td class=''><?php // echo ($record['jumlah'] - $sum_selesai); ?></td>
        <td class=''><?php // echo (!empty($staff)) ? $staff['pekerja'] : ''; ?></td>
        <td class=''><?php // echo (!empty($tgl)) ? $tgl[0]['tanggal'] : ''; ?></td>
        <td class=' fixed-10'>
<!--        <?php
//        $hiddenid = array('oid' => $record['id']);
//        $cekbhn = $this->fin_model->count_data_by_oid($record['id'],$record['coid']);
//        $icocol = ($cekbhn) ? '' : ' icon-white';
//        echo form_open($ctlr . '/set', $attributes, $hiddenid); ?>
            <button type="submit" class="btn btn-success btn-mini" title="Set pekerja">
                <i class="icon-th<?php // echo $icocol; ?>"></i>
            </button>
        </form>-->
        </td>
        </tr>
        <?php
//        $focoasc = ($order == 'asc') ? 'desc' : 'asc';
        $sos = $this->fin_model->foco_by_oid($record['oid'], FALSE, NULL, '', $focoorderby, $focoasc);
       foreach ($sos as $co){
        ?>
        <tr class="<?php echo $class;?>">
            <td class=' fixed-10'></td>
            <td class=''><div class="right"><?php
                if($this->unread->cek_unread('sew', $co['oid'], $co['coid'])){
                    echo '<span id="unread" class="badge badge-info sew">Baru</span>';
                }
                echo ' oid: ' .$co['oid'] . '.' . $co['coid'] . '';
            ?></div></td>
            <td class=''><?php echo $co['estdate']; ?></td>
            <td class=''><?php echo $co['jumlah']; ?></td>
            <td class=''></td>
            <td class=''></td>
            <td class=''></td>
            <td class=''></td>
            <td class=''>
            <?php 
                $staff = $this->fin_model->pekerja_by_oid($co['oid'],$co['coid']);
                $tgl = $this->fin_model->get_data_by_oid($co['oid'], FALSE, 1, '', 'tanggal', 'desc',$co['coid']);
                $sum_data = $this->fin_model->sum_data_by_oid($co['oid'],$co['coid'], 'selesai');
                $sum_selesai = (!empty($sum_data)) ? $sum_data['selesai'] : '';
                echo $sum_selesai; 
            ?>
            </td>
            <td class=''>
            <?php
                $sisa = $co['jumlah'] - $sum_data['selesai'];
                $status = ($sum_data['selesai'] >= $co['jumlah']) ? 'Selesai' : ((!empty($staff['pekerja'])) ? 'Pengerjaan' : '');
                echo $sisa;
            ?>                
            </td>
            <td class=''><?php echo (!empty($staff)) ? $staff['pekerja'] : ''; ?></td>
            <td class=''><?php echo (!empty($tgl)) ? $tgl[0]['tanggal'] : ''; ?></td>
            <td class=' fixed-10'>
                <?php
                $hiddenid = array('oid' => $co['oid'], 'coid' => $co['coid']);
                $cekbhn = $this->fin_model->count_data_by_oid($co['oid'],$co['coid']);
                $icocol = ($cekbhn) ? '' : ' icon-white';
                echo form_open($ctlr . '/set', $attributes, $hiddenid); ?>
                    <button type="submit" class="btn btn-success btn-mini" title="Set Pekerja">
                        <i class="icon-th<?php echo $icocol; ?>"></i>
                    </button>
                </form>
            </td>
            </tr>        
        <?php
        }
        ?>        
    <?php endforeach; ?>
    <?php if (empty($record)) { ?>
        <tr>
            <td colspan="13" class="danger text-center"><?php echo $result; ?></td>
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

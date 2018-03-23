<!DOCTYPE html>
<html>
<head>
    <?php echo $this->load->view('head'); ?>
    <script src="<?php echo base_url().RES_DIR; ?>/js/main.js"></script>
</head>
<body>
<?php echo $this->load->view('header'); $i = $no; $ctlr = (empty($arsip)) ? "prtl" : "prtl/arsip"; ?>
<h4 class="text-center text-success"><?php echo $success; ?></h4>
<div class="container-fluid">
<div class="tab-content">
<div class="table-responsive">
   <table class="table table-bordered table-hover">
    <thead>
      <tr class="info">
         <th class='fixed-10'>No.</th>
         <th class=''><?php echo anchor($ctlr . '/0/itemjahitan/'.$order, 'Item Boneka', 'title="Urutkan Item Boneka"'); ?></th>
         <th class=''><?php echo anchor($ctlr . '/0/date/'.$order, 'Tgl. Selesai Cutting', 'title="Urutkan tgl"'); ?></th>
         <th class=''><?php echo anchor($ctlr . '/0/jml/'.$order, 'Jumlah', 'title="Urutkan Jumlah"'); ?></th>
         <th class=''>Status</th>
         <th class=''><?php echo anchor($ctlr . '/0/selesai/'.$order, 'Tgl. Selesai', 'title="Urutkan Tgl. Selesai"'); ?></th>
         <th class=''>Pekerja</th>
        <?php if ($this->authorization->is_permitted(['update_po','create_po'])): ?>
         <th class=' fixed-10'>Set</th>
        <?php endif; ?>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($records as $record): ?>
        <tr class="<?php $class = (($i % 2) == 0) ? "success" : "warning"; echo $class; ?>">
        <td class=' fixed-10'><?php echo $i++ ?></td>
        <td class=''><?php echo $record['itemjahitan']/*. ' ' .$record['bahanboneka']. ' ' .$record['warnaboneka']*/ . ' (oid: ' .$record['oid'] . ')'; ?></td>
        <td class=''><?php // echo ($orderby != 'itemjahitan') ? $record['date'] : ''; ?></td>
        <td class=''><?php // echo ($orderby != 'itemjahitan') ? $record['jml'] : ''; ?></td>
        <td class=''>
        <?php
            if($orderby != 'itemjahitan'){
//                echo ($record['status'] == 1) ? "Selesai" : (isset($record['status']) ? "Pengerjaan" : ""); 
            }
        ?></td>
        <td class=''><?php // echo ($orderby != 'itemjahitan') ? ($record['selesai'] == '0000-00-00' ? '' : $record['selesai']) : ''; ?></td>
        <td class=''><?php // echo ($orderby != 'itemjahitan') ? $record['nama'] : ''; ?></td>
        <?php if ($this->authorization->is_permitted(['update_po','create_po'])): ?>
        <td class=' fixed-10'>
        <?php
            /*if($orderby != 'itemjahitan'){
                $hiddenid = ['oid' => $record['oid'], 'dataid' => $record['poid'], 'coid' => $record['id']];
                $gourl = (empty($record['poid'])) ? '/insert' : '/edit';
                echo form_open($ctlr . $gourl, $attributes, $hiddenid); ?>
            <button class="btn btn-primary btn-mini" title="Edit penugasan" type="submit">
                <i class="icon-pencil icon-white"></i>
            </button>
        </form>
            <?php }*/ ?>
        </td>
        <?php endif; ?>
        </tr>
        <?php
//        if($orderby == 'itemjahitan'){
        $pos = $this->cut_model->poco_by_oid($record['oid'], FALSE, NULL, '', $pocoorderby, $pocoasc);
        foreach ($pos as $co){ 
        ?>
        <tr class="<?php echo $class;?>">
            <td class=' fixed-10'></td>
            <td class=''>
                <div class="right"><?php
                        if($this->unread->cek_unread('cut', $record['oid'], $co['id'])){
                            echo '<span id="unread" class="badge badge-info cut">Baru</span>';
                        }
                        echo ' oid: ' .$co['oid'] . '.' . $co['id'] . '';
                ?></div>
            </td>
            <td class=''><?php echo $co['date']; ?></td>
            <td class=''><?php echo $co['jml']; ?></td>
            <td class=''><?php echo ($co['status'] == 1) ? "Selesai" : (isset($co['status']) ? "Pengerjaan" : ""); ?></td>
            <td class=''><?php echo ($co['selesai'] == '0000-00-00') ? '' : $co['selesai']; ?></td>
            <td class=''><?php echo $co['nama']; ?></td>
            <?php if ($this->authorization->is_permitted(['update_po','create_po'])): ?>
            <td class=' fixed-10'>
                <?php $hiddenid = ['oid' => $co['oid'], 'dataid' => $co['poid'], 'coid' => $co['id']];
                $gourl = (empty($co['poid'])) ? '/insert' : '/edit';
                echo form_open($ctlr . $gourl, $attributes, $hiddenid); ?>
                <button class="btn btn-primary btn-mini" title="Edit penugasan" type="submit">
                    <i class="icon-pencil icon-white"></i>
                </button>
            </form>
            </td>
            <?php endif; ?>
            </tr>        
        <?php
        }
//        }
        ?>        
    <?php endforeach; ?>
    <?php if (empty($record)) { ?>
        <tr>
            <td colspan="8" class="danger text-center"><?php echo $result; ?></td>
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

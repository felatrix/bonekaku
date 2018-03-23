<!DOCTYPE html>
<html>
<head>
    <?php echo $this->load->view('head'); ?>
    <script src="<?php echo base_url().RES_DIR; ?>/js/main.js"></script>
</head>
<body>
<?php echo $this->load->view('header'); $i = $no; $ctlr = (empty($arsip)) ? "cut" : "cut/arsip"; ?>
<h4 class="text-center text-success"><?php echo $success; ?></h4>
<div class="container-fluid">
<div class="tab-content">
<div class="table-responsive">
   <table class="table table-bordered table-hover">
    <thead>
      <tr class="info">
         <th class='fixed-10'><?php echo anchor($ctlr . '/0/tglorder/'.$order, 'No.', 'title="Urutkan"'); ?></th>
         <th class=''><?php echo anchor($ctlr . '/0/tglorder/'.$order, 'Tgl. Order', 'title="Urutkan Tgl. Order"'); ?></th>
         <th class=''><?php echo anchor($ctlr . '/0/itemjahitan/'.$order, 'Item Boneka', 'title="Urutkan Item Boneka"'); ?></th>
         <th class=''><?php echo anchor($ctlr . '/0/jumlah/'.$order, 'Jumlah', 'title="Urutkan Jumlah"'); ?></th>
         <th class=''>Split</th>
         <th class=''>Jenis Tulisan</th>
         <th class=''>Tulisan</th>
         <th class=''>Warna Bordir</th>
         <th class=''>Lain-Lain</th>
         <th class=''>Pekerja</th>
         <th class=''>Tanggal</th>
         <th class=' fixed-10'>Set</th>
        <?php if ($this->authorization->is_permitted(['delete_co'])): ?>
         <th class=' fixed-10'>Del</th>
        <?php endif; ?>
        <?php if ($this->authorization->is_permitted(['retrieve_bhn'])): ?>
         <th class=' fixed-10'>Bhn</th>
        <?php endif; ?>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($records as $record): ?>
        <tr class="<?php $class = (($i % 2) == 0) ? "success" : "warning"; echo $class; ?>">
        <td class=' fixed-10'><?php echo $i++ ?></td>
        <td class=''><?php echo $record['tglorder']; ?></td>
        <!--<td class=''><?php echo $record['itemjahitan']. ' ' .$record['bahanboneka']. ' ' .$record['warnaboneka']; ?></td>-->
        <td class=''>
            <?php echo $record['itemjahitan'] . ' (oid: ' .$record['id'] . ')'; ?>
            <?php
                if($this->unread->cek_unread('main', $record['id'])){
                    echo '<span id="unread" class="badge badge-info main">Baru</span>';
                }
            ?>
        </td>
        <td class=''><?php echo $record['jumlah']; ?></td>
        <td class=''></td>
        <td class=''>
        <?php 
            $cektls = $this->tulisan_model->count_data_by_oid($record['id']);
            $icocol = ($cektls) ? '' : ' icon-white';
            echo form_open('tulisan/set', ['role' => 'form', 'style' => 'margin: auto'], ['oid' => $record['id']]);
            echo $record['jenistulisan']; ?>
            <div class="pull-right">
                <button class="btn btn-info btn-mini" title="Tambah aktivitas" type="submit"<?php echo $this->authorization->is_permitted(['retrieve_tulisan'])?'':' disabled';?>>
                    <i class="icon-font<?php echo $icocol; ?>"></i>
                </button>
            </div>
            </form>
        </td>
        <td class=''><?php echo $record['tulisan']; ?></td>
        <td class=''><?php echo $record['warnabordir']; ?></td>
        <td class=''><?php echo $record['lainlain']; ?></td>
        <td class=''></td>
        <td class=''></td>
        <td class=' fixed-10'>
        <?php echo form_open($ctlr . '/insert', ['role' => 'form', 'style' => 'margin: auto'], ['oid' => $record['id']]); ?>
            <button class="btn btn-primary btn-mini"<?php echo $this->authorization->is_permitted(['create_co'])?'':' disabled ';?>title="Tambah penugasan" type="submit">
                <i class="icon-plus icon-white"></i>
            </button>
        </form>
        <?php $hiddenid = array('oid' => $record['id']);
              //echo form_open($ctlr . '/set', $attributes, $hiddenid); ?>
            <!--button class="btn btn-primary btn-mini" type="submit">
                <i class="icon-list icon-white"></i>
            </button>
        </form-->
        </td>
        <?php if ($this->authorization->is_permitted(['delete_co'])): ?>
        <td class=' fixed-10'></td>
        <?php endif; ?>
        <?php if ($this->authorization->is_permitted(['retrieve_bhn'])): ?>
        <td class=' fixed-10'>
        <?php
        $cekbhn = $this->bhn_model->count_data_by_oid($record['id']);
        $icocol = ($cekbhn) ? '' : ' icon-white';
        echo form_open('bhn/set', $attributes, $hiddenid); ?>
            <button type="submit" class="btn btn-success btn-mini" title="Set kebutuhan bahan">
                <i class="icon-briefcase<?php echo $icocol; ?>"></i>
            </button>
        </form>
        </td>
        <?php endif; ?>
        </tr>
        <?php
        $cutorders = $this->cut_model->get_data_by_oid($record['id']);
        foreach ($cutorders as $co){ 
        ?>
        <tr class="<?php echo $class;?>">
            <td class=' fixed-10'></td>
            <td class=''></td>
            <td class=''><div class="right"><?php echo 'oid: ' .$record['id'] . '.' . $co['id'] . ''?></div></td>
            <td class=''></td>
            <td class=''><?php echo $co['jml']; ?></td>
            <td class=''></td>
            <td class=''></td>
            <td class=''></td>
            <td class=''></td>
            <td class=''><?php echo $co['nama']; ?></td>
            <td class=''><?php echo $co['date']; ?></td>
            <td class=' fixed-10'>
            <?php $hiddenid = ['oid' => $co['oid'], 'dataid' => $co['id']];
                  echo form_open($ctlr . '/edit', $attributes, $hiddenid); ?>
                <button class="btn btn-primary btn-mini" title="Edit penugasan" type="submit"<?php echo $this->authorization->is_permitted(['update_co'])?'':' disabled '; ?>>
                    <i class="icon-pencil icon-white"></i>
                </button>
            </form>
            </td>
            <?php if ($this->authorization->is_permitted(['delete_co'])): ?>
            <td class=' fixed-10'>
            <?php echo form_open($ctlr . '/del', $attributes, $hiddenid); ?>
                <button type="button" class="btn btn-danger btn-mini delete-event" title="Hapus penugasan" data-dismiss="modal" data-url="#" data-confirmation="Yakin akan menghapus penugasan kepada '<?php echo $co['nama'];?>'?" data-confirmation-title="Konfirmasi">
                    <i class="icon-trash icon-white"></i>
                </button>
            </form>
            </td>
            <?php endif; ?>
            <?php if ($this->authorization->is_permitted(['retrieve_bhn'])): ?>
            <td class=' fixed-10'></td>
            <?php endif; ?>
            </tr>        
        <?php
        }
        ?>
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

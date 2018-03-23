<!DOCTYPE html>
<html>
<head>
    <?php echo $this->load->view('head'); ?>
    <script src="<?php echo base_url().RES_DIR; ?>/js/main.js"></script>
</head>
<body>
<?php echo $this->load->view('header'); $i = $no; ?>
<h4 class="text-center text-success"><?php echo $success; ?></h4>
<div class="container-fluid">
<div class="tab-content">
<div class="table-responsive">
   <table class="table table-bordered table-hover">
    <thead>
      <tr class="info">
         <th class='fixed-10'>No.</th>
         <th class=''><?php echo anchor('main/0/tglorder/'.$order, 'Tgl. Order', 'title="Urutkan tgl"'); ?></th>
         <th class=''><?php echo anchor('main/0/itemboneka/'.$order, 'Item Boneka', 'title="Urutkan Item Boneka"'); ?></th>
         <th class=''><?php echo anchor('main/0/jumlah/'.$order, 'Jumlah', 'title="Urutkan Jumlah"'); ?></th>
         <th class=''>Split</th>
         <th class=''><?php echo anchor('main/0/jenistulisan/'.$order, 'Jenis Tulisan', 'title="Urutkan Jenis Tulisan"'); ?></th>
         <th class=''>Tulisan</th>
         <th class=''>Warna Bordir</th>
         <th class=''>Lain-Lain</th>
         <th class=''>Pekerja</th>
         <th class=''>Tanggal</th>
         <th class=' fixed-10'>Set</th>
         <th class=' fixed-10'>Del</th>
         <th class=' fixed-10'>Bhn</th>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($records as $record): ?>
        <tr class="<?php $class = (($i % 2) == 0) ? "success" : "warning"; echo $class; ?>">
        <td class=' fixed-10'><?php echo $i++ ?></td>
        <td class=''><?php echo $record['tglorder']; ?></td>
        <td class=''><?php echo $record['itemboneka']; ?></td>
        <td class=''><?php echo $record['jumlah']; ?></td>
        <td class=''></td>
        <td class=''><?php echo $record['jenistulisan']; ?></td>
        <td class=''><?php echo $record['tulisan']; ?></td>
        <td class=''><?php echo $record['warnabordir']; ?></td>
        <td class=''><?php echo $record['lainlain']; ?></td>
        <td class=''></td>
        <td class=''></td>
        <td class=' fixed-10'>
        <?php echo form_open('cut/insert', ['role' => 'form', 'style' => 'margin: auto'], ['oid' => $record['id']]); ?>
            <button class="btn btn-primary btn-mini" title="Tambah penugasan" type="submit">
                <i class="icon-plus icon-white"></i>
            </button>
        </form>
        <?php $hiddenid = array('orderid' => $record['id']);
              //echo form_open('cut/set', $attributes, $hiddenid); ?>
            <!--button class="btn btn-primary btn-mini" type="submit">
                <i class="icon-list icon-white"></i>
            </button>
        </form-->
        </td>
        <td class=' fixed-10'></td>
        <td class=' fixed-10'>
        <?php echo form_open('bhn/set', $attributes, $hiddenid); ?>
            <button type="button" class="btn btn-success btn-mini delete-event" data-dismiss="modal" data-url="#" data-confirmation="Yakin akan menghapus order '<?php echo $record['itemboneka'];?>' ini?" data-confirmation-title="Konfirmasi">
                <i class="icon-briefcase icon-white"></i>
            </button>
        </form>
        </td>
        </tr>
        <?php
        $cutorders = $this->cut_model->get_data_by_oid($record['id']);
        foreach ($cutorders as $co){ 
        ?>
        <tr class="<?php echo $class;?>">
            <td class=' fixed-10'></td>
            <td class=''></td>
            <td class=''></td>
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
                  echo form_open('cut/edit', $attributes, $hiddenid); ?>
                <button class="btn btn-primary btn-mini" title="Edit penugasan" type="submit">
                    <i class="icon-pencil icon-white"></i>
                </button>
            </form>
            </td>
            <td class=' fixed-10'>
            <?php echo form_open('cut/del', $attributes, $hiddenid); ?>
                <button type="button" class="btn btn-danger btn-mini delete-event" title="Hapus penugasan" data-dismiss="modal" data-url="#" data-confirmation="Yakin akan menghapus penugasan kepada '<?php echo $co['nama'];?>'?" data-confirmation-title="Konfirmasi">
                    <i class="icon-trash icon-white"></i>
                </button>
            </form>
            </td>
            <td class=' fixed-10'></td>
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

<!DOCTYPE html>
<html>
    <head>
        <?php echo $this->load->view('head'); ?>
        <script src="<?php echo base_url() . RES_DIR; ?>/js/main.js"></script>
    </head>
    <body>
        <?php echo $this->load->view('header'); ?>
        <!--div class = "page-header"-->
        <!--h2 class="text-center"><?php /* echo $title; */ $i = $no; ?></h2>
        <br-->
        <!--/div-->
        <h4 class="text-center text-success"><?php echo $success; ?></h4>
        <div class="container-fluid">
            <div class="tab-content">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th class='success fixed-10'><?php echo anchor('client/0/nama/' . $order, 'No.', 'title="Urutkan No"'); ?></th>
                                <th class='warning'><?php echo anchor('client/0/nama/' . $order, 'Nama', 'title="Urutkan Nama"'); ?></th>
                                <th class='success'><?php echo anchor('client/0/jeniskontak/' . $order, 'Jenis Kontak', 'title="Urutkan Jenis Kontak"'); ?></th>
                                <th class='warning'><?php echo anchor('client/0/nomer/' . $order, 'Nomer', 'title="Urutkan Nomer"'); ?></th>
                                <th class='success'><?php echo anchor('client/0/alamat/' . $order, 'Alamat', 'title="Urutkan Alamat"'); ?></th>
                                <?php if ($this->authorization->is_permitted(['update_pelanggan'])): ?>
                                    <th class='warning fixed-10'>Edit</th>
                                <?php endif; ?>
                                <?php if ($this->authorization->is_permitted(['delete_pelanggan'])): ?>
                                    <th class='success fixed-10'>Del</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($records as $record): ?>
                                <tr>
                                    <td class='success fixed-10'><?php echo $i++ ?></td>
                                    <td class='warning'><?php echo $record['nama']; ?></td>
                                    <td class='success'><?php echo $record['jeniskontak']; ?></td>
                                    <td class='warning'><?php echo $record['nomer']; ?></td>
                                    <td class='success'><?php echo nl2br($record['alamat']); ?></td>
                                    <?php $hiddenid = array('dataid' => $record['id']); ?>
                                    <?php if ($this->authorization->is_permitted(['update_pelanggan'])): ?>
                                        <td class='warning fixed-10'>
                                            <?php echo form_open('client/edit', $attributes, $hiddenid); ?>
                                            <button class="btn btn-primary btn-mini" type="submit">
                                                <i class="icon-pencil icon-white"></i>
                                            </button>
                                            </form>
                                        </td>
                                    <?php endif; ?>
                                    <?php if ($this->authorization->is_permitted(['delete_pelanggan'])): ?>
                                        <td class='success fixed-10'>
                                            <?php echo form_open('client/del', $attributes, $hiddenid); ?>
                                            <button type="button" class="btn btn-danger btn-mini delete-event" data-dismiss="modal" data-url="#" data-confirmation="Yakin akan menghapus '<?php echo $record['nama']; ?>'?" data-confirmation-title="Konfirmasi">
                                                <i class="icon-trash icon-white"></i>
                                            </button>
                                            </form>
                                        </td>
                                    <?php endif; ?>
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

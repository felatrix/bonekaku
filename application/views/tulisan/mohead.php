<div class="container-fluid">
    <div class="tab-content">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <td class="success"><div class="text-left">Orderid:</div></td><td class="success"><div class="text-right"><?php echo $data['id']; ?></div></td>
                        <td class="warning"><div class="text-left">Item:</div></td><td class="warning"><div class="text-right"><?php echo $data['itemjahitan']. ' ' .$data['bahanboneka']. ' ' .$data['warnaboneka']; ?></div></td>
                        <td class="info"><div class="text-left">Jumlah:</div></td><td class="info"><div class="text-right"><?php echo $data['jumlah']; ?></div></td>
                        <td class="success"><div class="text-left">Jenis Tulisan:</div></td><td class="success"><div class="text-right"><?php echo $data['jenistulisan']; ?></div></td>
                        <td class="warning"><div class="text-left">Tulisan:</div></td><td class="warning"><div class="text-right"><?php echo $data['tulisan']; ?></div></td>
                        <?php if($mode == 'list') { ?>
                        <td class="error fixed-10">Kegiatan</td>
                        <td class='error fixed-10'>
                            <?php echo form_open($ctlr . '/insert', ['role' => 'form', 'style' => 'margin: auto'], ['oid' => $data['id']]); ?>
                            <button class="btn btn-primary btn-mini" title="Tambah pemakaian" type="submit">
                                <i class="icon-plus icon-white"></i>
                            </button>
                            </form>
                        </td>
                        <?php } ?>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<!--div class="container-fluid">
<div class="row-fluid">
    <div class="span1"><div class="bg-success"> Orderid: <?php echo $data['id']; ?></div></div>
    <div class="warning span1"><div class="text-left">Item:</div></div><div class="warning span1"><div class="text-right"><?php echo $data['itemboneka']; ?></div></div>
    <div class="info span1"><div class="text-left">Jumlah:</div></div><div class="info span1"><div class="text-right"><?php echo $data['jumlah']; ?></div></div>
    <div class="success span1"><div class="text-left">Jenis Tulisan:</div></div><div class="success span1"><div class="text-right"><?php echo $data['jenistulisan']; ?></div></div>
    <div class="warning span1"><div class="text-left">Tulisan:</div></div><div class="warning span1"><div class="text-right"><?php echo $data['tulisan']; ?></div></div>
    <div class="error span1">Kegiatan</div>
    <div class='error span1'>
        <?php echo form_open($ctlr . '/insert', ['role' => 'form', 'style' => 'margin: auto'], ['oid' => $data['id']]); ?>
        <button class="btn btn-primary btn-mini" title="Tambah pemakaian" type="submit">
            <i class="icon-plus icon-white"></i>
        </button>
        </form>
    </div>
</div>
</div-->
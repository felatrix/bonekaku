<!DOCTYPE html>
<html>
    <head>
        <?php echo $this->load->view('head'); ?>
        <link href="<?php echo base_url() . RES_DIR; ?>/bootstrap/css/bootstrap-datepicker3.css" rel="stylesheet"/>
    </head>
    <body>
        <?php
        echo $this->load->view('header');
        $i = $no;
        $ctlr = "laporan";
        ?>
        <h4 class="text-center text-success"><?php echo $success; ?></h4>
        <div class="container-fluid">
            <div class="row-fluid">
                <div class="span12 well form-horizontal">
                    <div class="control-group row-fluid">
                        <label class="control-label" for="start">Pilih Durasi Tanggal :</label>
                        <div class="controls">
                <!--            <div class="input-append date" id="dpa" data-date="<?php echo date('Y-m'); ?>" data-date-format="yyyy-mm" data-date-min-view-mode="1">
                                <input type="text" class="input-mini" id="tgl" name="tgl" value="<?php echo date('Y-m'); ?>"  />
                                <span class="add-on"><i class="icon-th"></i></span>
                            </div>-->
                            <div class="input-daterange" id="datepicker">
                                <input type="text" class="input-small" id="start" name="start" value="<?php echo $start; ?>" />
                                <span class="add-on"> sampai </span>
                                <input type="text" class="input-small" id="end" name="end" value="<?php echo $stop; ?>" />
                            </div>
                        </div>
                    </div>
                    <div class="control-group row-fluid">
                        <?php
                        $hiddenid = ['starth' => $start, 'endh' => $stop];
                        // $hiddenid = ['bln' => date('Y-m'),'starth' => date('Y-m-d'),'endh' => date('Y-m-d')];
                        $attributes = ['role' => 'form', 'style' => 'display: inline-block'];
                        if ($this->authorization->is_permitted(['laporan_percust'])):
                            echo form_open($ctlr . '/percust', $attributes, $hiddenid);
                            ?>
                            <button type="submit" class="btn btn-primary">Boneka per customer</button>    
                            </form>
                            <?php
                        endif;
                        $attributes = ['role' => 'form', 'style' => 'display: inline-block'];
                        if ($this->authorization->is_permitted(['laporan_top10'])):
                            echo form_open($ctlr . '/topten', $attributes, $hiddenid);
                            ?>
                            <button type="submit" class="btn btn-primary">Top 10 Customer</button>    
                            </form>
                            <?php
                        endif;
                        $attributes = ['role' => 'form', 'style' => 'display: inline-block'];
                        if ($this->authorization->is_permitted(['laporan_peritem'])):
                            echo form_open($ctlr . '/peritem', $attributes, $hiddenid);
                            ?>
                            <button type="submit" class="btn btn-primary">Item Boneka</button>    
                            </form>
                            <?php
                        endif;
                        $attributes = ['role' => 'form', 'style' => 'display: inline-block'];
                        if ($this->authorization->is_permitted(['laporan_itemdate'])):
                            echo form_open($ctlr . '/bydate', $attributes, $hiddenid);
                            ?>
                            <input name="field" type="hidden" value="tglorder" />
                            <button type="submit" class="btn btn-primary">Item Boneka by Tgl.Order</button>    
                            </form>
                            <?php
                        endif;
                        $attributes = ['role' => 'form', 'style' => 'display: inline-block'];
                        if ($this->authorization->is_permitted(['laporan_blmlunas'])):
                            echo form_open($ctlr . '/blmlunas', $attributes, $hiddenid);
                            ?>
                            <button type="submit" class="btn btn-primary">Customer belum Lunas</button>
                            </form>
                        <?php endif; ?>
                        <?php if ($this->authorization->is_permitted(['laporan_konsumsi'])): ?>
                            <?php echo form_open($ctlr . '/avgbhn', $attributes, $hiddenid); ?>
                            <button type="submit" class="btn btn-primary">Rata2 Konsumsi Bahan</button>    
                            </form>
                        <?php endif; ?>
                        <?php if ($this->authorization->is_permitted(['laporan_cmt'])): ?>
                            <?php echo form_open($ctlr . '/cmt', $attributes, $hiddenid); ?>
                            <button type="submit" class="btn btn-primary">CMT Progress</button>    
                            </form>
                        <?php endif; ?>
                    </div>
                    <div class="control-group row-fluid">
                        <?php if ($this->authorization->is_permitted(['laporan_jenis'])): ?>
                            <label class="control-label" for="jenis">Pilih Jenis Tulisan :</label>
                            <div class="controls">
                                <?php
                                $attributes = ['role' => 'form', 'style' => 'display: inline-block'];
                                echo form_open($ctlr . '/pertulisan', $attributes, $hiddenid);
                                ?>
                                <?php echo form_dropdown('jenis_tulisan', $jenis, $jenisdef, 'id="jenis"'); ?>
                                <button type="submit" class="btn btn-primary">Jenis Tulisan</button>    
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php
                    $lapStaff = ['laporan_sewperform', 'laporan_biayasew', 'laporan_biayapretel', 'laporan_biayafin'];
                    if ($this->authorization->is_permitted($lapStaff)):
                        ?>
                        <div class="control-group row-fluid">
                            <label class="control-label" for="staff">Pilih Pekerja :</label>
                            <div class="controls">
                                <?php
                                $hiddenid = ['starth' => $start, 'endh' => $stop, 'pekerja' => $idstaff];
                                echo form_open($ctlr . '/sewnilai', $attributes, $hiddenid);
                                ?>
                                <?php echo form_dropdown('pekerjas', $pekerjas, $idstaff, 'id="staff"'); ?>
                                <?php if ($this->authorization->is_permitted(['laporan_sewperform'])): ?>
                                    <button type="submit" class="btn btn-primary">Sewing Performance</button>    
                                <?php endif; ?>
                                </form>
                                <?php if ($this->authorization->is_permitted(['laporan_biayasew'])): ?>
                                    <?php echo form_open($ctlr . '/sewongkos', $attributes, $hiddenid); ?>
                                    <button type="submit" class="btn btn-primary">Biaya Sewing</button>    
                                    </form>
                                <?php endif; ?>
                                <?php if ($this->authorization->is_permitted(['laporan_biayapretel'])): ?>
                                    <?php echo form_open($ctlr . '/prtlongkos', $attributes, $hiddenid); ?>
                                    <button type="submit" class="btn btn-primary">Biaya Pretel</button>    
                                    </form>
                                <?php endif; ?>
                                <?php if ($this->authorization->is_permitted(['laporan_biayafin'])): ?>
                                    <?php echo form_open($ctlr . '/finongkos', $attributes, $hiddenid); ?>
                                    <button type="submit" class="btn btn-primary">Biaya Finishing</button>    
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="row-fluid">
                <div class="span12 well form-horizontal">
                    <!--        <div class="control-group row-fluid">
                                <label class="control-label" for="tgl">Pilih Durasi Tanggal :</label>
                                <div class="controls">
                                    <div class="input-daterange" id="datepicker2">
                                        <input type="text" class="input-small" id="start2" name="start" value="<?php echo $start; ?>" />
                                        <span class="add-on"> sampai </span>
                                        <input type="text" class="input-small" id="end2" name="end" value="<?php echo $stop; ?>" />
                                    </div>
                                </div>
                            </div>-->
                    <!--        <div class="control-group row-fluid">
                                <label class="control-label" for="tgl">Pilih Pekerja :</label>
                                <div class="controls">
                                    <div class="input-append">
                    <?php
                    $hiddenid = ['starth' => $start, 'endh' => $stop, 'pekerja' => $idstaff];
                    echo form_open($ctlr . '/sewnilai', $attributes, $hiddenid);
                    ?>
                    <?php echo form_dropdown('pekerjas', $pekerjas, $idstaff); ?>
                                        <div class="btn-group">
                                            <button class="btn dropdown-toggle" data-toggle="dropdown">
                                              Action
                                              <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a href="#">Sewing Performance</a></li>
                                                <li><a href="javascript:void(0);">Biaya Sewing</a></li>
                                            </ul>
                                          </div>                    
                                    </form>
                                    </div>
                                </div>
                            </div>-->
                    <div class="control-group row-fluid">
                        <?php if ($this->authorization->is_permitted(['laporan_stok'])): ?>
                            <?php echo form_open($ctlr . '/stokbhn', $attributes); ?>
                            <button type="submit" class="btn btn-primary">Stok Bahan</button>    
                            </form>
                        <?php endif; ?>
                        <?php if ($this->authorization->is_permitted(['laporan_inweek'])): ?>
                            <?php $hiddenid = ['starth' => $start, 'endh' => $stop]; ?>
                            <?php echo form_open($ctlr . '/inweek', $attributes); ?>
                            <button type="submit" class="btn btn-primary">Pengiriman minggu ini</button>    
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php echo $this->load->view('footer'); ?>
    <script src="<?php echo base_url() . RES_DIR; ?>/bootstrap/js/bootstrap-datepicker.151.min.js"></script>
    <script src="<?php echo base_url() . RES_DIR; ?>/js/report.js"></script>

</body>
</html>

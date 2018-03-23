<?php
if (!$this->authentication->is_signed_in()) {
    redirect('account/sign_in/?continue=' . urlencode(current_url()));
}
?>
<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <?php echo anchor('', lang('website_title'), 'class="brand"'); ?>
            <div class="nav-collapse collapse">
                <ul class="nav">
                    <!--li class="divider-vertical"></li-->
                    <?php
                    $mainArray = ['lmo', 'amo', 'emo'];
                    $cutArray = ['lcut', 'acut', 'ecut', 'abhn', 'sbhn', 'ebhn', 'atls', 'stls', 'etls'];
                    $prtlArray = ['lprtl', 'aprtl', 'eprtl'];
                    $sewArray = ['lsew', 'adsew', 'sdsew', 'edsew'];
                    $finArray = ['lfin', 'adfin', 'sdfin', 'edfin'];
                    $ctrlArray = ['lcontrol', 'rcontrol'];
                    $itmArray = ['litem', 'aitem', 'eitem'];
                    $colArray = ['lcolor', 'acolor', 'ecolor'];
                    $bhnArray = ['lbbhn', 'abbhn', 'ebbhn'];
                    $ongArray = ['longkos', 'aongkos', 'eongkos'];
                    $kapArray = ['lkapasitas', 'akapasitas', 'ekapasitas'];
                    $tolArray = ['etoleransi'];
                    $jenArray = ['ljenis', 'ajenis', 'ejenis'];
                    $cusArray = ['lclient', 'aclient', 'eclient'];
                    $staffArray = ['astaff', 'lstaff', 'estaff'];
                    $lapArray = ['alaporan', 'llaporan'];
                    $actArray = ['lact'];
                    $bhninArray = ['abhnin', 'lbhnin', 'ebhnin'];
                    $arsipArray = ['rmo', 'rcut', 'rprtl', 'rsew', 'rfin'];
                    $cfgArray = array_merge($bhninArray, $colArray, $itmArray, $bhnArray, $ongArray, $kapArray, $tolArray, $jenArray, $cusArray, $staffArray);
                    $orderArray = array_merge($mainArray, $cutArray, $prtlArray, $sewArray, $finArray);
                    $laporanAll = ['laporan_percust', 'laporan_top10', 'laporan_peritem', 'laporan_itemdate'
                        , 'laporan_blmlunas', 'laporan_konsumsi', 'laporan_jenis', 'laporan_sewperform'
                        , 'laporan_biayasew', 'laporan_biayapretel', 'laporan_biayafin', 'laporan_stok'
                        , 'laporan_inweek', 'laporan_cmt','retrieve_actlog'];
                    $lapNlog = array_merge($laporanAll, ['retrieve_actlog']);
                    $configAll = ['create_jenis', 'retrieve_jenis', 'update_jenis', 'delete_jenis', 'create_warna'
                        , 'retrieve_warna', 'update_warna', 'delete_warna', 'create_bahan', 'retrieve_bahan'
                        , 'update_bahan', 'delete_bahan', 'create_bhnin', 'retrieve_bhnin', 'update_bhnin'
                        , 'delete_bhnin', 'create_ongkos', 'retrieve_ongkos', 'update_ongkos', 'delete_ongkos'
                        , 'create_kapasitas', 'retrieve_kapasitas', 'update_kapasitas', 'delete_kapasitas'
                        , 'update_toleransi', 'create_jtulisan', 'retrieve_jtulisan', 'update_jtulisan'
                        , 'delete_jtulisan', 'create_pelanggan', 'retrieve_pelanggan', 'update_pelanggan'
                        , 'delete_pelanggan', 'create_pekerja', 'retrieve_pekerja', 'update_pekerja', 'delete_pekerja'];
                    ?>
                    <li class="dropdown<?php echo (in_array($title, $orderArray)) ? ' active' : ''; ?>">
                        <a class = "dropdown-toggle" data-toggle = "dropdown" href = "javascript:void(0);">
                            Order <span class = "caret"></span>
                        </a>
                        <ul class = "dropdown-menu">
                            <?php if ($this->authorization->is_permitted(['retrieve_mainorder'])): ?>
                                <li class="dropdown-submenu<?php echo (in_array($title, $mainArray)) ? ' active' : ''; ?>">
                                    <a class = "dropdown-toggle" data-toggle = "dropdown" href = "javascript:void(0);">
                                        Main Order</span>
                                    </a>
                                    <ul class = "dropdown-menu">
                                        <li<?php echo ($title == 'lmo') ? ' class="active"' : ''; ?>><?php echo anchor('main/', 'List'); ?></li>
                                        <?php if ($this->authorization->is_permitted(['create_mainorder'])): ?>
                                            <li<?php echo ($title == 'amo') ? ' class="active"' : ''; ?>><?php echo anchor('main/insert', 'Tambah'); ?></li>
                                        <?php endif; ?>
                                    </ul>
                                </li>
                            <?php endif; ?>
                            <?php if ($this->authorization->is_permitted(['retrieve_co'])): ?>
                                <?php //if($this->authorization->is_role("Admin") || $this->authorization->is_role("Cutting")): ?>
                                <li<?php echo (in_array($title, $cutArray)) ? ' class="active"' : ''; ?>><?php echo anchor('cut/', 'Cutting Order'); ?></li>
                            <?php endif; ?>
                            <?php if ($this->authorization->is_permitted(['retrieve_po'])): ?>
                                <?php // if($this->authorization->is_role("Admin") || $this->authorization->is_role("Pretel")): ?>
                                <li<?php echo ($title == 'lprtl') ? ' class="active"' : ''; ?>><?php echo anchor('prtl/', 'Pretel Order'); ?></li>
                            <?php endif; ?>
                            <?php if ($this->authorization->is_permitted(['retrieve_so'])): ?>
                                <?php // if($this->authorization->is_role("Admin") || $this->authorization->is_role("Sewing")): ?>
                                <li<?php echo (in_array($title, $sewArray)) ? ' class="active"' : ''; ?>><?php echo anchor('sew/', 'Sewing Order'); ?></li>
                            <?php endif; ?>
                            <?php if ($this->authorization->is_permitted(['retrieve_fo'])): ?>
                                <?php // if($this->authorization->is_role("Admin") || $this->authorization->is_role("Finishing")): ?>
                                <li<?php echo (in_array($title, $finArray)) ? ' class="active"' : ''; ?>><?php echo anchor('fin/', 'Finishing Order'); ?></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <?php if ($this->authorization->is_permitted(['lihat_control'])): ?>
                        <li class="dropdown<?php echo (in_array($title, $ctrlArray)) ? ' active' : ''; ?>">
                            <a class = "dropdown-toggle" data-toggle = "dropdown" href = "javascript:void(0);">
                                Production Control <span class = "caret"></span>
                            </a>
                            <ul class = "dropdown-menu">
                                <li<?php echo ($title == 'lcontrol') ? ' class="active"' : ''; ?>><?php echo anchor('control/', 'Control'); ?></li>
                                <li<?php echo ($title == 'rcontrol') ? ' class="active"' : ''; ?>><?php echo anchor('control/arsip', 'Arsip'); ?></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                    <?php if ($this->authorization->is_permitted($lapNlog)): ?>
                        <?php // if($this->authorization->is_role("Admin")): ?>
                        <li class="dropdown<?php echo (in_array($title, $lapArray)) ? ' active' : ''; ?>">
                            <a class = "dropdown-toggle" data-toggle = "dropdown" href = "javascript:void(0);">
                                Laporan <span class = "caret"></span>
                            </a>
                            <ul class = "dropdown-menu">
                                <?php if ($this->authorization->is_permitted($laporanAll)): ?>
                                    <li<?php echo ($title == 'llaporan') ? ' class="active"' : ''; ?>><?php echo anchor('laporan/', 'Laporan'); ?></li>
                                <?php endif; ?>
                                <?php if ($this->authorization->is_permitted(['retrieve_actlog'])): ?>
                                    <li<?php echo ($title == 'lact') ? ' class="active"' : ''; ?>><?php echo anchor('actlog/', 'Sejarah Aktifitas'); ?></li>
                                <?php endif; ?>
                            </ul>
                        </li>
                    <?php endif; ?>
                    <?php if ($this->authorization->is_permitted($configAll)): ?>
                        <li class="dropdown<?php echo (in_array($title, $cfgArray)) ? ' active' : ''; ?>">
                            <a class = "dropdown-toggle" data-toggle = "dropdown" href = "javascript:void(0);">
                                Config <span class = "caret"></span>
                            </a>
                            <ul class = "dropdown-menu">
                                <?php if ($this->authorization->is_permitted(['retrieve_jenis', 'create_jenis'])): ?>
                                    <li class="dropdown-submenu<?php echo (in_array($title, $itmArray)) ? ' active' : ''; ?>">
                                        <a class = "dropdown-toggle" data-toggle = "dropdown" href = "javascript:void(0);">
                                            Jenis Boneka & Performance</span>
                                        </a>
                                        <ul class = "dropdown-menu">
                                            <?php if ($this->authorization->is_permitted(['retrieve_jenis'])): ?>
                                                <li<?php echo ($title == 'litem') ? ' class="active"' : ''; ?>><?php echo anchor('item/', 'List'); ?></li>
                                            <?php endif; ?>
                                            <?php if ($this->authorization->is_permitted(['create_jenis'])): ?>
                                                <li<?php echo ($title == 'aitem') ? ' class="active"' : ''; ?>><?php echo anchor('item/insert', 'Tambah'); ?></li>
                                            <?php endif; ?>
                                        </ul>
                                    </li>
                                <?php endif; ?>
                                <?php if ($this->authorization->is_permitted(['retrieve_warna', 'create_warna'])): ?>
                                    <li class="dropdown-submenu<?php echo (in_array($title, $colArray)) ? ' active' : ''; ?>">
                                        <a class = "dropdown-toggle" data-toggle = "dropdown" href = "javascript:void(0);">
                                            Warna Boneka</span>
                                        </a>
                                        <ul class = "dropdown-menu">
                                            <?php if ($this->authorization->is_permitted(['retrieve_warna'])): ?>
                                                <li<?php echo ($title == 'lcolor') ? ' class="active"' : ''; ?>><?php echo anchor('color/', 'List'); ?></li>
                                            <?php endif; ?>
                                            <?php if ($this->authorization->is_permitted(['create_warna'])): ?>
                                                <li<?php echo ($title == 'acolor') ? ' class="active"' : ''; ?>><?php echo anchor('color/insert', 'Tambah'); ?></li>
                                            <?php endif; ?>
                                        </ul>
                                    </li>
                                <?php endif; ?>
                                <?php if ($this->authorization->is_permitted(['retrieve_bahan', 'create_bahan'])): ?>
                                    <li class="dropdown-submenu<?php echo (in_array($title, $bhnArray)) ? ' active' : ''; ?>">
                                        <a class = "dropdown-toggle" data-toggle = "dropdown" href = "javascript:void(0);">
                                            Bahan Boneka</span>
                                        </a>
                                        <ul class = "dropdown-menu">
                                            <?php if ($this->authorization->is_permitted(['retrieve_bahan'])): ?>
                                                <li<?php echo ($title == 'lbbhn') ? ' class="active"' : ''; ?>><?php echo anchor('bbhn/', 'List'); ?></li>
                                            <?php endif; ?>
                                            <?php if ($this->authorization->is_permitted(['create_bahan'])): ?>
                                                <li<?php echo ($title == 'abbhn') ? ' class="active"' : ''; ?>><?php echo anchor('bbhn/insert', 'Tambah'); ?></li>
                                            <?php endif; ?>
                                        </ul>
                                    </li>
                                <?php endif; ?>
                                <?php if ($this->authorization->is_permitted(['retrieve_bhnin', 'create_bhnin'])): ?>
                                    <li class="dropdown-submenu<?php echo (in_array($title, $bhninArray)) ? ' active' : ''; ?>">
                                        <a class = "dropdown-toggle" data-toggle = "dropdown" href = "javascript:void(0);">
                                            Bahan Masuk</span>
                                        </a>
                                        <ul class = "dropdown-menu">
                                            <?php if ($this->authorization->is_permitted(['retrieve_bhnin'])): ?>
                                                <li<?php echo ($title == 'lbhnin') ? ' class="active"' : ''; ?>><?php echo anchor('bhnin/', 'List'); ?></li>
                                            <?php endif; ?>
                                            <?php if ($this->authorization->is_permitted(['create_bhnin'])): ?>
                                                <li<?php echo ($title == 'abhnin') ? ' class="active"' : ''; ?>><?php echo anchor('bhnin/insert', 'Tambah'); ?></li>
                                            <?php endif; ?>
                                        </ul>
                                    </li>
                                <?php endif; ?>
                                <?php if ($this->authorization->is_permitted(['retrieve_ongkos', 'create_ongkos'])): ?>
                                    <li class="dropdown-submenu<?php echo (in_array($title, $ongArray)) ? ' active' : ''; ?>">
                                        <a class = "dropdown-toggle" data-toggle = "dropdown" href = "javascript:void(0);">
                                            Ongkos & Konsumsi Bahan</span>
                                        </a>
                                        <ul class = "dropdown-menu">
                                            <?php if ($this->authorization->is_permitted(['retrieve_ongkos'])): ?>
                                                <li<?php echo ($title == 'longkos') ? ' class="active"' : ''; ?>><?php echo anchor('ongkos/', 'List'); ?></li>
                                            <?php endif; ?>
                                            <?php if ($this->authorization->is_permitted(['create_ongkos'])): ?>
                                                <li<?php echo ($title == 'aongkos') ? ' class="active"' : ''; ?>><?php echo anchor('ongkos/insert', 'Tambah'); ?></li>
                                            <?php endif; ?>
                                        </ul>
                                    </li>
                                <?php endif; ?>
                                <?php if ($this->authorization->is_permitted(['retrieve_kapasitas', 'create_kapasitas'])): ?>
                                    <li class="dropdown-submenu<?php echo (in_array($title, $kapArray)) ? ' active' : ''; ?>">
                                        <!--<a class = "dropdown-toggle" data-toggle = "dropdown" href = "<?php echo base_url('kapasitas/') ?>">-->
                                        <a class = "dropdown-toggle" data-toggle = "dropdown">
                                            Kapasitas Produksi</span>
                                        </a>
                                        <ul class = "dropdown-menu">
                                            <?php if ($this->authorization->is_permitted(['retrieve_kapasitas'])): ?>
                                                <li<?php echo ($title == 'lkapasitas') ? ' class="active"' : ''; ?>><?php echo anchor('kapasitas/', 'List'); ?></li>
                                            <?php endif; ?>
                                            <?php if ($this->authorization->is_permitted(['create_kapasitas'])): ?>
                                                <li<?php echo ($title == 'akapasitas') ? ' class="active"' : ''; ?>><?php echo anchor('kapasitas/insert', 'Tambah'); ?></li>
                                            <?php endif; ?>
                                        </ul>
                                    </li>
                                <?php endif; ?>
                                <?php if ($this->authorization->is_permitted(['update_toleransi'])): ?>
                                    <li<?php echo ($title == 'etoleransi') ? ' class="active"' : ''; ?>><?php echo anchor('toleransi/', 'Toleransi Kirim'); ?></li>
                                <?php endif; ?>
                                <?php if ($this->authorization->is_permitted(['retrieve_jtulisan', 'create_jtulisan'])): ?>
                                    <li class="dropdown-submenu<?php echo (in_array($title, $jenArray)) ? ' active' : ''; ?>">
                                        <a class = "dropdown-toggle" data-toggle = "dropdown" href = "javascript:void(0);">
                                            Jenis Tulisan</span>
                                        </a>
                                        <ul class = "dropdown-menu">
                                            <?php if ($this->authorization->is_permitted(['retrieve_jtulisan'])): ?>
                                                <li<?php echo ($title == 'ljenis') ? ' class="active"' : ''; ?>><?php echo anchor('jenis/', 'List'); ?></li>
                                            <?php endif; ?>
                                            <?php if ($this->authorization->is_permitted(['create_jtulisan'])): ?>
                                                <li<?php echo ($title == 'ajenis') ? ' class="active"' : ''; ?>><?php echo anchor('jenis/insert', 'Tambah'); ?></li>
                                            <?php endif; ?>
                                        </ul>
                                    </li>
                                <?php endif; ?>
                                <?php if ($this->authorization->is_permitted(['retrieve_pelanggan', 'create_pelanggan'])): ?>
                                    <li class="dropdown-submenu<?php echo (in_array($title, $cusArray)) ? ' active' : ''; ?>">
                                        <a class = "dropdown-toggle" data-toggle = "dropdown" href = "javascript:void(0);">
                                            Pelanggan</span>
                                        </a>
                                        <ul class = "dropdown-menu">
                                            <?php if ($this->authorization->is_permitted(['retrieve_pelanggan'])): ?>
                                                <li<?php echo ($title == 'lclient') ? ' class="active"' : ''; ?>><?php echo anchor('client/', 'List'); ?></li>
                                            <?php endif; ?>
                                            <?php if ($this->authorization->is_permitted(['create_pelanggan'])): ?>
                                                <li<?php echo ($title == 'aclient') ? ' class="active"' : ''; ?>><?php echo anchor('client/insert', 'Tambah'); ?></li>
                                            <?php endif; ?>
                                        </ul>
                                    </li>
                                <?php endif; ?>
                                <?php if ($this->authorization->is_permitted(['retrieve_pekerja', 'create_pekerja'])): ?>
                                    <li class="dropdown-submenu<?php echo (in_array($title, $staffArray)) ? ' active' : ''; ?>">
                                        <a class = "dropdown-toggle" data-toggle = "dropdown" href = "javascript:void(0);">
                                            Pekerja</span>
                                        </a>
                                        <ul class = "dropdown-menu">
                                            <?php if ($this->authorization->is_permitted(['retrieve_pekerja'])): ?>
                                                <li<?php echo ($title == 'lstaff') ? ' class="active"' : ''; ?>><?php echo anchor('pekerja/', 'List'); ?></li>
                                            <?php endif; ?>
                                            <?php if ($this->authorization->is_permitted(['create_pekerja'])): ?>
                                                <li<?php echo ($title == 'astaff') ? ' class="active"' : ''; ?>><?php echo anchor('pekerja/insert', 'Tambah'); ?></li>
                                            <?php endif; ?>
                                        </ul>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </li>
                    <?php endif; ?>                    
                    <li class="dropdown<?php echo (in_array($title, $arsipArray)) ? ' active' : ''; ?>">
                        <a class = "dropdown-toggle" data-toggle = "dropdown" href = "javascript:void(0);">
                            Arsip <span class = "caret"></span>
                        </a>
                        <ul class = "dropdown-menu">
                            <?php if ($this->authorization->is_permitted(['retrieve_mainorder'])): ?>
                                <?php // if($this->authorization->is_role("Admin")): ?>
                                <li<?php echo ($title == 'rmo') ? ' class="active"' : ''; ?>><?php echo anchor('main/arsip', 'Main Order'); ?></li>
                            <?php endif; ?>
                            <?php if ($this->authorization->is_permitted(['retrieve_co'])): ?>
                                <?php // if($this->authorization->is_role("Admin") || $this->authorization->is_role("Cutting")): ?>
                                <li<?php echo ($title == 'rcut') ? ' class="active"' : ''; ?>><?php echo anchor('cut/arsip', 'Cutting Order'); ?></li>
                            <?php endif; ?>
                            <?php if ($this->authorization->is_permitted(['retrieve_po'])): ?>
                                <?php // if($this->authorization->is_role("Admin") || $this->authorization->is_role("Pretel")): ?>
                                <li<?php echo ($title == 'rprtl') ? ' class="active"' : ''; ?>><?php echo anchor('prtl/arsip', 'Pretel Order'); ?></li>
                            <?php endif; ?>
                            <?php if ($this->authorization->is_permitted(['retrieve_so'])): ?>
                                <?php // if($this->authorization->is_role("Admin") || $this->authorization->is_role("Sewing")): ?>
                                <li<?php echo ($title == 'rsew') ? ' class="active"' : ''; ?>><?php echo anchor('sew/arsip', 'Sewing Order'); ?></li>
                            <?php endif; ?>
                            <?php if ($this->authorization->is_permitted(['retrieve_fo'])): ?>
                                <?php // if($this->authorization->is_role("Admin") || $this->authorization->is_role("Finishing")): ?>
                                <li<?php echo ($title == 'rfin') ? ' class="active"' : ''; ?>><?php echo anchor('fin/arsip', 'Finishing Order'); ?></li>
                            <?php endif; ?>
                        </ul>
                    </li>                    
                </ul>

                <ul class="nav pull-right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <?php if ($this->authentication->is_signed_in()) : ?>
                                <i class="icon-user icon-white"></i> <?php echo $account->username; ?> <b class="caret"></b></a>
                        <?php else : ?>
                            <i class="icon-user icon-white"></i> <b class="caret"></b></a>
                        <?php endif; ?>

                        <ul class="dropdown-menu">
                            <?php if ($this->authentication->is_signed_in()) : ?>
                                <li class="nav-header">Account Info</li>
                                <li><?php echo anchor('account/account_profile', lang('website_profile')); ?></li>
                                <li><?php echo anchor('account/account_settings', lang('website_account')); ?></li>
                                <?php if ($account->password) : ?>
                                    <li><?php echo anchor('account/account_password', lang('website_password')); ?></li>
                                <?php endif; ?>
                    <!--li><?php echo anchor('account/account_linked', lang('website_linked')); ?></li-->    
                                <?php if ($this->authorization->is_permitted(array('retrieve_users', 'retrieve_roles', 'retrieve_permissions'))) : ?>
                                    <li class="divider"></li>
                                    <li class="nav-header">Admin Panel</li>
                                    <?php if ($this->authorization->is_permitted('retrieve_users')) : ?>
                                        <li><?php echo anchor('account/manage_users', lang('website_manage_users')); ?></li>
                                    <?php endif; ?>

                                    <?php if ($this->authorization->is_permitted('retrieve_roles')) : ?>
                                        <li><?php echo anchor('account/manage_roles', lang('website_manage_roles')); ?></li>
                                    <?php endif; ?>

                                    <?php if ($this->authorization->is_permitted('retrieve_permissions')) : ?>
                                        <li><?php echo anchor('account/manage_permissions', lang('website_manage_permissions')); ?></li>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <li class="divider"></li>
                                <li><?php echo anchor('account/sign_out', lang('website_sign_out')); ?></li>
                            <?php else : ?>
                                <li><?php echo anchor('account/sign_in', lang('website_sign_in')); ?></li>
                            <?php endif; ?>

                        </ul>
                    </li>
                </ul>

            </div>
            <!--/.nav-collapse -->
        </div>
    </div>
</div>
<?php
if (in_array($title, $mainArray)) {
    $first = 'Main Order';
    $firstlink = base_url('main/');
} elseif (in_array($title, $cutArray)) {
    $first = 'Cutting Order';
    $firstlink = base_url('cut/');
    $csec = substr($title, 1);
    switch ($csec) {
        case 'tls': $sec = 'Jenis Tulisan';
            $seclink = base_url('tulisan/set');
            break;
        case 'bhn': $sec = 'Pemakaian Bahan';
            $seclink = base_url('bhn/set');
            break;
    }
} elseif (in_array($title, $prtlArray)) {
    $first = 'Pretel Order';
    $firstlink = base_url('prtl/');
} elseif (in_array($title, $sewArray)) {
    $first = 'Sewing Order';
    $firstlink = base_url('sew/');
    $csec = substr($title, 2);
    switch ($csec) {
        case 'sew': $sec = 'Sewing Distribution';
            $seclink = base_url('sew/set');
            break;
    }
} elseif (in_array($title, $finArray)) {
    $first = 'Finishing Order';
    $firstlink = base_url('fin/');
    $csec = substr($title, 2);
    switch ($csec) {
        case 'fin': $sec = 'Finishing Distribution';
            $seclink = base_url('fin/set');
            break;
    }
} elseif (in_array($title, $ctrlArray)) {
    $first = 'Kontrol Produksi';
    $firstlink = base_url('control/');
    $csec = substr($title, 0, 1);
    switch ($csec) {
        case 'r': $sec = 'Arsip';
            $seclink = base_url('control/arsip');
            break;
    }
} elseif (in_array($title, $lapArray)) {
    $first = 'Laporan';
    $firstlink = base_url('laporan/');
} elseif (in_array($title, $actArray)) {
    $first = 'Sejarah Aktifitas';
    $firstlink = base_url('actlog/');
} elseif (in_array($title, $itmArray)) {
    $first = 'Jenis Boneka & Performance';
    $firstlink = base_url('item/');
} elseif (in_array($title, $colArray)) {
    $first = 'Warna Boneka';
    $firstlink = base_url('color/');
} elseif (in_array($title, $bhnArray)) {
    $first = 'Bahan Boneka';
    $firstlink = base_url('bbhn/');
} elseif (in_array($title, $bhninArray)) {
    $first = 'Bahan Masuk';
    $firstlink = base_url('bhnin/');
} elseif (in_array($title, $ongArray)) {
    $first = 'Ongkos & Konsumsi Bahan';
    $firstlink = base_url('ongkos/');
} elseif (in_array($title, $kapArray)) {
    $first = 'Kapasitas Produksi';
    $firstlink = base_url('kapasitas/');
} elseif (in_array($title, $tolArray)) {
    $first = 'Toleransi Kirim';
    $firstlink = base_url('toleransi/');
} elseif (in_array($title, $jenArray)) {
    $first = 'Jenis Tulisan';
    $firstlink = base_url('jenis/');
} elseif (in_array($title, $cusArray)) {
    $first = 'Pelanggan';
    $firstlink = base_url('client/');
} elseif (in_array($title, $staffArray)) {
    $first = 'Pekerja';
    $firstlink = base_url('pekerja/');
} elseif (in_array($title, $arsipArray)) {
    $first = 'Arsip';
    $firstlink = 'javascript:void(0)';
    $csec = substr($title, 1);
    switch ($csec) {
        case 'mo': $sec = 'Main Order';
            $seclink = base_url('main/arsip');
            break;
        case 'cut': $sec = 'Cutting Order';
            $seclink = base_url('cut/arsip');
            break;
        case 'prtl': $sec = 'Pretel Order';
            $seclink = base_url('prtl/arsip');
            break;
        case 'sew': $sec = 'Sewing Order';
            $seclink = base_url('sew/arsip');
            break;
        case 'fin': $sec = 'Finishing Order';
            $seclink = base_url('fin/arsip');
            break;
    }
}

$cthird = substr($title, 0, 1);
switch ($cthird) {
    case 'r':;
    case 's':;
    case 'l': $third = 'List';
        break;
    case 'a': $third = 'Input';
        break;
    case 'e': $third = 'Edit';
        break;
}
?>
<?php if (!empty($first)) { ?>
    <div class="container-fluid">
        <ul class="breadcrumb">
            <li><a href="<?php echo $firstlink; ?>"><?php echo $first; ?></a> <span class="divider">/</span></li>
    <?php if (!empty($sec)) { ?>
                <li><a href="<?php echo $seclink; ?>"><?php echo $sec; ?></a> <span class="divider">/</span></li>
    <?php } ?>
            <li class="active"><?php echo $third; ?></li>
        </ul>
    </div>
<?php } ?>

<!--<div class="container-fluid" style="margin-top: -30px;">
    <div class="page-header2">
<?php // echo $judul; ?>
    </div>
</div>-->

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
                    $titleArray = ['lmo', 'amo', 'lclient', 'aclient', 'astaff', 'lstaff'];
                    $cutArray = ['lcut', 'acut', 'abhn', 'sbhn', 'ebhn', 'atls', 'stls', 'etls'];
                    $prtlArray = ['lprtl', 'aprtl', 'eprtl'];
                    $sewArray = ['lsew', 'adsew', 'sdsew', 'edsew'];
                    $finArray = ['lfin', 'adfin', 'sdfin', 'edfin'];
                    $ctrlArray = ['lctrl'];
                    $itmArray = ['litem', 'aitem', 'eitem'];
                    $colArray = ['lcolor', 'acolor', 'ecolor'];
                    $bhnArray = ['lbbhn', 'abbhn', 'ebbhn'];
                    $ongArray = ['longkos', 'aongkos', 'eongkos'];
                    $kapArray = ['lkapasitas', 'akapasitas', 'ekapasitas'];
                    $tolArray = ['etoleransi'];
                    $jenArray = ['ljenis', 'ajenis', 'ejenis'];
                    $cfgArray = array_merge($colArray, $itmArray, $bhnArray, $ongArray, $kapArray, $tolArray, $jenArray);
                    ?>
                    <li class="dropdown<?php echo (in_array($title, $titleArray)) ? ' active' : ''; ?>">
                        <a class = "dropdown-toggle" data-toggle = "dropdown" href = "<?php echo base_url('main/') ?>">
                            Main Order <span class = "caret"></span>
                        </a>
                        <ul class = "dropdown-menu">
                            <li<?php echo ($title == 'lmo') ? ' class="active"' : ''; ?>><?php echo anchor('main/', 'Pesanan'); ?></li>
                            <li<?php echo ($title == 'amo') ? ' class="active"' : ''; ?>><?php echo anchor('main/insert', 'Tambah Order'); ?></li>
                            <li class="divider"></li>
                            <li<?php echo ($title == 'lclient') ? ' class="active"' : ''; ?>><?php echo anchor('client/', 'Lihat Pelanggan'); ?></li>
                            <li<?php echo ($title == 'aclient') ? ' class="active"' : ''; ?>><?php echo anchor('client/insert', 'Tambah Pelanggan'); ?></li>
                            <li class="divider"></li>
                            <li<?php echo ($title == 'lstaff') ? ' class="active"' : ''; ?>><?php echo anchor('pekerja/', 'Lihat Pekerja'); ?></li>
                            <li<?php echo ($title == 'astaff') ? ' class="active"' : ''; ?>><?php echo anchor('pekerja/insert', 'Tambah Pekerja'); ?></li>
                        </ul>
                    </li>
                    <li class="dropdown<?php echo (in_array($title, $cutArray)) ? ' active' : ''; ?>">
                        <a class = "dropdown-toggle" data-toggle = "dropdown" href = "<?php echo base_url('cut/') ?>">
                            Cutting Order <span class = "caret"></span>
                        </a>
                        <ul class = "dropdown-menu">
                            <li<?php echo ($title == 'lcut') ? ' class="active"' : ''; ?>><?php echo anchor('cut/', 'Cutting Job'); ?></li>
                            <li class="dropdown-submenu<?php echo (in_array($title, $titleArray)) ? ' active' : ''; ?>">
                                <a class = "dropdown-toggle" data-toggle = "dropdown" href = "<?php echo base_url('main/') ?>">
                                    Main Order </span>
                                </a>
                                <ul class = "dropdown-menu">
                                    <li<?php echo ($title == 'lmo') ? ' class="active"' : ''; ?>><?php echo anchor('main/', 'Pesanan'); ?></li>
                                    <li<?php echo ($title == 'amo') ? ' class="active"' : ''; ?>><?php echo anchor('main/insert', 'Tambah Order'); ?></li>
                                    <li class="divider"></li>
                                    <li<?php echo ($title == 'lclient') ? ' class="active"' : ''; ?>><?php echo anchor('client/', 'Lihat Pelanggan'); ?></li>
                                    <li<?php echo ($title == 'aclient') ? ' class="active"' : ''; ?>><?php echo anchor('client/insert', 'Tambah Pelanggan'); ?></li>
                                    <li class="divider"></li>
                                    <li<?php echo ($title == 'lstaff') ? ' class="active"' : ''; ?>><?php echo anchor('pekerja/', 'Lihat Pekerja'); ?></li>
                                    <li<?php echo ($title == 'astaff') ? ' class="active"' : ''; ?>><?php echo anchor('pekerja/insert', 'Tambah Pekerja'); ?></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown<?php echo (in_array($title, $prtlArray)) ? ' active' : ''; ?>">
                        <a class = "dropdown-toggle" data-toggle = "dropdown" href = "<?php echo base_url('prtl/') ?>">
                            Pretel Order <span class = "caret"></span>
                        </a>
                        <ul class = "dropdown-menu">
                            <li<?php echo ($title == 'lprtl') ? ' class="active"' : ''; ?>><?php echo anchor('prtl/', 'Pretel Job'); ?></li>
                        </ul>
                    </li>
                    <li class="dropdown<?php echo (in_array($title, $sewArray)) ? ' active' : ''; ?>">
                        <a class = "dropdown-toggle" data-toggle = "dropdown" href = "<?php echo base_url('sew/') ?>">
                            Sewing Order <span class = "caret"></span>
                        </a>
                        <ul class = "dropdown-menu">
                            <li<?php echo ($title == 'lsew') ? ' class="active"' : ''; ?>><?php echo anchor('sew/', 'Sewing Job'); ?></li>
                        </ul>
                    </li>
                    <li class="dropdown<?php echo (in_array($title, $finArray)) ? ' active' : ''; ?>">
                        <a class = "dropdown-toggle" data-toggle = "dropdown" href = "<?php echo base_url('fin/') ?>">
                            Finishing Order <span class = "caret"></span>
                        </a>
                        <ul class = "dropdown-menu">
                            <li<?php echo ($title == 'lfin') ? ' class="active"' : ''; ?>><?php echo anchor('fin/', 'Finishing Job'); ?></li>
                        </ul>
                    </li>
                    <li class="dropdown<?php echo (in_array($title, $ctrlArray)) ? ' active' : ''; ?>">
                        <a class = "dropdown-toggle" data-toggle = "dropdown" href = "<?php echo base_url('control/') ?>">
                            Production Control <span class = "caret"></span>
                        </a>
                        <ul class = "dropdown-menu">
                            <li<?php echo ($title == 'lcontrol') ? ' class="active"' : ''; ?>><?php echo anchor('control/', 'Control'); ?></li>
                        </ul>
                    </li>
                    <li class="dropdown<?php echo (in_array($title, $cfgArray)) ? ' active' : ''; ?>">
                        <a class = "dropdown-toggle" data-toggle = "dropdown" href = "<?php echo base_url('item/') ?>">
                            Config <span class = "caret"></span>
                        </a>
                        <ul class = "dropdown-menu">
                            <li class="dropdown-submenu<?php echo (in_array($title, $itmArray)) ? ' active' : ''; ?>">
                                <a class = "dropdown-toggle" data-toggle = "dropdown" href = "<?php echo base_url('item/') ?>">
                                    Jenis Boneka & Performance</span>
                                </a>
                                <ul class = "dropdown-menu">
                                    <li<?php echo ($title == 'litem') ? ' class="active"' : ''; ?>><?php echo anchor('item/', 'List'); ?></li>
                                    <li<?php echo ($title == 'aitem') ? ' class="active"' : ''; ?>><?php echo anchor('item/insert', 'Tambah'); ?></li>
                                </ul>
                            </li>
                            <li class="dropdown-submenu<?php echo (in_array($title, $colArray)) ? ' active' : ''; ?>">
                                <a class = "dropdown-toggle" data-toggle = "dropdown" href = "<?php echo base_url('color/') ?>">
                                    Warna Boneka</span>
                                </a>
                                <ul class = "dropdown-menu">
                                    <li<?php echo ($title == 'lcolor') ? ' class="active"' : ''; ?>><?php echo anchor('color/', 'List'); ?></li>
                                    <li<?php echo ($title == 'acolor') ? ' class="active"' : ''; ?>><?php echo anchor('color/insert', 'Tambah'); ?></li>
                                </ul>
                            </li>
                            <li class="dropdown-submenu<?php echo (in_array($title, $bhnArray)) ? ' active' : ''; ?>">
                                <a class = "dropdown-toggle" data-toggle = "dropdown" href = "<?php echo base_url('bbhn/') ?>">
                                    Bahan Boneka</span>
                                </a>
                                <ul class = "dropdown-menu">
                                    <li<?php echo ($title == 'lbbhn') ? ' class="active"' : ''; ?>><?php echo anchor('bbhn/', 'List'); ?></li>
                                    <li<?php echo ($title == 'abbhn') ? ' class="active"' : ''; ?>><?php echo anchor('bbhn/insert', 'Tambah'); ?></li>
                                </ul>
                            </li>
                            <li class="dropdown-submenu<?php echo (in_array($title, $ongArray)) ? ' active' : ''; ?>">
                                <a class = "dropdown-toggle" data-toggle = "dropdown" href = "<?php echo base_url('ongkos/') ?>">
                                    Ongkos & Konsumsi Bahan</span>
                                </a>
                                <ul class = "dropdown-menu">
                                    <li<?php echo ($title == 'longkos') ? ' class="active"' : ''; ?>><?php echo anchor('ongkos/', 'List'); ?></li>
                                    <li<?php echo ($title == 'aongkos') ? ' class="active"' : ''; ?>><?php echo anchor('ongkos/insert', 'Tambah'); ?></li>
                                </ul>
                            </li>
                            <li class="dropdown-submenu<?php echo (in_array($title, $kapArray)) ? ' active' : ''; ?>">
                                <a class = "dropdown-toggle" data-toggle = "dropdown" href = "<?php echo base_url('kapasitas/') ?>">
                                    Kapasitas Produksi</span>
                                </a>
                                <ul class = "dropdown-menu">
                                    <li<?php echo ($title == 'lkapasitas') ? ' class="active"' : ''; ?>><?php echo anchor('kapasitas/', 'List'); ?></li>
                                    <li<?php echo ($title == 'akapasitas') ? ' class="active"' : ''; ?>><?php echo anchor('kapasitas/insert', 'Tambah'); ?></li>
                                </ul>
                            </li>
                            <li<?php echo ($title == 'etoleransi') ? ' class="active"' : ''; ?>><?php echo anchor('toleransi/', 'Toleransi Kirim'); ?></li>
                            <li class="dropdown-submenu<?php echo (in_array($title, $jenArray)) ? ' active' : ''; ?>">
                                <a class = "dropdown-toggle" data-toggle = "dropdown" href = "<?php echo base_url('jenis/') ?>">
                                    Jenis Tulisan</span>
                                </a>
                                <ul class = "dropdown-menu">
                                    <li<?php echo ($title == 'ljenis') ? ' class="active"' : ''; ?>><?php echo anchor('jenis/', 'List'); ?></li>
                                    <li<?php echo ($title == 'ajenis') ? ' class="active"' : ''; ?>><?php echo anchor('jenis/insert', 'Tambah'); ?></li>
                                </ul>
                            </li>
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

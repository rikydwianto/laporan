<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation" style="border: none; background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <div class="navbar-header">
            <a class="navbar-brand" href="<?=$url ?>" style="font-weight: 700; font-size: 20px; color: white; letter-spacing: 0.5px; padding: 15px 20px;">
                <i class="fa fa-dashboard" style="margin-right: 8px;"></i><?= isset($d['nama_cabang']) ? strtoupper($d['nama_cabang']) : '' ?>
            </a>
        </div>

        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse" style="margin-top: 8px; margin-right: 15px; border-color: rgba(255,255,255,0.3);">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar" style="background-color: white;"></span>
            <span class="icon-bar" style="background-color: white;"></span>
            <span class="icon-bar" style="background-color: white;"></span>
        </button>

        <!-- Top Navigation: Left Menu -->
        <ul class="nav navbar-nav navbar-left navbar-top-links">
            <!-- <li><a href="<?=$url ?>"><i class="fa fa-home fa-fw"></i> PAGADEN</a></li> -->
        </ul>

        <!-- Top Navigation: Right Menu -->
        <ul class="nav navbar-right navbar-top-links" style="margin-right: 15px;">
            
			<li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#" style="color: white; padding: 15px 20px; display: flex; align-items: center;">
                    <i class="fa fa-user fa-fw" style="margin-right: 8px; font-size: 16px;"></i>
                    <span style="margin-right: 5px;"><?= isset($d['nama_karyawan']) ? $d['nama_karyawan'] : 'User' ?></span>
                    <b class="caret"></b>
                </a>
                <ul class="dropdown-menu dropdown-user" style="border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.1); margin-top: 0;">
                    <li style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 15px; margin-bottom: 5px;">
                        <div style="color: white; font-weight: 600;">
                            <i class="fa fa-user fa-fw"></i> <?= isset($d['nama_karyawan']) ? $d['nama_karyawan'] : '' ?>
                        </div>
                        <div style="color: rgba(255,255,255,0.8); font-size: 12px; margin-top: 5px;">
                            <?= isset($d['nama_jabatan']) ? $d['nama_jabatan'] : '' ?>
                        </div>
                    </li>
                    <li><a href="<?php echo $url.$menu?>setting" style="padding: 10px 15px;"><i class="fa fa-gear fa-fw"></i> Settings</a>
                    </li>
                    <li class="divider" style="margin: 5px 0;"></li>
                    <li><a href="<?php echo $url.$menu?>logout" style="padding: 10px 15px; color: #ef4444;"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                    </li>
                </ul>
            </li>
        </ul>

        <!-- Sidebar -->
        <div class="navbar-default sidebar" role="navigation" style="background: #1e293b;">
            <div style="overflow-y: auto; height: calc(100vh - 51px); position: fixed; width: 250px; background: #1e293b;">
                    
                <div class="sidebar-nav navbar-collapse" style="padding: 0;">

                    <ul class="nav" id="side-menu" style="background: #1e293b;">
                        <li class="sidebar-search" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 20px; margin: 0; border: none;">
                            <div class="text" style="color: white; font-weight: 700; font-size: 15px; letter-spacing: 1.2px; text-align: center;">
                                <i class="fa fa-bars" style="margin-right: 8px;"></i>MENU
    						  <!-- <img src="<?= $url ?>/assets/logo.png" style="width:20px" class="img-thumbnail rounded" alt="...">	 -->

    						</div>
                        </li>
                        <div></div>
    					<?php include"view/menu.php"; ?>
                    </ul>

                </div>
            </div>
        </div>
    </nav>
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation" style="border: none;">
        <div class="navbar-header">
            <a class="navbar-brand" href="<?=$url ?>" style="font-weight: 600; font-size: 18px;"><?= isset($d['nama_cabang']) ? strtoupper($d['nama_cabang']) : '' ?></a>
        </div>

        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>

        <!-- Top Navigation: Left Menu -->
        <ul class="nav navbar-nav navbar-left navbar-top-links">
            <!-- <li><a href="<?=$url ?>"><i class="fa fa-home fa-fw"></i> PAGADEN</a></li> -->
        </ul>

        <!-- Top Navigation: Right Menu -->
        <ul class="nav navbar-right navbar-top-links">
            
			<li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fa fa-user fa-fw"></i>  <b class="caret"></b>
                </a>
                <ul class="dropdown-menu dropdown-user">
                    <li><a href="#"><i class="fa fa-user fa-fw"></i> <?= isset($d['nama_karyawan']) ? $d['nama_karyawan'] : '' ?></a>
                    </li>
                    <li><a href="<?php echo $url.$menu?>setting"><i class="fa fa-gear fa-fw"></i> Settings</a>
                    </li>
                    <li class="divider"></li>
                    <li><a href="<?php echo $url.$menu?>logout"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                    </li>
                </ul>
            </li>
        </ul>

        <!-- Sidebar -->
        <div class="navbar-default sidebar " role="navigation">
            <div style="overflow-y: auto; height: calc(100vh - 51px); position: fixed; width: 250px;">
                    
                <div class="sidebar-nav navbar-collapse "    class='bg-dark'>

                    <ul class="nav" id="side-menu">
                        <li class="sidebar-search" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 20px; margin: 0;">
                            <div class="text" style="color: white; font-weight: 600; font-size: 14px; letter-spacing: 1px;">
                                MENU
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
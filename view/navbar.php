<nav class="navbar navbar-inverse navbar-fixed-top " role="navigation">
        <div class="navbar-header">
            <a class="navbar-brand" href="<?=$url ?>">KOMIDA</a>
        </div>

        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>

        <!-- Top Navigation: Left Menu -->
        <ul class="nav navbar-nav navbar-left navbar-top-links">
            <li><a href="<?=$url ?>"><i class="fa fa-home fa-fw"></i> PAGADEN</a></li>
        </ul>

        <!-- Top Navigation: Right Menu -->
        <ul class="nav navbar-right navbar-top-links">
            <li class="navbar-inverse">
                
                <a class="" href="<?php echo $url.$menu?>logout">
                     <i class="fa fa-sign-out fa-fw"></i> 
                </a>
            </li>
			<li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fa fa-user fa-fw"></i>  <b class="caret"></b>
                </a>
                <ul class="dropdown-menu dropdown-user">
                    <li><a href="#"><i class="fa fa-user fa-fw"></i> <?=$d['nama_karyawan'] ?></a>
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
            <div style="overflow: 0">
                    
                <div class="sidebar-nav navbar-collapse "    class='bg-dark'>

                    <ul class="nav" id="side-menu">
                        <li class="sidebar-search">
                            <div class="text">
    						  <img src="<?= $url ?>/assets/logo.png" style="width:50px" class="img-thumbnail rounded" alt="...">	

    						</div>
                        </li>
                        <div></div>
    					<?php include"view/menu.php"; ?>
                    </ul>

                </div>
            </div>
        </div>
    </nav>
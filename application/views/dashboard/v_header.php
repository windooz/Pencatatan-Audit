<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Malas Ngoding | Dashboard</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/Ionicons/css/ionicons.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/AdminLTE.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/skins/_all-skins.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/morris.js/morris.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/jvectormap/jquery-jvectormap.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/bootstrap-daterangepicker/daterangepicker.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition skin-blue sidebar-mini">
	<div class="wrapper">

		<header class="main-header">			
			<a href="<?php echo base_url(); ?>" class="logo">
				<span class="logo-mini"><b>MN</b></span>
				<span class="logo-lg">Audit<b>S</b></span>
			</a>
			
			<nav class="navbar navbar-static-top">
				
				<a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
					<span class="sr-only">Toggle navigation</span>
				</a>

				<div class="navbar-custom-menu">
					<ul class="nav navbar-nav">
						<li class="dropdown user user-menu">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<img src="<?php echo base_url(); ?>assets/dist/img/user.png" class="user-image" alt="User Image">
								<span class="hidden-xs"><b><?php echo $this->session->userdata('level') ?></b></span>
							</a>
							<ul class="dropdown-menu">
								<li class="user-header">
									<img src="<?php echo base_url(); ?>assets/dist/img/user.png" class="img-circle" alt="User Image">
									<p>
										<?php echo $this->session->userdata('username') ?>
										<small>Hak akses : <?php echo $this->session->userdata('level') ?></small>
									</p>
								</li>
								
								<li class="user-footer">
									<div class="pull-left">
										<a href="<?php echo base_url().'dashboard/profil' ?>" class="btn btn-default btn-flat">Profil</a>
									</div>
									<div class="pull-right">
										<a href="<?php echo base_url().'dashboard/keluar' ?>" class="btn btn-default btn-flat">Keluar</a>
									</div>
								</li>
							</ul>
						</li>
						
					</ul>
				</div>
			</nav>
		</header>
		<aside class="main-sidebar">
			<section class="sidebar">
				<div class="user-panel">
					<div class="pull-left image">
						<img src="<?php echo base_url(); ?>assets/dist/img/user.png" >
					</div>
					<div class="pull-left info">
						<?php 
						$id_user = $this->session->userdata('id');
						$user = $this->db->query("select * from pengguna where pengguna_id='$id_user'")->row();
						?>
						<p><?php echo $user->pengguna_nama; ?></p>
						<a href="#"><i class="fa fa-circle text-success"></i> Online</a>
					</div>
				</div>
				
				<ul class="sidebar-menu" data-widget="tree">
					<li class="header">MAIN NAVIGATION</li>
					<li>
						<a href="<?php echo base_url().'dashboard' ?>">
							<i class="fa fa-dashboard"></i>
							<span>DASHBOARD</span>


						</a>
					</li>
					<?php 
					if($this->session->userdata('level') == "auditor"){
					?>
					<li>
						<a href="<?php echo base_url().'dashboard/artikel' ?>">
							<i class="fa fa-pencil"></i>
							<span>MULAI AUDIT</span>
						</a>
					</li>
					<?php
					} 
					?>
					
					<li>
						<a href="<?php echo base_url().'dashboard/audit' ?>">
							<i class="fa fa-th"></i>
							<span>DAFTAR AUDIT</span>
						</a>
					</li>
					
					<li>
						<a href="<?php echo base_url().'dashboard/perusahaan' ?>">
							<i class="fa fa-th"></i>
							<span>DAFTAR PERUSAHAAN</span>
						</a>
					</li>
					<?php 
					if($this->session->userdata('level') == "admin"){
					?>

					<li>
						<a href="<?php echo base_url().'dashboard/soal' ?>">
							<i class="fa fa-pencil"></i>
							<span>CHECKLIST</span>
						</a>
					</li>

					
					
					<li>
						<a href="<?php echo base_url().'dashboard/pengguna' ?>">
							<i class="fa fa-users"></i>
							<span>PENGGUNA & HAK AKSES</span>
						</a>
					</li>
					<?php
					} 
					?>
				</ul>
			</section>
		</aside>
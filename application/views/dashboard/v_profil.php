<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Profil
			<small>Profil Pengguna</small>
		</h1>
	</section>

	<section class="content">

		<div class="row">
			<div class="col-lg-6">
				<div class="box box-primary">
					<div class="box-header">
						<h3 class="box-title">Dashboard</h3>
					</div>
					<div class="box-body">
						<h3>Selamat Datang !</h3>

						<div class="table-responsive">
							<table class="table table-bordered table-hover">
								<tr>
									<th width="%">Nama</th>
									<th width="1px">:</th>
									<td>
										<?php 
										$id_user = $this->session->userdata('id');
										$user = $this->db->query("select * from pengguna where pengguna_id='$id_user'")->row();
										?>
										<p><?php echo $user->pengguna_nama; ?></p>
									</td>
								</tr>
								<tr>
									<th width="20%">Username</th>
									<th width="1px">:</th>
									<td><?php echo $this->session->userdata('username') ?></td>
								</tr>
								<tr>
									<th width="20%">Level</th>
									<th width="1px">:</th>
									<td><?php echo $this->session->userdata('level') ?></td>
								</tr>
								<tr>
									<th width="20%">Status</th>
									<th width="1px">:</th>
									<td>Aktif</td>
								</tr>
							</table>
						</div>
					</div>
				</div>
				<a href="<?php echo base_url().'dashboard/ganti_password'; ?>" class="btn btn-sm btn-primary">Ganti Password</a>

			</div>
		</div>

	</section>

</div>
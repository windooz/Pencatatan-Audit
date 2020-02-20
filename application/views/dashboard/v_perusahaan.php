<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Daftar
			<small>Daftar Perusahaan</small>
		</h1>
	</section>

	<section class="content">

		<div class="row">
			<div class="col-lg-9">
				
				<a href="<?php echo base_url().'dashboard/perusahaan_tambah'; ?>" class="btn btn-sm btn-primary">Masukkan Nama Perusahaan baru</a>

				<br/>
				<br/>

				<div class="box box-primary">
					<div class="box-header">
						<h3 class="box-title">Daftar Perusahaan</h3>
					</div>
					<div class="box-body">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th width="1%">NO</th>
									<th>Perusahaan</th>
									<th>Slug</th>
									<th width="10%">OPSI</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$no = 1;
								foreach($perusahaan as $k){ 
									?>
									<tr>
										<td><?php echo $no++; ?></td>
										<td><?php echo $k->perusahaan_nama; ?></td>
										<td><?php echo $k->perusahaan_slug; ?></td>
										<td>
											<a href="<?php echo base_url().'dashboard/perusahaan_edit/'.$k->perusahaan_id; ?>" class="btn btn-warning btn-sm"> <i class="fa fa-pencil"></i> </a>
											<a href="<?php echo base_url().'dashboard/perusahaan_hapus/'.$k->perusahaan_id; ?>" class="btn btn-danger btn-sm"> <i class="fa fa-trash"></i> </a>
										</td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
						

					</div>
				</div>

			</div>
		</div>

	</section>

</div>
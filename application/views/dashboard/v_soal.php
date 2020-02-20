<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Checklist
			
		</h1>
	</section>

	<section class="content">

		<div class="row">
			<div class="col-lg-9">
				
				<a href="<?php echo base_url().'dashboard/soal_tambah'; ?>" class="btn btn-sm btn-primary">Masukkan Checklist</a>

				<br/>
				<br/>

				<div class="box box-primary">
					<div class="box-header">
						<h3 class="box-title">Daftar Checklist</h3>
					</div>
					<div class="box-body">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th width="1%">NO</th>
									<th>Checklist</th>
									<th>Catatan</th>
									<th width="10%">OPSI</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$no = 1;
								foreach($soal as $k){ 
									?>
									<tr>
										<td><?php echo $no++; ?></td>
										<td><?php echo $k->soal; ?></td>
										<td><?php echo $k->note; ?></td>
										<td>
											<a href="<?php echo base_url().'dashboard/soal_edit/'.$k->soal_id; ?>" class="btn btn-warning btn-sm"> <i class="fa fa-pencil"></i> </a>
											<a href="<?php echo base_url().'dashboard/soal_hapus/'.$k->soal_id; ?>" class="btn btn-danger btn-sm"> <i class="fa fa-trash"></i> </a>
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
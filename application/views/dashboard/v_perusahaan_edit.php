<div class="content-wrapper">
	<section class="content-header">
		<h1>
			perusahaan
			<small>perusahaan Artikel</small>
		</h1>
	</section>

	<section class="content">

		<div class="row">
			<div class="col-lg-6">
				<a href="<?php echo base_url().'dashboard/perusahaan'; ?>" class="btn btn-sm btn-primary">Kembali</a>
				
				<br/>
				<br/>

				<div class="box box-primary">
					<div class="box-header">
						<h3 class="box-title">perusahaan</h3>
					</div>
					<div class="box-body">
						
						<?php foreach($perusahaan as $k){ ?>

							<form method="post" action="<?php echo base_url('dashboard/perusahaan_update') ?>">
								<div class="box-body">
									<div class="form-group">
										<label>Nama perusahaan</label>
										<input type="hidden" name="id" value="<?php echo $k->perusahaan_id; ?>">
										<input type="text" name="perusahaan" class="form-control" placeholder="Masukkan nama perusahaan .." value="<?php echo $k->perusahaan_nama; ?>">
										<?php echo form_error('perusahaan'); ?>
									</div>
								</div>

								<div class="box-footer">
									<input type="submit" class="btn btn-success" value="Update">
								</div>
							</form>

						<?php } ?>

					</div>
				</div>

			</div>
		</div>

	</section>

</div>
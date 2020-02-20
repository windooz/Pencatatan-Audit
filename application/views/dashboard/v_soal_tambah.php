<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Checklist
			
		</h1>
	</section>

	<section class="content">

		<div class="row">
			<div class="col-lg-6">
				<a href="<?php echo base_url().'dashboard/soal'; ?>" class="btn btn-sm btn-primary">Kembali</a>
				
				<br/>
				<br/>

				<div class="box box-primary">
					<div class="box-header">
						<h3 class="box-title">Checklist</h3>
					</div>
					<div class="box-body">
						
						
						<form method="post" action="<?php echo base_url('dashboard/soal_aksi') ?>">
							<div class="box-body">
								<div class="form-group">
									<label>Checklist</label>
									<textarea type="text" id="editor" name="soal" class="form-control" placeholder="Masukkan checklist .."> </textarea>
									<?php echo form_error('soal'); ?>
								</div>
							</div>

							<div class="box-body">
								<div class="form-group">
									<label>Catatan</label>
									<textarea type="text" name="note" class="form-control" placeholder="Masukkan catatan .."> </textarea>
									<?php echo form_error('note'); ?>
								</div>
							</div>
							<div class="box-footer">
								<input type="submit" class="btn btn-success" value="Simpan">
							</div>
						</form>

					</div>
				</div>

			</div>
		</div>

	</section>

</div>
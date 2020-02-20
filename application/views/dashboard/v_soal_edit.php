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
						
						<?php foreach($soal as $k){ ?>

							<form method="post" action="<?php echo base_url('dashboard/soal_update') ?>">
								
								<div class="box-body">
									<div class="form-group">
										<label>Checklist</label>
										<input type="hidden" name="id" value="<?php echo $k->soal_id; ?>">
										<textarea id="editor"  name="soal" class="form-control" placeholder="Masukkan Checklist" > <?php echo $k->soal; ?> </textarea>
										</br>
										<?php echo form_error('soal'); ?>
									</div>
								</div>

								<div class="box-body">
									<div class="form-group">
										<label>Catatan</label>
										<?php echo form_error('note'); ?>
										</br>
										<textarea  name="note" class="form-control"> <?php echo $k->note; ?> </textarea>
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
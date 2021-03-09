<script>

 //datatables
	var table;

	table = $('#example').DataTable({ 
				"processing": true, 
				"serverSide": true, 
				"order": [], 
					
				"ajax": {
					"url": "<?php echo base_url('master/supplier/get_supplier')?>",
					"type": "POST",
				},
				"columnDefs": [
					{ 
						"targets": [ 0 ], 
						"orderable": false, 
					},
				],		
			});

// Master Supplier

	$('#tambah-supplier').validate({
		rules: {
			nama_supplier: {
				required: true
			},
			no_telepon: {
				required: true
			},
			alamat: {
				required: true
			},
		},
		messages: {
			nama_supplier: {
				required: "harus di isi"
			},
			no_telepon: {
				required: "harus di isi"
			},
			alamat: {
				required: "harus di isi"
			},
		},
		errorElement: 'span',
		errorPlacement: function (error, element) {
			error.addClass('invalid-feedback');
			element.closest('.col-sm-8').append(error);
		},
		highlight: function (element, errorClass, validClass) {
			$(element).addClass('is-invalid');
		},
		unhighlight: function (element, errorClass, validClass) {
			$(element).removeClass('is-invalid');
		},
		submitHandler: function (form) {
			const href = $('#tambah-supplier').data('url');
			$.ajax({
				type: 'POST',
				url: href,
				data: $('#tambah-supplier').serialize(),
				dataType: 'json',
				success: function (output) {
					if (output.status == 200) {
							Swal.fire({
								type: 'success',
								position: 'top',
								title: output.message,
								showConfirmButton: false,
								timer: 1050,
							})
							$('#modal-tambah').modal('hide');
							$('#tambah-supplier')[0].reset();
							$("input[name='id_supplier']").val(output.auto_kode);
							table.ajax.reload();
						} else {
							Swal.fire({
								type: 'error',
								position: 'top',
								title: output.message,
								showConfirmButton: false,
								timer: 1050,
							})
						}
				}
			});
		}
	});

	$('#example').on('click', '.edit-supplier', function () {
		const Id = $(this).data('id');

		$.ajax({
			type: 'POST',
			url: '<?=base_url('master/supplier/get_supplier_byId')?>',
			data: {
				Id: Id
			},
			dataType: 'json',
			success: function (spl) {
				$("input[name='id_supplier']").val(spl.id_supplier);
				$("input[name='nama_supplier']").val(spl.nama_supplier);
				$("input[name='no_telepon']").val(spl.no_telepon);
				$('#alamat').val(spl.alamat);
			}
		});
	})

	$('#edit-supplier').validate({
		rules: {
			nama_supplier: {
				required: true
			},
			no_telepon: {
				required: true
			},
			alamat: {
				required: true
			},
		},
		messages: {
			nama_supplier: {
				required: "harus di isi"
			},
			no_telepon: {
				required: "harus di isi"
			},
			alamat: {
				required: "harus di isi"
			},
		},
		errorElement: 'span',
		errorPlacement: function (error, element) {
			error.addClass('invalid-feedback');
			element.closest('.col-sm-8').append(error);
		},
		highlight: function (element, errorClass, validClass) {
			$(element).addClass('is-invalid');
		},
		unhighlight: function (element, errorClass, validClass) {
			$(element).removeClass('is-invalid');
		},
		submitHandler: function (form) {
			const href = $('#edit-supplier').data('url');
			$.ajax({
				type: 'POST',
				url: href,
				data: $('#edit-supplier').serialize(),
				dataType: 'json',
				success: function (output) {
					if (output.status == 200) {
							Swal.fire({
								type: 'success',
								position: 'top',
								title: output.message,
								showConfirmButton: false,
								timer: 1050,
							})
							$('#modal-edit-supplier').modal('hide');
							$('#edit-supplier')[0].reset();
							table.ajax.reload();
						} else {
							Swal.fire({
								type: 'error',
								position: 'top',
								title: output.message,
								showConfirmButton: false,
								timer: 1050,
							})
						}
				}
			});
		}
	});

	$('#example').on('click', '.delete-supplier', function () {
		const Id = $(this).data('id');
		const Name = $(this).data('nama');
		console.log(Id, Name);
		Swal.fire({
			type: 'warning',
			title: 'Hapus Supplier?',
			text: Name + ' akan dihapus',
			showCancelButton: true,
			confirmButtonColor: '#d33',
			confirmButtonText: 'Hapus',
			cancelButtonText: 'Batal',
			preConfirm: (login) => {
				console.log("TRUE");
				$.ajax({
					type: 'POST',
					url: '<?= base_url('master/supplier/hapus')?>',
					data: {
						Id: Id
					},
					dataType: 'json',
					success: function (output) {
						if (output.status == 200) {
							Swal.fire({
								type: 'success',
								position: 'top',
								title: output.message,
								showConfirmButton: false,
								timer: 1050,
							})
							$('#modal-tambah').modal('hide');
							$('#tambah-supplier')[0].reset();
							$("input[name='id_supplier']").val(output.auto_kode_supplier);
							table.ajax.reload();
						} else {
							Swal.fire({
								type: 'error',
								position: 'top',
								title: output.message,
								showConfirmButton: false,
								timer: 1050,
							})
						}
					}
				})
			},
		})
	})

</script>
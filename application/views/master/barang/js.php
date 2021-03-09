<script>
       //datatables
		var table;

			table = $('#example').DataTable({ 
				"processing": true, 
				"serverSide": true, 
				
				"ajax": {
					"url": "<?php echo base_url('master/barang/get_barang')?>",
					"type": "POST",
				},
				"columnDefs": [
					{ 
						"targets": [ 0 ], 
						"orderable": false, 
					},
				],
				
			});

var table_supplier;
console.log(table_supplier);

 $('#Modal-Supplier').modal('hide');
 $('#table-supplier').DataTable({ 
				 "processing": true, 
				 "serverSide": true, 
				 "order": [], 
				 
				 "ajax": {
					 "url": "<?php echo base_url('master/barang/get_supplier')?>",
					 "type": "POST",
				 },
				 createdRow: function( row, data, dataIndex ) {
					 $( row )
					 .addClass('supplier')
					 .attr('data-id', data[1])
					 .attr('data-nama',data[2])
					 
					 console.log(data[1]);
					 console.log(row);
				 },
				 "columnDefs": [
					 { 
						 "targets": [ 0 ], 
						 "orderable": false, 
					 },
				 ],
				 
			 });

 $('#pilih-supplier').on('click', function () {
	$('#Modal-Supplier').modal('show');
 })

$('#table-supplier').on('click', '.supplier', function () {
	const Id = $(this).attr('data-id');
	const Name = $(this).attr('data-nama');

	$("input[name='id_supplier']").val(Id);
	$("input[name='nama_supplier']").val(Name);

	$('#Modal-Supplier').modal('hide');
});

$('#tambah-barang').validate({
	rules: {
		kode_barcode: {
			required: true
		},
		nama_barang: {
			required: true
		},
		harga_beli: {
			required: true
		},
		nama_supplier: {
			required: true
		},
		kategori: {
			required: true
		},
		satuan: {
			required: true
		},
	},
	messages: {
		kode_barcode: {
			required: "harus di isi"
		},
		nama_barang: {
			required: "harus di isi"
		},
		harga_beli: {
			required: "harus di isi"
		},
		kategori: {
			required: "harus di isi"
		},
		nama_barang: {
			required: "harus di isi"
		},
		satuan: {
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
		console.log('success');
		const href = $('#tambah-barang').data('url');
		$.ajax({
			type: 'POST',
			url: href,
			data: $('#tambah-barang').serialize(),
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
					$('#modal-tambah-barang').modal('hide');
					$('#tambah-barang')[0].reset();
					$("input[name='id_barang']").val(output.auto_kode);
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

$('#example').on('click', '.edit-barang', function () {
	const Id = $(this).data('id');

	$.ajax({
		type: 'POST',
		url: '<?= base_url('master/barang/get_barang_byId')?>',
		data: {
			Id: Id
		},
		dataType: 'json',
		success: function (barang) {
			$("input[name='id_barang']").val(barang.id_barang);
			$("input[name='kode_barcode']").val(barang.kode_barcode);
			$("input[name='nama_barang']").val(barang.nama_barang);
			$("input[name='harga_beli']").val(barang.harga_beli);
			$("input[name='stok']").val(barang.stok);
			$("input[name='id_supplier']").val(barang.id_supplier);
			console.log(barang.nama_supplier);
			$("input[name='nama_supplier']").val(barang.nama_supplier);
			$('#satuan option[value=' + barang.satuan + ']').attr("selected", "true");
			$('#kategori option[value=' + barang.id_kategori + ']').attr("selected", "true");
		}
	});
})

$('#edit-barang').validate({
	rules: {
		kode_barcode: {
			required: true
		},
		nama_barang: {
			required: true
		},
		harga_beli: {
			required: true
		},
		satuan: {
			required: true
		},
	},
	messages: {
		kode_barcode: {
			required: "harus di isi"
		},
		nama_barang: {
			required: "harus di isi"
		},
		harga_beli: {
			required: "harus di isi"
		},
		satuan: {
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
		const href = $('#edit-barang').data('url');
		$.ajax({
			type: 'POST',
			url: href,
			data: $('#edit-barang').serialize(),
			dataType: 'json',
			success: function (output) {
				if (output.status == 200) {
					Swal.fire({
						type: 'success',
						position: 'top',
						title: output.message,
						showConfirmButton: false,
						timer: 1000,
					})
					$('#modal-edit-barang').modal('hide');
					$('#edit-barang')[0].reset();
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

$('#example').on('click', '.delete-barang', function () {
	const Id = $(this).data('id');
	const Name = $(this).data('nama');
	console.log(Id, Name);
	Swal.fire({
		type: 'warning',
		title: 'Hapus Barang?',
		text: Name + ' akan dihapus',
		showCancelButton: true,
		confirmButtonColor: '#d33',
		confirmButtonText: 'Hapus',
		cancelButtonText: 'Batal',
		preConfirm: (login) => {
			console.log("TRUE");
			$.ajax({
				type: 'POST',
				url: '<?= base_url('master/barang/hapus')?>',
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

// Master kategori

$('#tambah-kategori').validate({
	rules: {
		nama_kategori: {
			required: true,
			minlength: 3
		},
	},
	messages: {
		nama_kategori: {
			required: "Nama Kategori harus diisi",
			minlength: "Isi dengan benar"
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
		const href = $('#tambah-kategori').data('url');
		$.ajax({
			type: 'POST',
			url: href,
			data: $('#tambah-kategori').serialize(),
			dataType: 'json',
			success: function (output) {
				if (output.status == 200) {
					Swal.fire({
						type: 'success',
						position: 'top',
						title: output.message,
						showConfirmButton: false,
						timer: 1000,
					})
					window.setTimeout(function () {
						location.reload();
					}, 1070);

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

$('.edit-kategori').on('click', function () {
	const Id = $(this).data('id');

	$.ajax({
		type: 'POST',
		url: '<?= base_url('master/barang/get_ktg_byId') ?>',
		data: {
			Id: Id
		},
		dataType: 'json',
		success: function (ktg) {
			$("input[name='id_kategori']").val(ktg.id_kategori);
			$("input[name='nama_kategori']").val(ktg.nama_kategori);
		}
	});
})

$('#edit-kategori').validate({
	rules: {
		nama_kategori: {
			required: true,
			minlength: 3
		},
	},
	messages: {
		nama_kategori: {
			required: "Nama Kategori harus diisi",
			minlength: "Isi dengan benar"
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
		const href = $('#edit-kategori').data('url');
		$.ajax({
			type: 'POST',
			url: href,
			data: $('#edit-kategori').serialize(),
			dataType: 'json',
			success: function (output) {
				if (output.status == 200) {
					Swal.fire({
						type: 'success',
						position: 'top',
						title: output.message,
						showConfirmButton: false,
						timer: 1000,
					})
					window.setTimeout(function () {
						location.reload();
					}, 1070);

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

$('.delete-kategori').on('click', function () {
	const Id = $(this).data('id');
	const Name = $(this).data('nama');
	console.log(Id, Name);
	Swal.fire({
		type: 'warning',
		title: 'Hapus Kategori?',
		text: Name + ' akan dihapus',
		showCancelButton: true,
		confirmButtonColor: '#d33',
		confirmButtonText: 'Hapus',
		cancelButtonText: 'Batal',
		preConfirm: (login) => {
			console.log("TRUE");
			$.ajax({
				type: 'POST',
				url: '<?= base_url('master/barang/hapus_kategori') ?>',
				data: {
					Id: Id
				},
				dataType: 'json',
				success: function (output) {
					get_swal_notif(output);
				}
			})
		},
	})
})

</script>
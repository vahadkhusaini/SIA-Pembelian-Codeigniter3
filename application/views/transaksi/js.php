<script>

$(document).on('click', '.delete-cart', function () {
	const Id = $(this).attr("id"); //mengambil row_id dari artibut id

	$.ajax({
		type: "POST",
		url: '<?= base_url('transaksi/cart/delete')?>',
		data: {
			Id: Id
		},
		success: function (data) {
			$('#show_cart').html(data);
		}
	});
});

$(document).on('click', '.update-cart', function () {
	const Kode = $(this).attr("data-id"); //mengambil row_id dari artibut id
	const Barcode = $(this).attr("data-barcode");
	const Name = $(this).attr("data-name");
	const Qty = $(this).attr("data-qty");
	const Harga = $(this).attr("data-price");
	const Satuan = $(this).attr("data-satuan");

	$("input[name='kode_barang']").val(Kode);
	$("input[name='barcode']").val(Barcode);
	$("input[name='nama_barang']").val(Name);
	$("input[name='jumlah']").val(Qty);
	$("input[name='harga_barang']").val(Harga);
	$('#satuan option[value=' + Satuan + ']').attr("selected", "true");
	// $("input[name='satuan']").val(Satuan);

	const Id = $(this).attr("data-rowid"); //mengambil row_id dari artibut id
	console.log(Id);

	$.ajax({
		type: "POST",
		url: '<?= base_url('transaksi/cart/delete')?>',
		data: {
			Id: Id
		},
		success: function (data) {
			$('#show_cart').html(data);
		}
	});
});

$(document).on('click', '.destroy-cart', function () {
	$.ajax({
		type: 'POST',
		url: '<?= base_url('transaksi/cart/destroy')?>',
		success: function (data) {
			$('#show_cart').html(data);
		}
	});
});

// Transaksi

function get_total() {
	const total = $('#jumlah').val() * $('#harga_barang').val();
	$('#total').val(total);
}

$('#jumlah').on('keyup', function () {
	get_total();
});

$('#harga_barang').on('keyup', function () {
	get_total();
});

function empty() {
	$('#kode_barang').val('');
	$('#barcode').val('');
	$('#nama_barang').val('');
	$('#jumlah').val(null);
	$('#harga_barang').val(null);
	$('#total').val(null);
}


$('#sel-brg').on('click', function () {
	const Id = $("input[name='id_supplier']").val();
	console.log(Id);

	$.ajax({
		type: 'POST',
		url: '<?= base_url('master/barang/show_barang')?>',
		data: {
			Id: Id
		},
		success: function (data) {
			$('#show_brg').html(data);
		}
	});
})

$('#example2').on('click', '.barang', function () {
	const Kode = $(this).attr('data-kode');
	const Barcode = $(this).attr('data-barcode');
	const Name = $(this).attr('data-nama');
	const Harga = $(this).attr('data-harga');
	const Satuan = $(this).attr('data-satuan');

	$("input[name='kode_barang']").val(Kode);
	$("input[name='barcode']").val(Barcode);
	$("input[name='nama_barang']").val(Name);
	$("input[name='harga_barang']").val(0);
	//$("input[name='satuan']").val(Satuan);
	$('#satuan option[value=' + Satuan + ']').attr("selected", "true");
	$('#jumlah').val(null);
	$('#total').val(null);

	$('#Modal-Barang').modal('hide');
});

$('.table').on('click', '.pesanan', function () {

	const Id = $(this).attr('data-id');
	const Id_supp = $(this).attr('data-id-supp');
	const Name = $(this).data('nama');
	const Status = $(this).data('status');
	console.log(Id);

	if (Status == 'Selesai') {
		Swal.fire({
			type: 'error',
			position: 'top',
			title: 'Pemesanan sudah diproses!',
			showConfirmButton: false,
			timer: 1050,
		})
	} else {

		$("input[name='no_pemesanan']").val(Id);
		$("input[name='id_supplier']").val(Id_supp);
		$("input[name='nama_supplier']").val(Name);
		$("textarea[name='keterangan']").val('Pembelian dari ' + Name);

		$.ajax({
			type: 'POST',
			url: '<?= base_url('transaksi/cart/get_order')?>',
			data: {
				Id
			},
			success: function (data) {
				$('#show_cart').html(data);
			}
		});
		$('#Modal-Pemesanan').modal('hide');
	}
});

$('#tambah-pemesanan').validate({
	rules: {
		no_pemesanan: {
			required: true
		},
		id_supplier: {
			required: true
		},
		nama_supplier: {
			required: true
		},
	},
	messages: {
		no_pemesanan: {
			required: "No Pemesanan harus diisi"
		},
		id_supplier: {
			required: "ID Supplier harus diisi"
		},
		nama_supplier: {
			required: "Nama Supplier harus diisi"
		},
	},
	errorElement: 'span',
	errorPlacement: function (error, element) {
		error.addClass('invalid-feedback');
		element.closest('.col-md-8').append(error);
	},
	highlight: function (element, errorClass, validClass) {
		$(element).addClass('is-invalid');
	},
	unhighlight: function (element, errorClass, validClass) {
		$(element).removeClass('is-invalid');
	},
	submitHandler: function (form) {
		Swal.fire({
			title: 'Simpan transaksi pemesanan?',
			text: "Pastikan semua data benar!",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Simpan',
			preConfirm: (yes) => {
				$.ajax({
					type: 'POST',
					url: '<?= base_url('transaksi/pemesanan/simpan')?>',
					data: $('#tambah-pemesanan').serialize(),
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
								window.location.href = '<?= base_url('transaksi/pemesanan')?>'
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
				})

			},
		})
	}
});

$('#tambah-pembelian').validate({
	rules: {
		no_nota: {
			required: true
		},
		no_pemesanan: {
			required: true
		},
		id_supplier: {
			required: true
		},
		nama_supplier: {
			required: true
		},
		keterangan: {
			required: true
		},
		tempo: {
			required: true
		},
	},
	messages: {
		no_nota: {
			required: "No Nota harus diisi"
		},
		no_pemesanan: {
			required: "No Pemesanan harus diisi"
		},
		id_supplier: {
			required: "ID Supplier harus diisi"
		},
		nama_supplier: {
			required: "Nama Supplier harus diisi"
		},
		keterangan: {
			required: "Keterangan tidak boleh kosong"
		},
		tempo: {
			required: "Tempo harus diisi"
		},
	},
	errorElement: 'span',
	errorPlacement: function (error, element) {
		error.addClass('invalid-feedback');
		element.closest('.col-md-7').append(error);
	},
	highlight: function (element, errorClass, validClass) {
		$(element).addClass('is-invalid');
	},
	unhighlight: function (element, errorClass, validClass) {
		$(element).removeClass('is-invalid');
	},
	submitHandler: function (form) {
		Swal.fire({
			title: 'Simpan transaksi pembelian?',
			text: "Pastikan semua data benar!",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Simpan',
			preConfirm: (yes) => {
				$.ajax({
					type: 'POST',
					url: '<?= base_url('transaksi/pembelian/simpan')?>',
					data: $('#tambah-pembelian').serialize(),
					dataType: 'json',
					success: function (output) {
						if (output.status == 200) {
							Swal.fire({
								type: 'success',
								position: 'top',
								title: output.message,
								showConfirmButton: false,
								timer: 10000,
							})
							window.setTimeout(function () {
								window.location.href = '<?= base_url('transaksi/pembelian')?>'
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
				})
			},
		})
	}
});

$(document).ready(function() {
	$('.print-pemesanan').click(function (event) {
		event.preventDefault();

		const href = $(this).attr('href');
		console.log(href);
		Swal.fire({
			type: 'warning',
			title: 'Cetak Bukti Pemesanan?',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			confirmButtonText: 'Cetak',
			cancelButtonText: 'Batal',
		}).then((result) => {
			if (result.value) {
				window.open(href, '_blank');
			}
		})
	});
}); 

$('.print-pembelian').on('click', function (e) {
	e.preventDefault();

	const href = $(this).attr('href');
	console.log(href);
	Swal.fire({
		type: 'warning',
		title: 'Cetak Faktur Pembelian?',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		confirmButtonText: 'Cetak',
		cancelButtonText: 'Batal',
	}).then((result) => {
		if (result.value) {
			window.open(href, '_blank');
		}
	})
});

// Retur 

$('.table').on('click', '.pembelian', function () {
	const Id = $(this).attr('data-id');
	const Id_supp = $(this).attr('data-id-supp');
	const Name = $(this).data('nama');
	const Status = $(this).data('status');

	if (Status == 'lunas') {
		Swal.fire({
			type: 'error',
			position: 'top',
			title: 'Pembelian sudah lunas!',
			showConfirmButton: false,
			timer: 1050,
		})
	} else {

		$("input[name='no_nota']").val(Id);
		$("input[name='no_retur']").val("RTB" + Id);
		$("input[name='id_supplier']").val(Id_supp);
		$("input[name='nama_supplier']").val(Name);
		$("textarea[name='keterangan']").val('Retur Pembelian Untuk ' + Name);

		console.log(Name);
		$('#Modal-Pembelian').modal('hide');
	}
});

$('#add_to_cart_retur').on('click', function () {

	if ($('#kode_barang').val() == "") {
		Swal.fire({
			type: 'error',
			position: 'top',
			title: 'Pilih data barang',
			showConfirmButton: false,
			timer: 1050,
		})
	} else if ($('#jumlah').val() == "" || $('#jumlah').val() == 0) {
		Swal.fire({
			type: 'error',
			position: 'top',
			title: 'Masukkan jumlah barang',
			showConfirmButton: false,
			timer: 1050,
		})
	} else if ($('#harga_barang').val() == "") {
		Swal.fire({
			type: 'error',
			position: 'top',
			title: 'Masukkan harga barang',
			showConfirmButton: false,
			timer: 1050,
		})
	} else {

		const Kode = $('#kode_barang').val();
		const Barcode = $('#barcode').val();
		const Name = $('#nama_barang').val();
		let Qty = $('#jumlah').val();
		const Harga = $('#harga_barang').val();
		const subtotal = Harga * Qty;
		let PPn = 0;

		console.log($('#pajak').val());
		console.log(subtotal);

		if ($('#pajak').val() == 1) {
			Ppn = subtotal * 0.10;
		} else {
			Ppn = 0;
		}

		$.ajax({
			type: 'POST',
			url: '<?= base_url('transaksi/retur/cek_stok')?>',
			dataType: 'json',
			data: {
				Kode: Kode
			},
			success: function (data) {
				console.log(data.stok);
				console.log(Qty);
				let stok = data.stok;

				if (data.stok < Qty) {

					Swal.fire({
						type: 'error',
						position: 'top',
						title: 'Retur lebih dari stok!',
						showConfirmButton: false,
						timer: 1300,
					})
				} else {
					$.ajax({
						type: 'POST',
						url: '<?= base_url('transaksi/cart/add')?>',
						data: {
							Kode: Kode,
							Barcode: Barcode,
							Name: Name,
							Qty: Qty,
							Harga: Harga,
							Ppn: Ppn
						},
						success: function (data) {
							$('#kode_barang').val("");
							$('#barcode').val("");
							$('#nama_barang').val("");
							$('#jumlah').val(null);
							$('#harga_barang').val(null);
							$('#total').val(null);

							$('#show_retur_data').html(data);
						}
					});
				}
			}
		});
	}
});


$('#tambah-retur').validate({
	rules: {
		no_retur: {
			required: true
		},
		tanggal: {
			required: true
		},
		id_supplier: {
			required: true
		},
		nama_supplier: {
			required: true
		},
	},
	messages: {
		no_retur: {
			required: "No Retur harus diisi"
		},
		tanggal: {
			required: "Tanggal harus diisi"
		},
		id_supplier: {
			required: "ID Supplier harus diisi"
		},
		nama_supplier: {
			required: "Nama Supplier harus diisi"
		},
	},
	errorElement: 'span',
	errorPlacement: function (error, element) {
		error.addClass('invalid-feedback');
		element.closest('.col-md-8').append(error);
	},
	highlight: function (element, errorClass, validClass) {
		$(element).addClass('is-invalid');
	},
	unhighlight: function (element, errorClass, validClass) {
		$(element).removeClass('is-invalid');
	},
	submitHandler: function (form) {
		Swal.fire({
			title: 'Simpan Retur Pembelian?',
			text: "Pastikan semua data benar!",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Simpan',
			preConfirm: (yes) => {
				$.ajax({
					type: 'POST',
					url: '<?= base_url('transaksi/retur/simpan')?>',
					data: $('#tambah-retur').serialize(),
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
				})
			},
		})
	}
});

$('.print-retur').on('click', function (e) {
	e.preventDefault();

	const href = $(this).attr('href');
	console.log(href);
	Swal.fire({
		type: 'warning',
		title: 'Cetak Bukti Retur Pembelian?',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		confirmButtonText: 'Cetak',
		cancelButtonText: 'Batal',
	}).then((result) => {
		if (result.value) {
			window.open(href, '_blank');
		}
	})
});

// pelunasan
$('.table').on('click', '.lunasi', function () {

	const Id = $(this).attr('data-id');
	const Id_supp = $(this).attr('data-id-supp');
	const Name = $(this).data('nama');
	const Status = $(this).data('status');
	const Total = $(this).data('total');

	if (Status == 'lunas') {
		Swal.fire({
			type: 'error',
			position: 'top',
			title: 'Pembelian sudah lunas!',
			showConfirmButton: false,
			timer: 1050,
		})
	} else {
		$.ajax({
			type: 'POST',
			url: '<?= base_url('transaksi/pelunasan/cek_retur')?>',
			data: {
				Id: Id
			},
			dataType: 'json',
			success: function (output) {

				if (output.status == 200) {
					$("input[name='no_nota']").val(Id);
					$("input[name='no_pelunasan']").val('BYR' + Id);
					$("input[name='id_supplier']").val(Id_supp);
					$("input[name='nama_supplier']").val(Name);
					$("input[name='total']").val(Total);
					$("input[name='retur']").val(output.total);
					$("textarea[name='keterangan']").val('Pelunasan Untuk ' + Name);

					const total = $('#total').val() - $('#retur').val();
					$('#totalbayar').val(total);
					console.log(total);

					console.log(Name);
					$('#Data-Pembelian').modal('hide');
				} else {

				}
			}
		})

	}
});


$('#tambah-pelunasan').validate({
	rules: {
		no_nota: {
			required: true
		},
		tanggal: {
			required: true
		},
		id_supplier: {
			required: true
		},
		nama_supplier: {
			required: true
		},
	},
	messages: {
		no_nota: {
			required: "No Nota harus dipilih"
		},
		tanggal: {
			required: "Tanggal harus diisi"
		},
		id_supplier: {
			required: "ID Supplier harus diisi"
		},
		nama_supplier: {
			required: "Nama Supplier harus diisi"
		},
	},
	errorElement: 'span',
	errorPlacement: function (error, element) {
		error.addClass('invalid-feedback');
		element.closest('.col-md-8').append(error);
	},
	highlight: function (element, errorClass, validClass) {
		$(element).addClass('is-invalid');
	},
	unhighlight: function (element, errorClass, validClass) {
		$(element).removeClass('is-invalid');
	},
	submitHandler: function (form) {
		Swal.fire({
			title: 'Bayar Pelunasan?',
			text: "Pastikan semua data benar!",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Simpan',
			preConfirm: (yes) => {
				$.ajax({
					type: 'POST',
					url: '<?= base_url('transaksi/pelunasan/simpan')?>',
					data: $('#tambah-pelunasan').serialize(),
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
								window.location.href = '<?= base_url('transaksi/pelunasan')?>',
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
				})

			},
		})
	}
});

$('.print-pelunasan').on('click', function (e) {
	e.preventDefault();

	const href = $(this).attr('href');
	console.log(href);
	Swal.fire({
		type: 'warning',
		title: 'Cetak Bukti Pelunasan?',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		confirmButtonText: 'Cetak',
		cancelButtonText: 'Batal',
	}).then((result) => {
		if (result.value) {
			window.open(href, '_blank');
		}
	})
});

// laporan 

$('#cetak').on('click', function (e) {
	e.preventDefault();

	const href = $(this).attr('href');
	Tanggal1 = $("input[name='tanggal1']").val();
	Tanggal2 = $("input[name='tanggal2']").val();
	Swal.fire({
		type: 'warning',
		title: 'Cetak Laporan?',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		confirmButtonText: 'Cetak',
		cancelButtonText: 'Batal',
		preConfirm: (login) => {
			console.log("TRUE");
			window.open(href + Tanggal1 + '/' + Tanggal2, '_blank');
		},
	})
})

$("#tampilkan").on('click', function (e) {
	Tanggal1 = $("input[name='tanggal1']").val();
	Tanggal2 = $("input[name='tanggal2']").val();
	console.log(Tanggal1, Tanggal2);

	$.ajax({
		type: 'POST',
		url: '<?= base_url('laporan/jurnal/get_jurnal_by_date')?>',
		data: {
			Tanggal1: Tanggal1,
			Tanggal2: Tanggal2
		},
		dataType: 'json',
		success: function (data) {
			var html = '';
			let i;
			for (i = 0; i < data.length; i++) {
				console.log(data);
				html += '<tr>' +
					'<td>' + data[i].tanggal + '</td>' +
					'<td>' + data[i].keterangan + '</td>' +
					'<td>' + data[i].id_akun + '</td>' +
					'<td>' + data[i].nama_akun + '</td>' +
					'<td>' + data[i].debet + '</td>' +
					'<td>' + data[i].kredit + '</td>' +
					'</tr>';
			}
			$('#show_report').html(html);
		}

	});
});

</script>
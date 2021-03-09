// mengambil base_url pada file view/partial/navbar.php
const base_url = $('#base_url').data('url');

function get_swal_notif(output) {
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

$('#logout').on('click', function (e) {
	e.preventDefault();
	const url = $('#logout').attr('href');

	Swal.fire({
		type: 'warning',
		title: 'Keluar dari sistem?',
		showCancelButton: true,
		confirmButtonColor: '#d33',
		confirmButtonText: 'Keluar',
		cancelButtonText: 'Batal',
	}).then((result) => {
		if (result.value) {
			document.location.href = url;
		}
	})
});



$('#edit-profile').validate({
	rules: {
		username: {
			required: true
		},
		name: {
			required: true
		},
	},
	messages: {
		username: {
			required: "harus di isi"
		},
		name: {
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
		const href = $('#edit-profile').data('url');
		$.ajax({
			type: 'POST',
			url: href,
			data: $('#edit-profile').serialize(),
			dataType: 'json',
			success: function (output) {

				if (output.error == true) {
					Swal.fire({
						type: 'error',
						position: 'top',
						title: 'Gagal',
						showConfirmButton: false,
						timer: 1000,
					})
				} else if (output.error == false) {
					console.log("success");
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
				}
			}
		});
	}
});

$('#change-password').validate({
	rules: {
		oldpass: {
			required: true,
			minlength: 6
		},
		newpass: {
			required: true,
			minlength: 6
		},
	},
	messages: {
		oldpass: {
			required: "harus di isi",
			minlength: "Minimal password 6 karakter"
		},
		newpass: {
			required: "harus di isi",
			minlength: "Minimal password 6 karakter"
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
		const href = $('#change-password').data('url');
		$.ajax({
			type: 'POST',
			url: href,
			data: $('#change-password').serialize(),
			dataType: 'json',
			success: function (output) {

				if (output.error == true) {
					Swal.fire({
						type: 'error',
						position: 'top',
						title: output.message,
						showConfirmButton: false,
						timer: 1200,
					})
				} else if (output.error == false) {
					console.log("success");
					Swal.fire({
						type: 'success',
						position: 'top',
						title: output.message,
						showConfirmButton: false,
						timer: 1000,
					})
					window.setTimeout(function () {
						location.reload();
					}, 1100);
				}
			}
		});
	}
});

// Cart

$('#add_to_cart').on('click', function () {

	if ($('#kode_barang').val() == "") {
		Swal.fire({
			type: 'error',
			position: 'top',
			title: 'Pilih data barang',
			showConfirmButton: false,
			timer: 1050,
		})
	} else if ($('#jumlah').val() == "") {
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
		const Qty = $('#jumlah').val();
		const Harga = $('#harga_barang').val();
		const Satuan = $('#satuan').val();
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
			url: base_url + 'transaksi/cart/add',
			data: {
				Kode: Kode,
				Barcode: Barcode,
				Name: Name,
				Qty: Qty,
				Harga: Harga,
				Ppn: Ppn,
				Satuan: Satuan
			},
			success: function (data) {
				empty();
				$('#show_cart').html(data);
			}
		});
	}
});
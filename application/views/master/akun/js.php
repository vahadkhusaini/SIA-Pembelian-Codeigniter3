<script>

// Master Kelompok Akun
$('#tambah-akun').validate({
	rules: {
		id_akun: {
			required: true
		},
		nama_akun: {
			required: true
		},
	},
	messages: {
		id_akun: {
			required: "harus di isi"
		},
		nama_akun: {
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
		const href = $('#tambah-akun').data('url');
		$.ajax({
			type: 'POST',
			url: href,
			data: $('#tambah-akun').serialize(),
			dataType: 'json',
			success: function (output) {
				get_swal_notif(output);
			}
		});
	}
});

$('.edit-akun').on('click', function () {
	const Id = $(this).data('id');

	$.ajax({
		type: 'POST',
		url: '<?= base_url('master/akun/get_akun_byId') ?>',
		data: {
			Id: Id
		},
		dataType: 'json',
		success: function (akun) {
			$("input[name='id_akun']").val(akun.id_akun);
			$("input[name='nama_akun']").val(akun.nama_akun);

		}
	});
})

$('#edit-akun').validate({
	rules: {
		id_akun: {
			required: true
		},
		nama_akun: {
			required: true
		},
	},
	messages: {
		id_akun: {
			required: "harus di isi"
		},
		nama_akun: {
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
		const href = $('#edit-akun').data('url');
		$.ajax({
			type: 'POST',
			url: href,
			data: $('#edit-akun').serialize(),
			dataType: 'json',
			success: function (output) {
				get_swal_notif(output);
			}
		});
	}
});

$('.delete-akun').on('click', function () {
	const Id = $(this).data('id');
	const Name = $(this).data('nama');
	console.log(Id, Name);
	Swal.fire({
		type: 'warning',
		title: 'Hapus Kelompok Akun?',
		text: Name + ' akan dihapus',
		showCancelButton: true,
		confirmButtonColor: '#d33',
		confirmButtonText: 'Hapus',
		cancelButtonText: 'Batal',
		preConfirm: (pre) => {
			console.log("TRUE");
			$.ajax({
				type: 'POST',
				url: '<?= base_url('master/akun/hapus') ?>',
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
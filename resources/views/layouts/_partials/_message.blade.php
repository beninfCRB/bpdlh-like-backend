@if (Session::has('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('message') }}',
        showConfirmButton: false,
        timer: 1500
    });
</script>
@endif
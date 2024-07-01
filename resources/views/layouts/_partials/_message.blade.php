@if (Session::has('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('message') }}',
        showConfirmButton: true,
        timer: 1500
    });
</script>
@endif

@if (Session::has('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Error!',
        text: '{{ session('message') }}',
        showConfirmButton: true,
        timer: 1500
    });
</script>
@endif
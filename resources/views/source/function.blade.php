<script>
    function verify(el) {
        console.log($this.data('url'));
        Swal.fire({
            title: 'Are you sure?',
            icon: 'warning',
            text: "Are you sure you want to verify this ?",
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: '{{ __('app.cancel') }}',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, verify it!'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    icon: 'success',
                    title: 'Verify',
                    showConfirmButton: false,
                    timer: 1500
                });
                setTimeout(() => {
                    $(el).prev('form').submit()
                }, 1600);
            }
        });
    }
</script>

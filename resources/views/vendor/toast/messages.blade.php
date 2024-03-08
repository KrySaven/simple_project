<script src="{{ asset('plugins/toastr/js/toastr.js') }}"></script>
<script>
    $( document ).ready(function() {
        @if(Session::has('message'))
        var type = "{{ Session::get('alert-type', 'info') }}";
      // alert(type);
        switch(type){
            case 'info':
                toastr.info("{{ Session::get('message') }}");
                break;
            
            case 'warning':
                toastr.warning("{{ Session::get('message') }}");
                break;

            case 'success':
                toastr.success("{{ Session::get('message') }}");
                break;

            case 'error':
                toastr.error("{{ Session::get('message') }}");
                break;
        }
    @endif
    });
    
</script>
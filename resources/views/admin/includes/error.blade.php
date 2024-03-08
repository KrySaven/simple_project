@if(count($errors) > 0)
  
        @foreach($errors->all() as $error)
          <div class="alert alert alert-danger alert-dismissible" role="alert" style="padding: 5px 32px 5px 10px; margin-bottom: 5px;">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
            {{ $error }}
        </div>
        @endforeach
    
@endif
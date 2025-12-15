@props(['method' => 'POST', 'action' => '', 'enctype' => 'multipart/form-data'])

<form method="{{ $method === 'GET' ? 'GET' : 'POST' }}" 
      action="{{ $action }}" 
      enctype="{{ $enctype }}"
      {{ $attributes }}>
    
    @if($method !== 'GET')
        @csrf
    @endif
    
    @if(in_array(strtoupper($method), ['PUT', 'PATCH', 'DELETE']))
        @method($method)
    @endif
    
    {{ $slot }}
</form>
























 
{{-- Information --}}
@include('iprofile::frontend.livewire.partials.infor-map')

{{-- Autocomplete --}}
@include('iprofile::frontend.livewire.partials.autocomplete-google',['inputId' => 'paymentAddress1','inputVarName' => 'address_1' ,'insideModal' => $insideModal])

{{-- Error message --}}
{!! $errors->first("address.country", trans("iprofile::addresses.validation.marker.required")) !!}

{{-- Map --}}
<x-isite::maps 
    :usingLivewire="true" 
    :allowMoveMarker="true" 
    :showLocationName="false" 
    zoom="13"
    inputVarName='address_1'/>
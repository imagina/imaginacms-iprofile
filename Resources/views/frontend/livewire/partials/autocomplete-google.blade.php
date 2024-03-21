<script>
    
    console.log("Iprofile::Livewire|Partials|AutocompleteGoogle|Init")

    //Global Variables
    var inputId = "{{$inputId}}"
    var inputVarName = "{{$inputVarName}}"
    var autocomplete;

    /*
    * INIT AUTOCOMPLETE
    */
    autocomplete = new google.maps.places.Autocomplete((document.getElementById(inputId)), {
        types: ['geocode']
    });

    /*
    * Get Data Especific
    */
    function getDataFromAddress(addressComponents)
    {
        
        //console.warn(addressComponents)

        var components = {};
        var city;
        var postalCode = null;
        var state;
        var country;
      

        jQuery.each(addressComponents, function(k,v1) {
            jQuery.each(v1.types,function(k2,v2){
                components[v2] = v1.short_name
            });
        });
                
        if(components.locality) { city = components.locality; }

        if(!city) { city = components.administrative_area_level_1; }

        if(components.postal_code) { postalCode = components.postal_code; }

        if(components.administrative_area_level_1) { state = components.administrative_area_level_1; }
        
        if(components.country) { country = components.country; }

        //console.warn("COUNTRY: "+country+" | STATE: "+state+" | CITY: "+city+" | Postal: "+postalCode)

        //Data Final
        var addressData = {country: country, state: state, city: city, postal: postalCode};

        return addressData;
    }

    
    /**
    * Event Place Changed | When select an address in google select
    */
    google.maps.event.addListener(autocomplete, 'place_changed', function () {

        var nearPlace = autocomplete.getPlace();

        //Obtener datos del address separados
        var addressData = getDataFromAddress(nearPlace.address_components)
        //Agrupando lat y lng
        var newPositionFromPlace = {lat: nearPlace.geometry.location.lat(), lng: nearPlace.geometry.location.lng()};
        //Toco enviarle tambien el valor del input, xq cuando se va escribiendo en frontend, y se selecciona. No guardaba lo de google
        var inputValue = document.getElementById(inputId).value

        //Data Final
        var dataToSend = {inputValue: inputValue, inputVarName: inputVarName, newPosition: newPositionFromPlace, addressData:addressData};

        //Emit to send data
        window.livewire.emit('updateMarkerInMap',dataToSend);

       
    });

</script>

@if($insideModal)
    @include('iprofile::frontend.livewire.partials.style-select-map-modal')
@endif
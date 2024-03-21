<div class="address-list-{{$type}}">
  <div class="form-group">
<label for="selectBillingAddress">  {{trans('iprofile::addresses.title.myAddresses')}} </label>
<select
        class="form-control"
        wire:model.lazy="addressSelectedId"
        id="selectAddressList{{$type}}">
  <option value="">{{trans('icommerce::billing_details.address.select_direction')}}</option>

  @foreach($addresses as $address)
    <option value="{{$address->id}}">{{$address->first_name}} {{ $address->last_name }}
      - {{  $address->address_1 }}</option>
  @endforeach
</select>
{!! $errors->first("billingAddress", '<span class="help-block">:message</span>') !!}
  </div>
  @if(isset($addressSelected->id))
<x-iprofile::address-card-item key="addressList{{$type}}" :address="$addressSelected"/>
    @endif
</div>
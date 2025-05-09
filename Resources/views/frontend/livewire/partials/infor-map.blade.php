<div class="infor-map-address-delivery text-small my-2">
    <strong class="text-uppercase text-danger">{{trans('iprofile::addresses.title.important')}}:</strong>

    <ul class="mb-0">
        <li>
          <small>
            {{trans('iprofile::addresses.messages.select address in searcher')}}
            <i class="fa-solid fa-location-dot fa-xl text-danger"></i>
          </small>
        </li>
        <li>
          <small>
            {{trans('iprofile::addresses.messages.not address in searcher')}}
            <i class="fa-solid fa-location-dot fa-xl text-danger"></i>
          </small>
        </li>
    </ul>

</div>

<style>
    .infor-map-address-delivery ul li{
        line-height: 1.1;
    }
</style>

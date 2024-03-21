<?php

namespace Modules\Iprofile\Http\Livewire;

use Livewire\Component;
use Modules\Iprofile\Entities\Address;

class AddressForm extends Component
{


  public $embedded;
  public $route;
  public $type;
  public $countries;
  public $provinces;
  public $cities;
  public $address;
  public $user;
  public $openInModal;
  public $withButtonSubmit;
  public $livewireEvent;
  public $shopAsGuest;
  public $addressGuest;
  public $log;
  public $showAddressmap;
  public $lat;
  public $lng;
  public $insideModal;
  public $showCancelBtn;
  public $userAddresses;
  public $hideLastName;

  protected $addressesExtraFields;
  protected $rules;
  protected $listeners = ['addressEmit','updateMarkerInMap','updateDataFromExternal'];

  /**
   * Create a new component instance.
   *
   * @return void
   */
  public function mount($embedded = false, $route = false, $type = null, $openInModal = false,
                        $withButtonSubmit = true, $livewireEvent = null, $addressGuest = [], $insideModal = false, $showCancelBtn = false,$userAddresses = null)
  {
    $this->log = "Iprofile::Livewire|AddressForm|";
    $this->embedded = $embedded;
    $this->openInModal = $openInModal;
    $this->route = $route;
    $this->type = $type;
    $this->withButtonSubmit = $withButtonSubmit;
    $this->livewireEvent = $livewireEvent;
    $this->addressGuest = $addressGuest;
    $this->shopAsGuest = false;
    $this->addressesExtraFields = json_decode(setting('iprofile::userAddressesExtraFields', null, "[]"));
    $this->hideLastName = setting('iprofile::hideLastNameInAddress', null, true);

    $this->initUser();
    $this->initAddress();
    $this->initCountries();
    $this->initProvinces();
    $this->initCities();

    $this->showAddressmap = (setting('iprofile::addressAutocomplete') && setting('isite::api-maps')) ? true : false;
    $this->insideModal = $insideModal;
    $this->showCancelBtn = $showCancelBtn;
    $this->userAddresses = $userAddresses;


  }

  public function addressEmit()
  {
    \Log::info($this->log.'addressEmit');

    try {
      $this->validate($this->setRules(), $this->setMessages());
      $this->initCities();

      //validate if the address doesnt have a custom City to get the city name from the DB
      $this->validateCity();

      $this->emit($this->livewireEvent, $this->address);
    }catch (\Illuminate\Validation\ValidationException $e) {
      // Do your thing and use $validator here
      $validator = $e->validator;

            $this->alert('warning', trans('iprofile::addresses.validation.alerts.invalid_data'), config('asgard.isite.config.livewireAlerts'));

            // Once you're done, re-throw the exception
            throw $e;
        }
    }

    public function updated($name, $value)
    {
        switch ($name) {
            case 'address.country':
                if (! empty($value)) {
                    $this->address['country_id'] = $this->countries->where('iso_2', $value)->first()->id;

                    $this->initProvinces();
                }
                break;

            case 'address.state':
                if (! empty($value)) {
                    $this->address['state_id'] = $this->provinces->where('iso_2', $value)->first()->id;
                    $this->initCities();
                }
                break;
        }
    }

    public function save()
    {
        try {
            $this->validate($this->setRules(), $this->setMessages());
            $this->address['user_id'] = \Auth::user()->id ?? null;

            //validate if the address doesnt have a custom City to get the city name from the DB
            $this->validateCity();

            $address = $this->addressRepository()->create($this->address);
            $this->emit('addressAdded', $address);
            $this->initAddress();
            $this->alert('success', trans('iprofile::addresses.messages.created'), config('asgard.isite.config.livewireAlerts'));
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Do your thing and use $validator here
            $validator = $e->validator;

            $this->alert('warning', trans('iprofile::addresses.validation.alerts.invalid_data'), config('asgard.isite.config.livewireAlerts'));

            // Once you're done, re-throw the exception
            throw $e;
        }
    }

private function validateCity(){

  if(!$this->showAddressmap){
    //validate if the address doesnt have a custom City to get the city name from the DB
    if (! isset($this->address['options']['customCity']) || ! $this->address['options']['customCity']) {
        $city = $this->cities->where('id', $this->address['city_id'])->first();
        $this->address['city'] = $city->name ?? '';
        $this->address['zip_code'] = $city->code ?? '';
    } else {
        $this->address['city_id'] = null;
    }
  }
}

    private function initUser()
    {
        $this->user = \Auth::user();
    }

    public function hydrate()
    {
        $this->rules = $this->setRules();
    }

  /**
   *
   */
  private function setRules()
  {
    $this->addressesExtraFields = json_decode(setting('iprofile::userAddressesExtraFields', null, "[]"));
    $extraFieldRules = [];
    if(is_array($this->addressesExtraFields)){
      foreach ($this->addressesExtraFields as $extraField) {
        $rule = "";
        switch ($extraField->type) {
          case 'number':
            $rule = "numeric";
            break;
          case 'text':
            $rule = "string";
            break;
          case 'documentType':
            $rule = "string";
            $extraFieldRules = array_merge($extraFieldRules, ["address.options.documentNumber" => $rule . ($extraField->required ? "|required|min:6|max:10" : "")]);

            break;
          case 'textarea':
            $rule = "string|max:180";
            break;
        }
        if ($extraField->required) {
          $rule .= "|required";
        }
        if ($extraField->active) {
          $extraFieldRules = array_merge($extraFieldRules, ["address.options." . $extraField->field => $rule]);
        }
      }
    }

    //custom validation for City: could be city from DB or custom city from the user
    if(isset($this->address["options"]["customCity"]) && $this->address["options"]["customCity"])
      $cityRule = [
        'address.city' => 'required|string',
        'address.zip_code' => 'required|string',
      ];
    else
      $cityRule = ['address.city_id' => 'required|integer'];

    //Validation LastName Optional
    if(!$this->hideLastName){
      $lastNameRule = ['address.last_name' => 'string|min:3'];
    }else{
      $lastNameRule = [];
    }

    return array_merge([
      'address.first_name' => 'required|string|min:3',
      'address.country' => 'required|string',
      'address.telephone' => 'required|min:5|max:10',
      'address.state' => 'required|string',
      'address.default' => 'boolean',
      'address.address_1' => 'required|string|min:10',
      'address.type' => 'string'], $extraFieldRules, $cityRule, $lastNameRule);

  }

    private function setMessages()
    {
        return [
            'address.options.documentNumber.min' => trans('iprofile::addresses.validation.documentNumber.min'),
            'address.options.documentNumber.max' => trans('iprofile::addresses.validation.documentNumber.max'),
            'address.first_name.min' => trans('iprofile::addresses.validation.first_name.min'),
            'address.last_name.min' => trans('iprofile::addresses.validation.last_name.min'),
            'address.address_1.min' => trans('iprofile::addresses.validation.address_1.min'),
            'address.telephone.min' => trans('iprofile::addresses.validation.telephone.min'),
            'address.telephone.max' => trans('iprofile::addresses.validation.telephone.max'),
            'address.first_name.required' => trans('iprofile::addresses.validation.required'),
            'address.last_name.required' => trans('iprofile::addresses.validation.required'),
            'address.address_1.required' => trans('iprofile::addresses.validation.required'),
            'address.country.required' => trans('iprofile::addresses.validation.required'),
            'address.city_id.required' => trans('iprofile::addresses.validation.required'),
            'address.city.required' => trans('iprofile::addresses.validation.required'),
            'address.zip_code.required' => trans('iprofile::addresses.validation.required'),
            'address.state.required' => trans('iprofile::addresses.validation.required'),
            'address.telephone.required' => trans('iprofile::addresses.validation.required'),
            'address.options.documentNumber.required' => trans('iprofile::addresses.validation.required'),
            'address.options.documentType.required' => trans('iprofile::addresses.validation.required'),
        ];
    }

  private function initAddress()
  {
    $this->addressesExtraFields = json_decode(setting('iprofile::userAddressesExtraFields', null, "[]"));
    $options = [];
    foreach ($this->addressesExtraFields as $extraField) {
      if ($extraField->active ?? false) {
        if ($extraField->field == "documentType") {
          $options["documentNumber"] = "";
        }
        $options[$extraField->field] = "";
      }
    }
    $options["extraInfo"] = "";
    $options["customCity"] = false;

    //Validation User Logged | Get Basi Information
    if(!is_null($this->user)){
      //Don't show LastName
      if($this->hideLastName){
         $userFirstName = $this->user->present()->fullname;
         $userLastName = "";
      }else{
        //Show lastname
        $userFirstName = $this->user->first_name;
        $userLastName = $this->user->last_name;
      }
    }

    $this->address = [
      'first_name' => $this->addressGuest['first_name'] ?? $userFirstName ?? "",
      'last_name' => $this->addressGuest['last_name'] ?? $userLastName ?? "",
      'address_1' => $this->addressGuest['address_1'] ?? "",
      'telephone' => $this->addressGuest['telephone'] ?? "",
      'country' => $this->addressGuest['country'] ?? "",
      'country_id' => $this->addressGuest['country_id'] ?? "",
      'state_id' => $this->addressGuest['state_id'] ?? "",
      'state' => $this->addressGuest['state'] ?? "",
      'city' => $this->addressGuest['city'] ?? "",
      'city_id' => $this->addressGuest['city_id'] ?? "",
      'zip_code' => $this->addressGuest['zip_code'] ?? "",
      'default' => $this->addressGuest['default'] ?? false,
      'user_id' => $this->addressGuest['user_id'] ?? $this->user->id ?? null,
      'type' => $this->addressGuest['type'] ?? $this->type,
      'options' => $this->addressGuest['options'] ?? $options
    ];
  }

    private function initCountries()
    {
        $params = [
            'filter' => ['order' => ['way' => 'asc', 'field' => 'name']],
        ];
        $this->countries = $this->countryRepository()->getItemsBy(json_decode(json_encode($params)));
        if ($this->countries->count() == 1) {
            $this->address['country'] = $this->countries->first()->iso_2;
            $this->address['country_id'] = $this->countries->first()->id;
        }
    }

    private function initProvinces()
    {
        if (isset($this->address['country_id'])) {
            $params = ['filter' => ['countryId' => $this->address['country_id'] ?? null, 'order' => ['way' => 'asc', 'field' => 'name']]];
            $this->provinces = $this->provinceRepository()->getItemsBy(json_decode(json_encode($params)));
        } else {
            $this->provinces = collect([]);
        }
    }

    private function initCities()
    {
        if (isset($this->address['state_id'])) {
            $params = ['filter' => ['provinceId' => $this->address['state_id'] ?? null, 'order' => ['way' => 'asc', 'field' => 'name']]];
            $this->cities = $this->cityRepository()->getItemsBy(json_decode(json_encode($params)));
        } else {
            $this->cities = collect([]);
        }
    }

    private function countryRepository()
    {
        return app('Modules\Ilocations\Repositories\CountryRepository');
    }

    private function provinceRepository()
    {
        return app('Modules\Ilocations\Repositories\ProvinceRepository');
    }

    private function cityRepository()
    {
        return app('Modules\Ilocations\Repositories\CityRepository');
    }

    private function addressRepository()
    {
        return app('Modules\Iprofile\Repositories\AddressRepository');
    }

  /**
   *  Listener | Update Marker in Map
   */
  public function updateMarkerInMap($params)
  {
    //\Log::info($this->log.'selectedMarkerInMap|params: '.json_encode($params));

    //Save new data
    $this->updateDataFromExternal($params);

    //Emit to update marker in map
    $this->dispatchBrowserEvent('google-update-marker-in-map',[
      'itemPosition' => $params['newPosition'],
      'inputVarName' => $params['inputVarName']
    ]);

  }

  /**
   *  Listener | Update address, lng, lat from map
   */
  public function updateDataFromExternal($params)
  {

    //\Log::info($this->log.'updateDataFromExternal|params: '.json_encode($params));

    //Save New Positions
    $this->address['lat'] = $params['newPosition']['lat'];
    $this->address['lng'] = $params['newPosition']['lng'];

    //Save new address in variable
    //\Log::info($this->log.'updateDataFromExternal|Input Value: '.$params['inputValue']);
    $this->address[$params['inputVarName']] = $params['inputValue'];

    //Search Required data in Ilocations
    $requiredData = $this->getDataFromIlocations($params['addressData']);

    //Save Data in variables
    $this->address["country"] = $requiredData['country']['name'];
    $this->address['country_id'] = $requiredData['country']['id'] ?? "";
    $this->address["state"] = $requiredData['province']['name'];
    $this->address['state_id'] = $requiredData['province']['id'] ?? "";
    $this->address["city"] = $requiredData['city']['name'];
    $this->address['city_id'] = $requiredData['city']['id'] ?? "";

  }

  /*
  * Get data from Ilocations to Country,Province and City
  */
  public function getDataFromIlocations($addresData)
  {

    //\Log::info($this->log.'updateDataFromExternal|params: '.json_encode($addresData);
    $country = [];
    $province = [];
    $city = [];

    //Search Country
    $searchParams['filter']['field'] = "iso_2";
    $searchParams['include'] = [];
    $criteria = $addresData['country'];

    $country = $this->countryRepository()->getItem($criteria,json_decode(json_encode($searchParams)));
    if(!is_null($country)){
      $country = ['id' => $country->id,'name' => $country->name];
    }else{
      $country = ['name' => $addresData['country']];
    }

    //Search Province
    $searchParams['filter']['search'] = $addresData['state'];
    $provinces = $this->provinceRepository()->getItemsBy(json_decode(json_encode($searchParams)));
    if(count($provinces)>0){
      $province = ['id' => $provinces[0]->id,'name' => $provinces[0]->name];
    }else{
      $province = ['name' => $addresData['state']];
    }

    //Search City
    $searchParams['filter']['search'] = $addresData['city'];
    $cities = $this->cityRepository()->getItemsBy(json_decode(json_encode($searchParams)));
    if(count($cities)>0){
      $city = ['id' => $cities[0]->id,'name' => $cities[0]->name];
    }else{
      $city = ['name' => $addresData['city']];
    }

    //Final Result
    $result = ['country' => $country, 'province' => $province,'city' => $city];


    return $result;
  }


  /**
   * Get the view / contents that represent the component.
   *
   * @return \Illuminate\Contracts\View\View|string
   */
  public function render()
  {
    return view("iprofile::frontend.livewire.address-form", [
      "addressesExtraFields" => $this->addressesExtraFields
    ]);
  }
}
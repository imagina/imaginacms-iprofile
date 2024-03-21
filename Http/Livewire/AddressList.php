<?php

namespace Modules\Iprofile\Http\Livewire;

use Livewire\Component;
use Modules\Iprofile\Entities\Address;

class AddressList extends Component
{


  public $addresses;
  public $addressSelected;
  public $addressSelectedId;
  public $type;

  /**
   * Create a new component instance.
   *
   * @return void
   */
  public function mount($addresses = [], $addressSelected = null, $type = "", $emit = "")
  {
    $this->addresses = $addresses;
    $this->addressSelected = $addressSelected;
    $this->type = $type;
    $this->emit = $emit;
    $this->initUser();
    $this->initAddress();

  }

  public function addressEmit()
  {
    try {
      $this->validate($this->setRules(), $this->setMessages());
      $this->initCities();
      
      //validate if the address doesnt have a custom City to get the city name from the DB
      $this->validateCity();
      
      $this->emit($this->livewireEvent, $this->address);
    }catch (\Illuminate\Validation\ValidationException $e) {
      // Do your thing and use $validator here
      $validator = $e->validator;

      $this->alert('warning', trans("iprofile::addresses.validation.alerts.invalid_data"), config("asgard.isite.config.livewireAlerts"));

      // Once you're done, re-throw the exception
      throw $e;
    }
  }

  public function updated($name, $value)
  {
    $this->emit($this->emit, $this->addressSelectedId);
    $this->addressSelected = $this->addresses->where("id",$this->addressSelectedId)->first();

  }
  

  /**
   *
   */
  private function initUser()
  {
    $this->user = \Auth::user();
  }

 


  private function initAddress()
  {
   
    if(empty($this->addresses) && isset($this->user->id)){
  
      $this->addresses = $this->user->addresses;
      
    }
    
      if(is_int($this->addressSelected)){
        
        $this->addressSelected = $this->addressRepository()->getItem($this->addressSelected);
        
      }
      /*
      if(is_null($this->addressSelected) && $this->addresses->isNotEmpty()){
  
        $this->addressSelected = $this->addresses->first();
      }
  */
      if(isset($this->addressSelected->id)){
  
        $this->addressSelectedId = $this->addressSelected->id;
        $this->emit($this->emit, $this->addressSelectedId);
      }
    
  }


  private function addressRepository()
  {
    return app('Modules\Iprofile\Repositories\AddressRepository');
  }

  /**
   * Get the view / contents that represent the component.
   *
   * @return \Illuminate\Contracts\View\View|string
   */
  public function render()
  {
    return view("iprofile::frontend.livewire.address-list");
  }
}
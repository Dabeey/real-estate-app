<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Property;


class ContactForm extends Component
{
    public Property $property;

    public $name='';
    public $email='';
    public $phone='';
    public $message='';
    public $showSuccess=false;

    // Define rules for validation so that a user cannot send an empty $email
    protected $rules = [
        'name' => 'required|min:2|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'nullable|max:20',
        'message' => 'required|min:10|max:1000',

    ];

    public function mount(Property $property): void{
        $this->property = $property;
        $this->message = "Hi, I'm interested in the property '{
        '{$property->title}' listed at {$property->formatted_price}. Could you "
    };

    public function render()
    {
        return view('livewire.contact-form');
    }
}

<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;

class ClientForm extends Component
{
    use WithFileUploads;

    public $first_name, $last_name, $address, $city, $county, $postal_code, $country = 'United Kingdom', $phone, $email, $DOB, $client_type;
    public $passport = 0, $permanent = 0;
    public $members = [];
    public $passportDetails = [
        'passport_no' => '',
        'passport_expiry_date' => '',
        'passport_place' => '',
        'passport_issue_date' => '',
        'passport_front' => null,
        'passport_back' => null,
        'letter' => null,
    ];
    public $permanentDetails = [
        'currency' => '£',
        'credit_limit' => '',
    ];

    public function addMember()
    {
        $this->members[] = [
            'passport_issue_date' => '',
            'passport_expiry_date' => '',
            'member_name' => '',
            'member_DOB' => '',
            'member_passport_no' => '',
            'member_passport_place' => '',
            'member_passport_front' => null,
            'member_passport_back' => null,
        ];
    }

    public function removeMember($index)
    {
        unset($this->members[$index]);
        $this->members = array_values($this->members);
    }

    public function render()
    {
        return view('livewire.client-form');
    }

    public function save()
    {
        dd($this->members);
        // Validation and saving logic here
    }
}

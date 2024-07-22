<div x-data="{ passport: @entangle('passport'), permanent: @entangle('permanent') }" x-init="initializeAddressLookup()">
    <form wire:submit.prevent="save">
        @csrf
        <div class="box box-primary" id="action">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="first_name">First Name</label>
                            <input type="text" wire:model="first_name" required class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="last_name">Last Name</label>
                            <input type="text" wire:model="last_name" required class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-11">
                            <div class="form-group">
                                <center>
                                    <div id="postcode_lookup" style="border:2px solid green;">
                                        <input type="text" id="getaddress_input" value="Enter your Postcode" style="color:#CBCBCB;" name="getaddress_input" autocomplete="off">
                                        <button id="getaddress_button" type="button" onclick="return false;">Find your Address</button>
                                    </div>
                                </center>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="address">Street</label>
                            <input type="text" id="line1" wire:model="address" required class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="city">City</label>
                            <input id="town" type="text" wire:model="city" required class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="county">County</label>
                            <input id="county" type="text" wire:model="county" required class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="postal_code">Postal Code</label>
                            <input id="postal_code" type="text" wire:model="postal_code" required class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="country">Country</label>
                            <input id="country" type="text" wire:model="country" required class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="text" wire:model="phone" required class="form-control" maxlength="12">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" wire:model="email" required class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="DOB">DOB</label>
                            <input type="date" wire:model="DOB" required class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="client_type">Client Type:</label>
                            <select wire:model="client_type" class="form-control">
                                <option value="">--Select--</option>
                                <option value="Corporate">Corporate</option>
                                <option value="Individual">Individual</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div id="family-member">
                    @foreach($members as $index => $member)
                        <div class="hatao" wire:key="member-{{ $index }}">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="passport_issue_date[]">Passport Issue Date</label>
                                        <input type="date" wire:model="members.{{ $index }}.passport_issue_date" required class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="passport_expiry_date[]">Passport Expire Date</label>
                                        <input type="date" wire:model="members.{{ $index }}.passport_expiry_date" required class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="member_name[]">Member Name</label>
                                        <input type="text" wire:model="members.{{ $index }}.member_name" required class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="member_DOB[]">Member DOB</label>
                                        <input type="date" wire:model="members.{{ $index }}.member_DOB" required class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="member_passport_no[]">Member Passport NO.</label>
                                        <input type="text" wire:model="members.{{ $index }}.member_passport_no" required class="form-control" maxlength="10">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="member_passport_place">Place of Issue</label>
                                        <input type="text" wire:model="members.{{ $index }}.member_passport_place" required class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="member_passport_front">Passport Front Page:</label>
                                        <input type="file" wire:model="members.{{ $index }}.member_passport_front" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="member_passport_back">Passport Back Page:</label>
                                        <input type="file" wire:model="members.{{ $index }}.member_passport_back" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div align="right">
                                <input type="button" class="btn btn-danger btn-xs" value="Remove" wire:click="removeMember({{ $index }})">
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="text-center" style="margin-top: 5px">
                    <button class="btn btn-sm btn-primary" type="button" wire:click="addMember">Add Member</button>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="passport">Do you have passport</label>
                            <input type="radio" name="passport" x-model="passport" value="1">Yes
                            <input type="radio" name="passport" x-model="passport" value="0">No
                        </div>
                    </div>
                </div>
                <div id="passport" x-show="passport">
                    <hr>
                    <div class="text-center">
                        <h3>Passport Details</h3>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="passport_no">Passport Number</label>
                                <input type="text" wire:model="passportDetails.passport_no" required class="form-control" maxlength="10">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="passport_expiry_date">Passport Expire date</label>
                                <input type="date" wire:model="passportDetails.passport_expiry_date" required class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="passport_place">Place of Issue</label>
                                <input type="text" wire:model="passportDetails.passport_place" required class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="passport_issue_date">Date Of Issue</label>
                                <input type="date" wire:model="passportDetails.passport_issue_date" required class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="passport_front">Passport Front:</label>
                                <input type="file" wire:model="passportDetails.passport_front" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="passport_back">Passport Back:</label>
                                <input type="file" wire:model="passportDetails.passport_back" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="passport_front">Permission Letter:</label>
                                <input type="file" wire:model="passportDetails.letter" class="form-control">
                            </div>
                        </div>
                    </div>
                    <hr>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="passport">Do you want to make this client permanent?</label>
                            <input type="radio" name="permanent" x-model="permanent" value="1">Yes
                            <input type="radio" name="permanent" x-model="permanent" value="0">No
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div id="permanent" x-show="permanent">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="currency">Currency</label>
                                        <select wire:model="permanentDetails.currency" class="form-control" id="currency">
                                            <option value="$">$</option>
                                            <option value="£" selected>£</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="credit_limit">Credit Limit</label>
                                        <input type="text" wire:model="permanentDetails.credit_limit" required class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="text-center">
                <button class="btn btn-success" type="submit">Add client</button>
            </div>
        </div>
    </form>
</div>

<script src="https://getaddress-cdn.azureedge.net/scripts/jquery.getAddress-3.0.1.min.js"></script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('initializeAddressLookup', () => ({
            initializeAddressLookup() {
                $('#postcode_lookup').getAddress({
                    api_key: 'uz1Ks6ukRke3TO_XZBrjeA22850',
                    output_fields: {
                        line_1: '#line1',
                        line_2: '#line2',
                        line_3: '#line3',
                        post_town: '#town',
                        county: '#county',
                        postcode: '#postal_code'
                    }
                });
            }
        }))
    })
</script>

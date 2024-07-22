<div class="card">
    <div class="card-header bg-primary text-white">
        <span class="card-title mb-0 text-white font-bold">Flight Search</span>
    </div>
    <div class="card-body">
        <form action="">
            <div class="row">
                <div class="col-md-1">
                    <label for="tripType" class="font-bold">Trip Type</label>
                    <div class="form-check" id="tripType">
                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
                        <label class="form-check-label" for="flexRadioDefault1">
                            One Way
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2" checked>
                        <label class="form-check-label" for="flexRadioDefault2">
                            Round Trip
                        </label>
                    </div>
                </div>
                <div class="col-md-3" x-data="{ openOrigin: false }">
                    <label for="origin" class="font-bold">Origin</label>
                    <input type="text" class="form-control" id="origin" placeholder="Origin" wire:model.debounce.300ms="originQuery" @focus="openOrigin = true" @blur="setTimeout(() => openOrigin = false, 200)">
                    <div x-show="openOrigin" class="absolute bg-white z-10 w-full border mt-2">
                        <ul>
                            @forelse ($originAirports as $airport)
                                <li wire:click="setOrigin('{{ $airport['iata'] }}')" class="cursor-pointer p-2 hover:bg-gray-200">
                                    {{ $airport['name'] }} ({{ $airport['iata'] }}) - {{ $airport['city'] }}, {{ $airport['country'] }}
                                </li>
                            @empty
                                <li class="p-2">No results found</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                <div class="col-md-3" x-data="{ openDestination: false }">
                    <label for="destination" class="font-bold">Destination</label>
                    <input type="text" class="form-control" id="destination" placeholder="Destination" wire:model.debounce.300ms="destinationQuery" @focus="openDestination = true" @blur="setTimeout(() => openDestination = false, 200)">
                    <div x-show="openDestination" class="absolute bg-white z-10 w-full border mt-2">
                        <ul>
                            @forelse ($destinationAirports as $airport)
                                <li wire:click="setDestination('{{ $airport['iata'] }}')" class="cursor-pointer p-2 hover:bg-gray-200">
                                    {{ $airport['name'] }} ({{ $airport['iata'] }}) - {{ $airport['city'] }}, {{ $airport['country'] }}
                                </li>
                            @empty
                                <li class="p-2">No results found</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
                <div class="col-md-5">
                    <label for="departureDate" class="font-bold">Departure Date</label>
                    <input type="date" class="form-control" id="departureDate" placeholder="Departure Date">
                </div>

                <div class="col-md-2">
                    <label for="adults" class="font-bold">Adults</label>
                    <select class="form-select" id="adults">
                        <option selected>1</option>
                        <option>2</option>
                        <option>3</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="children" class="font-bold">Children</label>
                    <select class="form-select" id="children">
                        <option selected>0</option>
                        <option>1</option>
                        <option>2</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="infants" class="font-bold">Infants</label>
                    <select class="form-select" id="infants">
                        <option selected>0</option>
                        <option>1</option>
                        <option>2</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="class" class="font-bold">Class</label>
                    <select class="form-select" id="class">
                        <option selected>Economy</option>
                        <option>Business</option>
                        <option>First</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="airline" class="font-bold">Airline</label>
                    <select class="form-select" id="airline">
                        <option selected>Any</option>
                        <option>Emirates</option>
                        <option>Qatar</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="currency" class="font-bold">Currency</label>
                    <select class="form-select" id="currency">
                        <option>USD</option>
                        <option>EUR</option>
                        <option selected>GBP</option>
                    </select>
                </div>
                <button class="btn btn-primary m-2">Search</button>

            </div>
        </form>
    </div>
</div>

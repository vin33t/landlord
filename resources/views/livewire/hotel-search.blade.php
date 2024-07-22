<div>
    <form wire:submit.prevent="searchHotels" x-data="dateRangePicker()">
        <div class="row">
            <div class="col-md-2">
                <label for="country">Country</label>
                <input class="form-control" type="text" id="country" wire:model="country" placeholder="Country">
            </div>
            <div class="col-md-2">
                <label for="cityIds">City</label>
                <input class="form-control" type="text" id="cityIds" wire:model="cityIds" placeholder="City">
            </div>
            <div class="col-md-3">
                <label for="dateRange">Check-In and Check-Out Dates:</label>
                <input class="form-control" type="text" id="dateRange" x-ref="dateRange" placeholder="Select Date Range">
            </div>
            <div class="col-md-2">
                <label for="nationality">Nationality:</label>
                <input class="form-control" type="text" id="nationality" wire:model="nationality">
            </div>
            <div class="col-md-2">
                <label for="currency">Currency:</label>
                <input class="form-control" type="text" id="currency" wire:model="currency">
            </div>
            <div class="col-md-1">
                <label for="availableOnly">Available Only:</label>
                <input type="checkbox" id="availableOnly" wire:model="availableOnly">
            </div>
            <div class="col-md-12 mt-2">
                <label for="rooms">Rooms:
                    <button type="button" class="btn btn-primary btn-sm" @click="roomCount++"><i class="fa fa-plus"></i></button>
                </label>
                <div class="row m-2">
                    <template x-for="(room, index) in roomCount" :key="index">
                        <div class="col-md-3" style="position: relative; border: 1px solid #ccc; padding: 15px; margin-bottom: 10px;">
                            <button type="button" @click="roomCount--; rooms.splice(index, 1)" style="position: absolute; top: 5px; right: 5px; background: none; border: none; font-size: 20px; line-height: 20px; cursor: pointer;"><i class="fa fa-times-circle"></i></button>

                            <label for="numAdults">Number of Adults:</label>
                            <input class="form-control" type="number" id="numAdults" :wire:model="`rooms.${index}.NumAdults`">

                            <label for="children">Children:</label>
                            <input class="form-control" type="text" id="children" placeholder="Enter ages (comma separated)" :x-model="rooms[index].Children">
                        </div>
                    </template>
                </div>
            </div>


        </div>

        <button type="submit" class="btn btn-primary">Search Hotels</button>
    </form>

    <div>
        <h2>Hotel Results</h2>
        <ul>
            @foreach($hotels as $hotel)
                <li>{{ $hotel['hotel_name'] }} - {{ $hotel['total_price'] }}</li>
            @endforeach
        </ul>
    </div>
</div>

<script>
    function dateRangePicker() {
        return {
            roomCount: 1,
            rooms: @entangle('rooms'),
            init() {
                flatpickr(this.$refs.dateRange, {
                    mode: 'range',
                    minDate: 'today',
                    dateFormat: 'Y-m-d',
                    onClose: (selectedDates, dateStr, instance) => {
                        if (selectedDates.length === 2) {
                            @this.set('checkInDate', selectedDates[0].toISOString().split('T')[0]);
                            @this.set('checkOutDate', selectedDates[1].toISOString().split('T')[0]);
                        }
                    }
                });
            },
            removeRoom(index) {
                this.rooms.splice(index, 1);
                this.roomCount--;
            }
        };
    }
</script>

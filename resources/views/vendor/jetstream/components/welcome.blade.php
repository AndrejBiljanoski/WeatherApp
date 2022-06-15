<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="overflow-hidden shadow-sm sm:rounded-lg">
            <div class="grid grid-cols-4 gap-4">
                @foreach ($cities as $city)
                    <div
                        class="p-6 max-w-sm bg-white rounded-lg border border-gray-200 shadow-md dark:bg-gray-800 dark:border-gray-700">
                        <h1 class="text-3xl">
                            @if($city->getCurrentWeather())
                                {{ $city->getCurrentWeather()->temperature }} &#8451;
                            @else
                                No Data Found
                            @endif
                            <span class="text-xl">
                                @if($city->getCurrentWeather())
                                    @if ($city->getCurrentWeather()->getWeatherTrend()['temperature'] != 0)
                                        {{ abs($city->getCurrentWeather()->getWeatherTrend()['temperature']) }}
                                        @if ($city->getCurrentWeather()->getWeatherTrend()['temperature'] > 0)
                                            &#8593;
                                        @else
                                            &#8595;
                                        @endif
                                    @endif
                                @endif
                            </span>
                        </h1>
                        <span class="font-semibold">{{ $city->name }}</span>
                        <span class="text-xs">
                            ({{ $city->longitude }}, {{ $city->latitude }})
                        </span>
                        <br />
                        <div class="py-2">
                            <div class="capitalize my-1">
                                <span class="bg-neutral-200 px-2">
                                    {{ $city->getCurrentWeather()->weather_description ?? 'N/A'}}
                                </span>
                            </div>
                            @if($city->getCurrentWeather())
                                Updated {{ \Carbon\Carbon::parse($city->getCurrentWeather()->time)->diffForHumans() }}
                            @endif
                        </div>
                        <a href="{{ route('city.show', $city->id) }}">
                            <button
                                class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-sm">
                                More Info
                            </button>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

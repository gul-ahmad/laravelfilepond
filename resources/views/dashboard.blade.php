<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <label class="fw-bolder black">ID CARD </label>

                    @if ($userDetails->cnicImage)
                        <p><img src="{{ asset('storage/cnicfinal/' . $userDetails->cnicImage->path) }}" class="img-fluid">
                        </p>
                    @else
                        <p>No Image Found
                        </p>
                    @endif
                </div>
                <div class="p-6 text-gray-900 m-4>
                    <label class="fw-bolder black">UserImages
                    </label>

                    @if ($userDetails->selfImages->count() > 0)

                        @foreach ($userDetails->selfImages as $selfImage)
                            <p class="mb-6"><img width="300px" height="200px"
                                    src="{{ asset('storage/imagesfinal/' . $selfImage->path) }}" class="img-fluid">
                            </p>
                        @endforeach
                    @else
                        <p>No Image Found
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

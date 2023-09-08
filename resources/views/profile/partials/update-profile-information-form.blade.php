<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)"
                required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)"
                required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

        </div>
        <div>
            <x-input-label for="cnic" />
            <x-text-input id="cnic" name="cnic" type="file" class="filepond mt-1 block w-full" required />

        </div>
        <div>
            <x-input-label for="images" />
            <x-text-input id="images" name="images[]" multiple type="file" class="filepond mt-1 block w-full"
                required />


        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>


<script>
    document.addEventListener('FilePond:loaded', (e) => {

        console.log('FilePond is ready for use', e.detail);

        const inputElements = document.querySelectorAll('input.filepond');


        FilePond.setOptions({
            // credits: false,
            server: {

                process: './filepond-process',
                revert: './filepond-delete',
                headers: {

                    'X-CSRF-TOKEN': '{{ csrf_token() }}',

                }


            }


        })


        Array.from(inputElements).forEach(inputElement => {

            const filepond = FilePond.create(inputElement);


        })




    });
</script>

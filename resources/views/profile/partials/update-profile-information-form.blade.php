<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <!-- Profile Photo Section -->
        <div>
            <x-input-label for="profile_photo" :value="__('Profile Photo')" />
            <div class="mt-2 flex items-center gap-4">
                @if(Auth::user()->profile_photo)
                    <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" 
                         alt="Profile Photo" 
                         class="w-20 h-20 rounded-full object-cover border-2 border-gray-300"
                         id="preview-image">
                @else
                    <div class="w-20 h-20 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-2xl font-bold"
                         id="preview-initial">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <img src="" 
                         alt="Profile Photo" 
                         class="w-20 h-20 rounded-full object-cover border-2 border-gray-300 hidden"
                         id="preview-image">
                @endif
                <div class="flex-1">
                    <input type="file" 
                           id="profile_photo" 
                           name="profile_photo" 
                           accept="image/*"
                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                           onchange="previewPhoto(event)">
                    <p class="mt-1 text-xs text-gray-500">JPG, PNG, atau GIF (Max. 2MB)</p>
                    @if(Auth::user()->profile_photo)
                        <label class="mt-2 flex items-center">
                            <input type="checkbox" name="remove_photo" value="1" class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500">
                            <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Hapus foto profil</span>
                        </label>
                    @endif
                </div>
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('profile_photo')" />
        </div>

        <script>
        function previewPhoto(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewImage = document.getElementById('preview-image');
                    const previewInitial = document.getElementById('preview-initial');
                    
                    previewImage.src = e.target.result;
                    previewImage.classList.remove('hidden');
                    if (previewInitial) {
                        previewInitial.classList.add('hidden');
                    }
                }
                reader.readAsDataURL(file);
            }
        }
        </script>

        <div>
            <x-input-label for="username" :value="__('Username')" />
            <x-text-input id="username" name="username" type="text" class="mt-1 block w-full" :value="old('username', $user->username)" required autofocus autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('username')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>

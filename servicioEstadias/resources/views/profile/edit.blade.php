<x-app-layout>
    @if(auth()->user()->email == 'desarrolloacademico@tecvalles.mx')
        <x-admin-layout></x-admin-layout>
    @elseif(auth()->user()->email == 'vinculacion@tecvalles.mx')
        <x-vinculacion-layout></x-vinculacion-layout>
    @else
        <x-user-layout></x-user-layout>
    @endif
<br>
    <x-username-layout></x-username-layout>
    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            
        </div>
    </div>
</x-app-layout>


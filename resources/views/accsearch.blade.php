<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
	<x-slot name="navi">
      <x-nav/>
    </x-slot>
    <div class="wide-marg">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                   <form method="post" action="">
                    Account: <input type="text" name="account">
                    	<input type="hidden" name="_token" value="{{ csrf_token() }}">
                    	<input type="submit" value="search">
                    	
                   </form>  
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
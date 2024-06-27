<x-app-layout>

	@php 
		$i = 1;
		$acc = array();
    @endphp
    @section('title', 'Your search results')
    
     <x-slot name="header">
     </x-slot>
     
     <x-slot name="navi">
      <x-nav/>
    </x-slot>

        <div class="w-100">
            <div class="w-100 bg-black pad">
              <h1>Search results for customer {{ $account }}</h1>
            </div>
           @foreach ($vc as $k => $v)
           	@php if($i === 1) {$top = '';}else{$top = ' marg-top-large';} @endphp
            <div class="bg-blue w-100 pad{{ $top }}">Customer: {{ $account }} </div>
            <div class="row">
              <div class="marg card radius">
              	<table class="w-100">
                     @foreach ($v->account as $vk => $vv)
                      <tr class="w-100">       
                      	<td class="bold">{{ $vv['label'] }}:</td>
                      	<td class="right">@php echo htmlentities(ucfirst($vv['value']), ENT_QUOTES); @endphp</td>
                      </tr>        
                     @endforeach
                 </table>
              </div>
              <div class="pad marg card radius">
               <div class="bg-black"></div>
              
              	<form method="post">
              		<input type="hidden" name="type" value="1">
                         <input type="hidden" name="account" value="{{ $account }}">
                         <input type="hidden" name="account_id" value="{{ $v->account_id }}">
                         <input type="hidden" name="_token" value="{{ csrf_token() }}">
                         <input type="hidden" name="deb" value="{{ session('debounce') }}">
              	  <table>
              	  	<tr>
              	  	  <td class="left bold w-50">
                          	Address:
                          	<hr>
                      </td>  
                     <td class="right w-50">
                     	<input class="w-100" type="submit" value="Update">
                     </td>
                    </tr>
                     @foreach ($v->address as $vk => $vv)
                       <tr>     
                       <td class="w-50">{{ $vv['label'] }}:</td>
                       <td class="w-50"><input class="center" name="{{$vk}}" type="text" value="{{ $vv['value'] }}"></td>
                       </tr>     
                     @endforeach
                     
                   </table>
                 </form>
                 
              </div>
              <div class="marg card radius right">
              <p class="bold left w-100">
              	Add Transaction:
              </p>
               <form method="post" action="">
              	<table class="w-100">
              	  <tr>
              		<td class="left w-50">
                     	<input type="hidden" name="account_id" value="{{ $v->account_id }}">Amount:
                    </td>
                    <td class="right w-50">
                     	<input class="w-90 center" type="text" name="amount">
                    </td>
                  </tr>
                  <tr>
                    <td class="left w-50">
                    	Credit or Debit?
                    </td>
                    <td class="right w-50">
                         <select class="w-90" name="txn_type_cd">
                         	<option value="DBT" selected="selected">Debit</option>
                         	<option value="CDT">Credit</option>
                         </select>
                     </td>
                   </tr>
                   <tr>
                     <td colspan=2 class="right w-100">
                     	<input type="hidden" name="type" value="2">
                     	<input type="hidden" name="account" value="{{ $account }}">
                    	<input type="hidden" name="_token" value="{{ csrf_token() }}">
                    	<input type="hidden" name="deb" value="{{ session('debounce') }}">
                    	<input class="w-50" type="submit" value="Add">
                     </td>
                    </tr>
                 </table>
                </form>
              </div>
            </div>
            @php 
        		$ii = 1;
        		$thead = '';
        		$tbod = '';
            @endphp
            <table class="w-100">
                @foreach ($trans[$k] as $tk => $tv)
                  @php $tbod = ''; @endphp
                		@foreach ($tv as $uk => $uv)
                			@php
                				if($ii === 1)
                				{
                					if(array_key_exists($uk, $th))
                					{
                						$thead .= '<th class="bg-lightgray">'.htmlentities(ucfirst($th[$uk]), ENT_QUOTES).'</th>';
                					}
                				}
                				if(array_key_exists($uk, $th))
                				{
                    				$tbod .= '<td class="center bord">'.htmlentities($uv, ENT_QUOTES).'</td>';
                    			}
                    		@endphp
                    	@endforeach
                    	@php
                    		if($ii === 1)
                			{
                				echo '<tr>'.$thead.'</tr>';
                			}
                			echo '<tr>'.$tbod.'</tr>';
                    	 	$ii = $ii + 1;
                    	@endphp
                @endforeach
            </table>
            @php $i = $i + 1; @endphp
          @endforeach
        </div>
</x-app-layout>

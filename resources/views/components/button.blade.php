<button type="submit" class="border-gray-300 border-2 rounded flex ml-0 mr-2 my-4 p-2">
    @if(empty($btnText))@else
        <span>{{$btnText}}</span>
    @endif
    <svg class="size-6 shrink-0 self-center stroke-[#FF2D20]"
         xmlns="http://www.w3.org/2000/svg"
         fill="none" viewBox="0 0 24 24" stroke-width="1.5">
        <path stroke-linecap="round" stroke-linejoin="round"
              d="M4.5 12h15m0 0l-6.75-6.75M19.5 12l-6.75 6.75"/>
    </svg>
</button>
<div 
    class="relative min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0"
    style="background-image: url('/assets/logo/fondologin.png'); background-size: cover; background-position: center;"
>
    <div class="absolute inset-0 bg-blue-950/50"></div>

    <div class="relative z-10 flex flex-col items-center w-full">
        <div>
            <!--{{ $logo }}-->
        </div>

        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            {{ $slot }}
        </div>
    </div>
</div>

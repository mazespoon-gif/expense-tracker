<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense tracker</title>
    <link rel="icon" href="{{ asset('image/logoresult.webp') }}" >
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxAppearance
    <style>
    h1{
        font-family: "Urbanist", sans-serif;
    }
    </style>
</head>
<body class="bg-gray-50">
 <nav class="flex h-[60px] border-b border-gray-200 items-center gap-2 justify-between px-3 sticky top-0 bg-gray-50 z-50">
    <x-app-logo   href="{{ route('home') }}" wire:navigate />
    <div class="flex items-center gap-2">
        @auth
            <a href="{{ route('dashboard') }}" wire:navigate class="text-sm font-medium text-gray-700 hover:text-gray-900">
                <button class="p-2 px-4 rounded-lg cursor-pointer bg-zinc-900 hover:bg-zinc-800 text-white wire:navigate">
                    Dashboard
                </button>
            </a>
        @else
            <a href="{{ route('login') }}" wire:navigate class="text-sm font-medium text-gray-700 hover:text-gray-900">
                <button class="p-2 px-4 rounded-lg cursor-pointer bg-zinc-900 hover:bg-zinc-800 text-white wire:navigate">
                    Login
                </button>
            </a>
            <a href="{{ route('register') }}" wire:navigate class="text-sm font-medium text-gray-700 hover:text-gray-900">
                <button class="p-2 rounded px-4 bg-zinc-900 hover:bg-zinc-800 text-white">
                    Register
                </button>
            </a>
        @endauth
    </div>
 </nav>

 <div class="mt-5 px-4 p-6 flex flex-col justify-center items-center h-screen:">


    <div class="flex flex-wrap items-center justify-center gap-2 pl-2.5 pr-4 py-1.5 mt-2 rounded-full border border-zinc-200">
        <div class="relative flex size-3.5 items-center justify-center">
            <span class="absolute inline-flex h-full w-full rounded-full bg-zinc-300 opacity-75 animate-ping duration-300"></span>
            <span class="relative inline-flex size-2 rounded-full bg-zinc-600"></span>
        </div>
        <p class="text-sm text-zinc-600">Track your expenses remotely</p>
    </div>
    <h1 class="text-gray-700 mt-3 font-bold text-xl md:text-3xl">Welcome to Expense Tracker Site</h1>
    <p class="text-gray-500">monitor your expesnse across in any device</p>

    <div class="flex gap-4 mt-4">
        <a href="{{route('register')}}" wire:navigate>
            <button class="bg-zinc-900 hover:bg-zinc-800 text-white px-6 py-2.5 rounded-sm text-sm transition cursor-pointer group">
               <div class="relative overflow-hidden">
                   <span class="block transition-transform duration-200 group-hover:-translate-y-full">
                       Get Started
                   </span>
                   <span class="absolute top-0 left-0 block transition-transform duration-200 group-hover:translate-y-0 translate-y-full">
                       Get Started
                   </span>
               </div>
            </button>
        </a>
    </div>
    <div class="mt-5 w-full flex justify-center items-center  px-6">
        <img src="{{ asset('image/dashboard.png') }}" alt="Expense Tracker Illustration" class="hello w-full border-gray-200 border-solid border shadow-xl object-contain">
    </div>
 </div>


    <!-- the features of the page -->
    <div class="bg-gray-100">
        <div class="bg-gray-100 px-8 lg:px-20 xl:px-[120px] py-20 flex flex-col items-center">
            <div class="max-w-full sm:max-w-2xl md:max-w-3xl lg:max-w-5xl xl:max-w-6xl w-full mb-9">
                <h1 class="text-2xl md:text-3xl font-medium text-zinc-800 mb-4 tracking-tight">
                    Features of the Expense Tracker
                </h1>
                <p class="text-sm text-zinc-800 tracking-tight max-w-xl">
                    We create thoughtfully crafted experiences that serve real users while driving meaningful business outcomes.
                </p>
            </div>

            <!-- Features Grid with Borders -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 max-w-full sm:max-w-2xl md:max-w-3xl lg:max-w-5xl xl:max-w-6xl w-full border-t border-l border-zinc-200">

                <div class="relative p-6 md:p-8 flex flex-col gap-4 border-r border-b border-zinc-200 transition-all duration-300 cursor-pointer bg-linear-to-b from-white to-[#EEF0FF]">
                    <div class="flex items-center gap-2.5 mb-1">
                        <div>
                            <svg width="17" height="17"xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#4f39f6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-icon lucide-user"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        </div>
                        <h3 class="text-sm font-medium text-zinc-800 leading-snug">
                            User-first design
                        </h3>
                    </div>
                    <p class="text-xs text-zinc-600 leading-relaxed mb-4">
                        We design with real users in mind, focusing on clarity, usability and accessibility from day one.
                    </p>
                </div>

                <div class="relative p-6 md:p-8 flex flex-col gap-4 border-r border-b border-zinc-200 transition-all duration-300 cursor-pointer bg-white hover:bg-linear-to-b hover:from-white hover:to-[#EEF0FF]">
                    <div class="absolute left-0 top-12 bottom-12 md:top-17 md:bottom-17 w-1.5 bg-indigo-500 rounded-r"></div>
                    <div class="flex items-center gap-2.5 mb-1">
                        <div>
                            <svg width="20" height="16" viewBox="0 0 20 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M16.05.75H3.45a1.8 1.8 0 0 0-1.8 1.8v7.2a1.8 1.8 0 0 0 1.8 1.8h12.6a1.8 1.8 0 0 0 1.8-1.8v-7.2a1.8 1.8 0 0 0-1.8-1.8M.75 15.148h18" stroke="#4f39f6" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /></svg>
                        </div>
                        <h3 class="text-sm font-medium text-zinc-800 leading-snug">
                            Fully responsive
                        </h3>
                    </div>
                    <p class="text-xs text-zinc-600 leading-relaxed mb-4">
                        Interfaces that look and feel great on desktop, tablet and mobile, no compromises.
                    </p>
                </div>

                <div class="relative p-6 md:p-8 flex flex-col gap-4 border-r border-b border-zinc-200 transition-all duration-300 cursor-pointer bg-white hover:bg-linear-to-b hover:from-white hover:to-[#EEF0FF]">
                    <div class="flex items-center gap-2.5 mb-1">
                        <div>
                            <svg width="17" height="17" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#4f39f6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-lightbulb-icon lucide-lightbulb"><path d="M15 14c.2-1 .7-1.7 1.5-2.5 1-.9 1.5-2.2 1.5-3.5A6 6 0 0 0 6 8c0 1 .2 2.2 1.5 3.5.7.7 1.3 1.5 1.5 2.5"/><path d="M9 18h6"/><path d="M10 22h4"/></svg>
                        </div>

                        <h3 class="text-sm font-medium text-zinc-800 leading-snug">
                            Light mode & dark mode
                        </h3>
                    </div>
                    <p class="text-xs text-zinc-600 leading-relaxed mb-4">
                        Dark mode enhances focus with softer contrast and reduced visual fatigue. Light mode provides sharp contrast and clear readability for daily tasks.
                    </p>
                </div>

                <div class="relative p-6 md:p-8 flex flex-col gap-4 border-r border-b border-zinc-200 transition-all duration-300 cursor-pointer bg-white hover:bg-linear-to-b hover:from-white hover:to-[#EEF0FF]">
                    <div class="flex items-center gap-2.5 mb-1">
                        <div>
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#a)" stroke="#4f39f6" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M8.553 1.452a1.33 1.33 0 0 0-1.106 0l-5.714 2.6a.667.667 0 0 0 0 1.22l5.72 2.607a1.33 1.33 0 0 0 1.107 0l5.72-2.6a.667.667 0 0 0 0-1.22z" /><path d="M1.333 8a.67.67 0 0 0 .387.607l5.733 2.606a1.33 1.33 0 0 0 1.1 0l5.72-2.6A.67.67 0 0 0 14.667 8" /><path d="M1.333 11.332a.67.67 0 0 0 .387.607l5.733 2.606a1.33 1.33 0 0 0 1.1 0l5.72-2.6a.67.67 0 0 0 .394-.613" /></g><defs><clipPath id="a"><path fill="#fff" d="M0 0h16v16H0z" /></clipPath></defs></svg>
                        </div>
                        <h3 class="text-sm font-medium text-zinc-800 leading-snug">
                            Scalable systems
                        </h3>
                    </div>
                    <p class="text-xs text-zinc-600 leading-relaxed mb-4">
                        Consistent components, tokens and patterns built to grow with your product.
                    </p>
                </div>

                <div class="relative p-6 md:p-8 flex flex-col gap-4 border-r border-b border-zinc-200 transition-all duration-300 cursor-pointer bg-white hover:bg-linear-to-b hover:from-white hover:to-[#EEF0FF]">
                    <div class="flex items-center gap-2.5 mb-1">
                        <div>
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M11.333 1.332 14 3.999l-2.667 2.666" stroke="#4f39f6" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /><path d="M2 7.333v-.666A2.667 2.667 0 0 1 4.667 4H14M4.667 14.665 2 12l2.667-2.667" stroke="#4f39f6" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /><path d="M14 8.668v.667A2.667 2.667 0 0 1 11.333 12H2" stroke="#4f39f6" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /></svg>
                        </div>
                        <h3 class="text-sm font-medium text-zinc-800 leading-snug">
                            Easy to iterate
                        </h3>
                    </div>
                    <p class="text-xs text-zinc-600 leading-relaxed mb-4">
                        Flexible designs that adapt quickly as your product, users and goals evolve.
                    </p>
                </div>

                <div class="relative p-6 md:p-8 flex flex-col gap-4 border-r border-b border-zinc-200 transition-all duration-300 cursor-pointer bg-white hover:bg-linear-to-b hover:from-white hover:to-[#EEF0FF]">
                    <div class="flex items-center gap-2.5 mb-1">
                        <div>
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2.667 9.334a.667.667 0 0 1-.52-1.087l6.6-6.8a.333.333 0 0 1 .573.307L8.04 5.767a.667.667 0 0 0 .627.9h4.666a.666.666 0 0 1 .52 1.087l-6.6 6.8a.334.334 0 0 1-.573-.307l1.28-4.013a.667.667 0 0 0-.627-.9z" stroke="#4f39f6" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /></svg>
                        </div>
                        <h3 class="text-sm font-medium text-zinc-800 leading-snug">
                            Performance-aware
                        </h3>
                    </div>
                    <p class="text-xs text-zinc-600 leading-relaxed mb-4">
                        Optimized layouts and interactions that support fast load times and smooth experiences.
                    </p>
                </div>

                <div class="relative p-6 md:p-8 flex flex-col gap-4 border-r border-b border-zinc-200 transition-all duration-300 cursor-pointer bg-white hover:bg-linear-to-b hover:from-white hover:to-[#EEF0FF]">
                    <div class="flex items-center gap-2.5 mb-1">
                        <div>
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#a)"><path d="M9.8 4.201a.667.667 0 0 0 0 .933l1.067 1.067a.666.666 0 0 0 .933 0l2.07-2.07c.214-.215.576-.147.656.145A4 4 0 0 1 9.02 8.981l-5.273 5.274a1.414 1.414 0 0 1-2-2L7.021 6.98a4 4 0 0 1 4.704-5.506c.292.08.36.441.146.656z" stroke="#4f39f6" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /></g><defs><clipPath id="a"><path fill="#fff" d="M0 0h16v16H0z" /></clipPath></defs></svg>
                        </div>
                        <h3 class="text-sm font-medium text-zinc-800 leading-snug">
                            Tool-friendly
                        </h3>
                    </div>
                    <p class="text-xs text-zinc-600 leading-relaxed mb-4">
                        Designs built with localization, multiple languages and diverse audiences in mind.
                    </p>
                </div>

                <div class="relative p-6 md:p-8 flex flex-col gap-4 border-r border-b border-zinc-200 transition-all duration-300 cursor-pointer bg-white hover:bg-linear-to-b hover:from-white hover:to-[#EEF0FF]">
                    <div class="flex items-center gap-2.5 mb-1">
                        <div>
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="m10.667 12 4-4-4-4M5.333 4l-4 4 4 4" stroke="#4f39f6" stroke-linecap="round" stroke-linejoin="round" /></svg>
                        </div>
                        <h3 class="text-sm font-medium text-zinc-800 leading-snug">
                            Dev-ready handoff
                        </h3>
                    </div>
                    <p class="text-xs text-zinc-600 leading-relaxed mb-4">
                        From startups to enterprise, our designs integrate seamlessly with your existing workflows.
                    </p>
                </div>
            </div>
        </div>
    </div>
    <!--the what-->
    <!-- This area is footer  -->
    <div class='pt-20 px-4'>
    <footer class="bg-white w-full max-w-[1350px] mx-auto shadow-lg shadow-gray-600 text-black pt-8 lg:pt-12 px-4 sm:px-8 md:px-16 lg:px-28 rounded-tl-3xl rounded-tr-3xl overflow-hidden">
        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-6 gap-8 md:gap-12 ">
            
            <div class="lg:col-span-3 space-y-6 ">
                      <x-app-logo href="{{ route('home') }}" wire:navigate />
                <p class="text-sm/6 text-neutral-600 max-w-96">Expense tracker help you track your daily expenses weekly monthly and daily with chart ang graph included </p>
                <div class="flex gap-5 md:gap-6 order-1 md:order-2">
                
                    <!-- Github -->
                    <a href="https://github.com/mazespoon-gif" target="_blank" class="text-neutral-600 hover:text-neutral-700 py-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M15 22v-4a4.8 4.8 0 0 0-1-3.5c3 0 6-2 6-5.5.08-1.25-.27-2.48-1-3.5.28-1.15.28-2.35 0-3.5 0 0-1 0-3 1.5-2.64-.5-5.36-.5-8 0C6 2 5 2 5 2c-.3 1.15-.3 2.35 0 3.5A5.403 5.403 0 0 0 4 9c0 3.5 3 5.5 6 5.5-.39.49-.68 1.05-.85 1.65-.17.6-.22 1.23-.15 1.85v4"/><path d="M9 18c-4.51 2-5-2-7-2"/>
                        </svg>
                    </a>
                    <span class="text-sm text-accent bg-amber-200 px-3 py-1  rounded-lg shadow-md">My Github desu</span>
                </div>
            </div>

            <div class="lg:col-span-3 grid grid-cols-1 md:grid-cols-1 gap-8 md:gap-12 lg:gap-28 items-start">
                <!-- Products -->
                <div>
                    <img src="https://media1.tenor.com/m/cCizK5_RohEAAAAC/peluk.gif" alt="Expense Tracker Logo" class="w-full rounded-lg h-[250px] [-webkit-clip-path:polygon(25%_0%,100%_0%,75%_100%,0%_100%)] [clip-path:polygon(25%_0%,100%_0%,75%_100%,0%_100%)]">
                    
                </div>
               
            </div>
        </div>

        <div class="max-w-7xl mx-auto mt-12 pt-4 border-t border-neutral-300 flex justify-between items-center">
            <p class="text-neutral-600 text-sm">Created by Drake</p>
            <p class='text-sm text-neutral-600'>All right reserved.</p>
        </div>
        <div class="relative">
            <div class="absolute inset-x-0 bottom-0 mx-auto w-full max-w-3xl h-full max-h-64 bg-slate-100 rounded-full blur-[100px] pointer-events-none"></div>
            <h1 class=" text-center font-extrabold leading-[0.7] text-transparent text-[clamp(3rem,15vw,15rem)] [-webkit-text-stroke:1px_#D4D4D4] mt-6" >
                Expenses
            </h3>
                        <h1 class=" text-center font-extrabold leading-[0.7] text-transparent text-[clamp(3rem,15vw,15rem)] [-webkit-text-stroke:1px_#D4D4D4] mt-6" >
                Tracker
            </h3>
        </div>
    </footer>
</div>
    @fluxScripts
</body>
</html>

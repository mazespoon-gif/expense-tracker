<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>419 Page Expired</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white">
    <div class="flex items-center justify-center min-h-screen p-4">
        <article class="border-2 border-black bg-white text-black shadow-[4px_4px_0_0,8px_8px_0_0] shadow-black max-w-md w-full">
            <div class="bg-yellow-200 p-3">
                <div class="flex items-center justify-between">
                    <strong class="text-xs/none font-bold uppercase">System Message</strong>
                    <div class="flex gap-1">
                        <div class="size-3 border-2 border-black bg-white"></div>
                        <div class="size-3 border-2 border-black bg-white"></div>
                    </div>
                </div>
            </div>

            <div class="border-t-2 border-black p-4 sm:p-6">
                <h3 class="text-lg font-semibold">419 Page Expired</h3>
                <p class="mt-2 text-sm text-pretty">
                    Your session has expired. Please refresh the page and try again.
                </p>
                <div class="mt-6">
                    <button onclick="history.back()" class="group px-8 py-2.5 bg-black rounded-lg text-white cursor-pointer active:scale-95 transition duration-300 hover:bg-black">
                        <p class="relative h-6 overflow-hidden">
                            <span class="block transition-transform duration-300 group-hover:-translate-y-full">Go back</span>
                            <span class="absolute w-full top-full left-1/2 -translate-x-1/2 block transition-transform duration-300 group-hover:translate-y-[-100%]">Go back</span>
                        </p>
                    </button>
                </div>
            </div>
        </article>
    </div>
</body>
</html>

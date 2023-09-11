<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<form
    class="flex flex-col items-center justify-center min-h-screen bg-gray-100"
    id="search-form"
>
    <label for="email" class="block text-sm font-medium leading-6 text-gray-900">
        OS NGD API – Features - <b>{{ env('VITE_OS_COLLECTION_ID') }} collection</b>
    </label>
    <div class="mt-2 flex rounded-md shadow-sm">
        <div class="relative flex flex-grow items-stretch focus-within:z-10">
            <input
                type="text"
                name="query"
                id="query"
                class="block w-full rounded-none rounded-l-md border-0 py-1.5 pl-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                placeholder="Search" />
        </div>
        <button type="submit" class="relative -ml-px inline-flex items-center gap-x-1.5 rounded-r-md px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
            <span>Search</span>
        </button>
    </div>
</form>

</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Horus</title>

    <link rel="stylesheet" href="/css/app.css">
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <main>
            <section class="bg-gray-50">
                <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
                    <a href="#" class="flex items-center mb-6 text-2xl font-semibold text-gray-900">
                        <img src="/images/logo.svg" class="h-6 mr-3 sm:h-7" alt="Horus Logo" />
                        Horus
                    </a>
                    <div class="w-full bg-white rounded-lg shadow md:mt-0 sm:max-w-md xl:p-0">
                        <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                            <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl">
                                Sign in to your account
                            </h1>
                            <form method="POST" class="space-y-4 md:space-y-6" action="{{ route('login.login') }}">
                                <div>
                                    <label for="username" class="block mb-2 text-sm font-medium text-gray-900 ">Username</label>
                                    <input type="text" name="username" id="username" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" placeholder="j.doe@st.hanze.nl" required>
                                </div>
                                <div>
                                    <label for="password" class="block mb-2 text-sm font-medium text-gray-900 ">Password</label>
                                    <input type="password" name="password" id="password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" required>
                                </div>
                                @if(!empty($error))
                                    <p>{{ $error }}</p>
                                @endif
                                <button type="submit" class="w-full text-white bg-secondary hover:bg-secondary-hover focus:ring-4 focus:outline-none focus:ring-primary-300 font-bold rounded-lg text-sm px-5 py-2.5 text-center">Sign in</button>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>
</body>
</html>

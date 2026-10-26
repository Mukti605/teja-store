<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Masuk admin | Teja Store</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="grid min-h-screen place-items-center bg-deep px-4 font-sans text-foreground antialiased" x-data="{ show: false }">
    <div class="animate-rise w-full max-w-sm">
        <div class="mb-6 text-center text-primary-foreground">
            <span class="mx-auto grid size-12 place-items-center rounded-2xl bg-accent text-xl font-extrabold text-accent-foreground">T</span>
            <h1 class="mt-3 text-2xl font-extrabold">Masuk admin</h1>
            <p class="text-sm text-primary-foreground/70">Halaman ini khusus pengelola Teja Store.</p>
        </div>

        <x-ui.card class="p-6">
            <form method="POST" action="{{ route('admin.login.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="email" class="mb-1.5 block text-sm font-semibold">Email</label>
                    <x-ui.input id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="username" autofocus required />
                    @error('email')<p class="mt-1.5 text-sm text-destructive">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="password" class="mb-1.5 block text-sm font-semibold">Kata sandi</label>
                    <div class="relative">
                        <x-ui.input id="password" name="password" x-bind:type="show ? 'text' : 'password'" type="password" autocomplete="current-password" class="pr-16" required />
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-2 px-2 text-xs font-bold text-primary" x-text="show ? 'Sembunyikan' : 'Lihat'"></button>
                    </div>
                </div>
                <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="remember" class="size-4 rounded border-border accent-primary"> Ingat saya di perangkat ini</label>
                <x-ui.button type="submit" size="lg" class="w-full">Masuk</x-ui.button>
            </form>
        </x-ui.card>
        <a href="{{ route('shop.index') }}" class="mt-5 block text-center text-sm font-semibold text-primary-foreground/70 hover:text-primary-foreground">Kembali ke toko</a>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SafeVoice - Ruang Aman')</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        dominant: '#F8FAFC',
                        oceanDark: '#0E2443',
                        oceanMain: '#12315F',
                        oceanBlue: '#2563EB',
                        oceanLight: '#38BDF8',
                        accentStart: '#FF8A5B',
                        accentEnd: '#FFB36B',
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', 'Inter', sans-serif; }
        .gradient-main {
            background: linear-gradient(135deg, #12315F 0%, #2563EB 50%, #38BDF8 100%);
        }
        .gradient-accent {
            background: linear-gradient(135deg, #FF8A5B 0%, #FFB36B 100%);
        }
        .text-gradient-accent {
            background: linear-gradient(135deg, #FF8A5B 0%, #FFB36B 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .bg-ocean-gradient {
            background: linear-gradient(135deg, #0A1830 0%, #12315F 55%, #1E3A8A 100%);
        }
        .input-focus-effect:focus {
            border-color: #2563EB;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.2);
        }
    </style>
    @stack('styles')
</head>
<body class="@yield('body-class', 'bg-gradient-to-br from-[#0a1830] via-[#12315F] to-[#1e3a8a] min-h-screen flex items-center justify-center p-4')">
    @yield('content')
    @stack('scripts')
</body>
</html>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <!-- Подключаем Font Awesome для иконок -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
    <header>
        <div class="header_left">
            <h1><a href="{{ route('mainpage') }}">TIPA Liquidpedia</a></h1>
        </div>

        <div class="header_right">
            <nav>
                <div class="navItem">
                    <a href="{{ route('top_teams') }}" class="navLink">
                        <i class="fas fa-users"></i> Top Teams
                    </a>
                </div>
                <div class="navItem">
                    <a href="{{ route('top_athletes') }}" class="navLink">
                        <i class="fas fa-user"></i> Top Athletes
                    </a>
                </div>

            </nav>
        </div>
    </header>

    @yield('content')

</body>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
        background-color: #f5f5f5;
        color: #333;
    }

    header {
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 2rem;
        background-color: #2c3e50;
        color: white;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .header_left h1 {
        font-size: 1.5rem;
        margin: 0;
    }

    .header_left a {
        color: white;
        text-decoration: none;
        transition: color 0.3s;
    }

    .header_left a:hover {
        color: #4a6bdf;
    }

    nav {
        display: flex;
        flex-direction: row;
        justify-content: space-around;
        gap: 1.5rem;
    }

    .navItem {
        display: flex;
        align-items: center;
    }

    .navLink {
        color: white;
        text-decoration: none;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 4px;
        transition: all 0.3s;
    }

    .navLink:hover {
        background-color: rgba(255, 255, 255, 0.1);
        transform: translateY(-2px);
    }

    .navLink i {
        font-size: 0.9rem;
    }

    /* Адаптивность для мобильных устройств */
    @media (max-width: 768px) {
        header {
            flex-direction: column;
            padding: 1rem;
            gap: 1rem;
        }

        nav {
            width: 100%;
            justify-content: space-between;
            gap: 0.5rem;
        }

        .navLink {
            padding: 0.5rem;
            font-size: 0.9rem;
        }

        .navLink span {
            display: none;
        }

        .navLink i {
            font-size: 1.1rem;
        }
    }
</style>

</html>

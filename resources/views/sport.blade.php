@extends('layout')

@section('title')
    {{ $sport->name }} - Sports Encyclopedia
@endsection

@section('content')
    <div class="sport-container">
        <!-- Заголовок с иконкой вида спорта -->
        <div class="sport-header">
            <div class="sport-icon">
                @php
                    // Генерируем иконку на основе названия спорта
                    $sportIcons = [
                        'football' => 'fa-futbol',
                        'basketball' => 'fa-basketball-ball',
                        'tennis' => 'fa-table-tennis',
                        'swimming' => 'fa-swimmer',
                        'volleyball' => 'fa-volleyball-ball',
                        'hockey' => 'fa-hockey-puck',
                        'boxing' => 'fa-boxing-glove',
                        'cycling' => 'fa-bicycle'
                    ];
                    $iconClass = $sportIcons[strtolower($sport->name)] ?? 'fa-trophy';
                @endphp
                <i class="fas {{ $iconClass }}"></i>
            </div>
            <h1>{{ $sport->name }}</h1>
        </div>

        <!-- Секция событий -->
        <section class="events-section">
            <h2 class="section-title">
                <i class="far fa-calendar-alt"></i>Events
            </h2>

            <div class="events-grid">
                @foreach ($events as $event)
                    <div class="event-card">
                        <div class="event-header">
                            <a href="{{ route('event', ['id' => $event->id]) }}" class="event-name">
                                {{ $event->name }}
                            </a>
                            <div class="event-date">
                                <span class="date-icon"><i class="far fa-clock"></i></span>
                                <time datetime="{{ $event->start_datetime }}">
                                    {{ date('M d, Y', strtotime($event->start_datetime)) }}
                                </time>
                                @if($event->finish_datetime && $event->finish_datetime != $event->start_datetime)
                                    <span class="date-separator">to</span>
                                    <time datetime="{{ $event->finish_datetime }}">
                                        {{ date('M d, Y', strtotime($event->finish_datetime)) }}
                                    </time>
                                @endif
                            </div>
                        </div>

                        @if($event->description)
                            <div class="event-description">
                                {{ $event->description }}
                            </div>
                        @endif

                        <div class="event-footer">
                            {{-- <div class="event-time">
                                <i class="far fa-calendar-check"></i>
                                {{ date('H:i', strtotime($event->start_datetime)) }}
                                @if($event->finish_datetime && $event->finish_datetime != $event->start_datetime)
                                    - {{ date('H:i', strtotime($event->finish_datetime)) }}
                                @endif
                            </div> --}}
                            <a href="{{ route('event', ['id' => $event->id]) }}" class="event-link">
                                View details <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            @if(count($events) === 0)
                <div class="no-events">
                    <i class="far fa-calendar-times"></i>
                    <p>No upcoming events scheduled</p>
                </div>
            @endif
        </section>
    </div>

    <style>
        /* Основные стили */
        .sport-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1.5rem;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
        }

        /* Шапка вида спорта */
        .sport-header {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            margin-bottom: 2.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid #eaeaea;
        }

        .sport-icon {
            width: 80px;
            height: 80px;
            background-color: #4a6bdf;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2.5rem;
            box-shadow: 0 4px 12px rgba(74, 107, 223, 0.3);
        }

        .sport-header h1 {
            font-size: 2.5rem;
            margin: 0;
            color: #2c3e50;
            font-weight: 700;
        }

        /* Секция событий */
        .section-title {
            font-size: 1.75rem;
            color: #2c3e50;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .section-title i {
            color: #4a6bdf;
        }

        .events-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 1.5rem;
        }

        /* Карточка события */
        .event-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
            border: 1px solid #eaeaea;
        }

        .event-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .event-header {
            padding: 1.5rem 1.5rem 0;
        }

        .event-name {
            font-size: 1.3rem;
            font-weight: 600;
            color: #2c3e50;
            text-decoration: none;
            display: block;
            margin-bottom: 0.75rem;
            transition: color 0.2s;
        }

        .event-name:hover {
            color: #4a6bdf;
        }

        .event-date {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.95rem;
            color: #6c757d;
            margin-bottom: 1rem;
        }

        .date-icon {
            color: #4a6bdf;
        }

        .date-separator {
            color: #adb5bd;
        }

        .event-description {
            padding: 0 1.5rem 1.5rem;
            color: #495057;
            line-height: 1.6;
            flex-grow: 1;
        }

        .event-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 1.5rem;
            background-color: #f8f9fa;
            border-top: 1px solid #eaeaea;
        }

        .event-time {
            font-size: 0.9rem;
            color: #6c757d;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .event-link {
            font-size: 0.9rem;
            color: #4a6bdf;
            text-decoration: none;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: color 0.2s;
        }

        .event-link:hover {
            color: #3a56c0;
        }

        .event-link i {
            font-size: 0.8rem;
            transition: transform 0.2s;
        }

        .event-link:hover i {
            transform: translateX(3px);
        }

        /* Нет событий */
        .no-events {
            text-align: center;
            padding: 3rem;
            background-color: #f8f9fa;
            border-radius: 8px;
            color: #6c757d;
        }

        .no-events i {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: #adb5bd;
        }

        .no-events p {
            margin: 0;
            font-size: 1.1rem;
        }

        /* Адаптивность */
        @media (max-width: 768px) {
            .sport-header {
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }

            .sport-icon {
                width: 70px;
                height: 70px;
                font-size: 2rem;
            }

            .sport-header h1 {
                font-size: 2rem;
            }

            .events-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <!-- Подключаем Font Awesome для иконок -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
@endsection

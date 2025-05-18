@extends('layout')

@section('title')
    {{ $team->name }}
@endsection

@section('content')
    <div class="container">
        <!-- Карточка команды -->
        <div class="team-card">
            @php
                $initials = strtoupper(substr($team->name, 0, 2));
                $bgColor = '#' . substr(md5($team->name), 0, 6);
            @endphp

            <div class="team-avatar" style="background-color: {{ $bgColor }};">
                {{ $initials }}
            </div>
            <div class="team-info">
                <h1>[{{ $team->id }}] {{ $team->name }}</h1>
                <h2>
                    Sport: <a href="{{ route('sport', ['id'=> $team->sport->id]) }}">{{ $team->sport->name }}</a>
                </h2>
            </div>
        </div>

        <!-- Секция участников команды -->
        <div class="section">
            <div class="section-header" onclick="toggleSection('members')">
                <h2>
                    <span class="section-title">Team Members</span>
                    <span class="badge">{{ count($athletes) }}</span>
                    <span class="arrow">▸</span>
                </h2>
            </div>
            <div class="section-content" id="members">
                <div class="members-grid">
                    @foreach ($athletes as $athlete)
                        @php
                            $nameParts = explode(' ', $athlete->name);
                            if (count($nameParts) >= 2) {
                                $athleteInitials = strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1));
                            } else {
                                $athleteInitials = strtoupper(substr($athlete->name, 0, 2));
                            }
                            $athleteBgColor = '#' . substr(md5($athlete->name), 0, 6);
                        @endphp

                        <div class="member-card">
                            <div class="member-avatar" style="background-color: {{ $athleteBgColor }};">
                                {{ $athleteInitials }}
                            </div>
                            <div class="member-info">
                                <div class="member-name">{{ $athlete->name }}</div>
                                <div class="member-id">ID: {{ $athlete->id }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Секция событий команды -->
        <div class="section">
            <div class="section-header" onclick="toggleSection('events')">
                <h2>
                    <span class="section-title">Team Events</span>
                    <span class="badge">{{ count($events) }}</span>
                    <span class="arrow">▸</span>
                </h2>
            </div>
            <div class="section-content" id="events">
                <div class="events-list">
                    @foreach ($events as $event)
                        <a href="{{ route('event', ['id' => $event->id]) }}" class="event-card">
                            <div class="event-info">
                                <div class="event-name">{{ $event->name }}</div>
                                <div class="event-datetime">
                                    <i class="far fa-calendar-alt"></i>
                                    {{ date('M d, Y', strtotime($event->start_datetime)) }}
                                    @if($event->finish_datetime && $event->finish_datetime != $event->start_datetime)
                                        - {{ date('M d, Y', strtotime($event->finish_datetime)) }}
                                    @endif
                                </div>
                            </div>
                            <div class="event-arrow">
                                <i class="fas fa-chevron-right"></i>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Секция матчей команды -->
        <div class="section">
            <div class="section-header" onclick="toggleSection('matches')">
                <h2>
                    <span class="section-title">Team Matches</span>
                    <span class="badge">{{ count($matches) }}</span>
                    <span class="arrow">▸</span>
                </h2>
            </div>
            <div class="section-content" id="matches">
                <div class="matches-list">
                    @foreach ($matches as $match)
                        <a href="{{ route('match', ['id' => $match->id]) }}" class="match-card">
                            <div class="match-info">
                                <div class="match-name">{{ $match->name ?? 'Unnamed match' }}</div>
                                <div class="match-datetime">
                                    <i class="far fa-calendar-alt"></i> {{ $match->match_datetime }}
                                </div>
                            </div>
                            <div class="match-arrow">
                                <i class="fas fa-chevron-right"></i>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <style>
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Стили для карточки команды */
        .team-card {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 30px;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .team-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            font-weight: bold;
            color: white;
            flex-shrink: 0;
        }

        .team-info h1 {
            margin: 0;
            font-size: 24px;
        }

        .team-info h2 {
            margin: 5px 0 0;
            font-size: 18px;
            font-weight: normal;
        }

        /* Общие стили для секций */
        .section {
            margin-bottom: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .section-header {
            padding: 15px 20px;
            cursor: pointer;
            background: #f8f9fa;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .section-header h2 {
            margin: 0;
            font-size: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .badge {
            background: #6c757d;
            color: white;
            border-radius: 10px;
            padding: 2px 8px;
            font-size: 14px;
        }

        .arrow {
            transition: transform 0.2s;
        }

        .section-content {
            padding: 20px;
            max-height: 1000px;
            overflow: hidden;
            transition: max-height 0.3s ease, padding 0.3s ease;
        }

        .section-content.collapsed {
            max-height: 0;
            padding: 0 20px;
        }

        /* Стили для участников команды */
        .members-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
        }

        .member-card {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 12px;
            border-radius: 8px;
            background: #f8f9fa;
            transition: transform 0.2s;
        }

        .member-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 3px 8px rgba(0,0,0,0.1);
        }

        .member-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            font-weight: bold;
            color: white;
            flex-shrink: 0;
        }

        .member-info {
            flex-grow: 1;
        }

        .member-name {
            font-weight: 600;
            margin-bottom: 2px;
        }

        .member-id {
            font-size: 12px;
            color: #6c757d;
        }

        /* Стили для событий */
        .events-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .event-card {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            text-decoration: none;
            color: inherit;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .event-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .event-info {
            flex-grow: 1;
        }

        .event-name {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .event-datetime {
            font-size: 14px;
            color: #6c757d;
        }

        .event-arrow {
            color: #ddd;
            padding-left: 10px;
        }

        /* Стили для матчей */
        .matches-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .match-card {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            text-decoration: none;
            color: inherit;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .match-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .match-info {
            flex-grow: 1;
        }

        .match-name {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .match-datetime {
            font-size: 14px;
            color: #6c757d;
        }

        .match-arrow {
            color: #ddd;
            padding-left: 10px;
        }
    </style>

    <!-- Подключаем Font Awesome для иконок -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <script>
        function toggleSection(sectionId) {
            const section = document.getElementById(sectionId);
            const header = section.previousElementSibling;
            const arrow = header.querySelector('.arrow');

            section.classList.toggle('collapsed');

            if (section.classList.contains('collapsed')) {
                arrow.textContent = '▸';
            } else {
                arrow.textContent = '▾';
            }
        }

        // По умолчанию сворачиваем все секции
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.section-content').forEach(section => {
                section.classList.add('collapsed');
            });
        });
    </script>
@endsection

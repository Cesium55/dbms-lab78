@extends('layout')

@section('title')
    {{ $athlete->name }}
@endsection

@section('content')
    <div class="container">
        <!-- Карточка игрока -->
        <div class="athlete-card">
            @php
                $initials = '';
                $nameParts = explode(' ', $athlete->name);
                if (count($nameParts) >= 2) {
                    $initials = strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1));
                } else {
                    $initials = strtoupper(substr($athlete->name, 0, 2));
                }
                $bgColor = '#' . substr(md5($athlete->name), 0, 6);
            @endphp

            <div class="athlete-avatar" style="background-color: {{ $bgColor }};">
                {{ $initials }}
            </div>
            <div class="athlete-info">
                <h1>[{{ $athlete->id }}] {{ $athlete->name }}</h1>
                <h2>
                    Sport: <a href="{{ route('sport', ['id'=> $sport->id]) }}">{{ $sport->name }}</a>
                </h2>
            </div>
        </div>

        <!-- Секция Events с возможностью сворачивания -->
        <div class="section">
            <div class="section-header" onclick="toggleSection('events')">
                <h2>Events ▸</h2>
            </div>
            <div class="section-content" id="events">
                <div class="event-list">
                    @foreach ($events as $event)
                        <a href="{{ route('event', ['id' => $event->id]) }}" class="event-item">
                            <div class="event-name">
                                {{ $event->name }}
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Секция Matches с возможностью сворачивания -->
        <div class="section">
            <div class="section-header" onclick="toggleSection('matches')">
                <h2>Matches ▸</h2>
            </div>
            <div class="section-content" id="matches">
                <div class="match-list">
                    @foreach ($matches as $match)
                        <a href="{{ route('match', ['id' => $match->id]) }}" class="match-item">
                            <div class="match-name">{{ $match->name ?? 'Unnamed match' }}</div>
                            <div class="match-date">{{ $match->match_datetime }}</div>
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

        /* Стили для карточки игрока */
        .athlete-card {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 30px;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .athlete-avatar {
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

        .athlete-info h1 {
            margin: 0;
            font-size: 24px;
        }

        .athlete-info h2 {
            margin: 5px 0 0;
            font-size: 18px;
            font-weight: normal;
        }

        /* Стили для сворачиваемых секций */
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
        }

        .section-header h2 {
            margin: 0;
            font-size: 20px;
            user-select: none;
        }

        .section-content {
            padding: 0;
            max-height: 1000px;
            overflow: hidden;
            transition: max-height 0.3s ease, padding 0.3s ease;
        }

        .section-content.collapsed {
            max-height: 0;
            padding: 0 20px;
        }

        /* Стили для списков */
        .event-list, .match-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
            padding: 20px;
        }

        .event-item, .match-item {
            display: block;
            text-decoration: none;
            color: inherit;
            padding: 12px 15px;
            border-radius: 6px;
            background: #f8f9fa;
            transition: background 0.2s;
        }

        .event-item:hover, .match-item:hover {
            background: #e9ecef;
        }

        .match-item {
            display: flex;
            justify-content: space-between;
        }

        .match-date {
            color: #6c757d;
        }
    </style>

    <script>
        function toggleSection(sectionId) {
            const section = document.getElementById(sectionId);
            const header = section.previousElementSibling;

            section.classList.toggle('collapsed');

            // Меняем иконку стрелки
            const arrow = header.querySelector('h2');
            if (section.classList.contains('collapsed')) {
                arrow.innerHTML = arrow.innerHTML.replace('▾', '▸');
            } else {
                arrow.innerHTML = arrow.innerHTML.replace('▸', '▾');
            }
        }

        // По умолчанию можно свернуть все секции
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.section-content').forEach(section => {
                section.classList.add('collapsed');
            });
        });
    </script>
@endsection

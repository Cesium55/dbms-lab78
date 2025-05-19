@extends('layout')

@section('title')
    {{ $event->name }}
@endsection

@section('content')
    <div class="container">
        <!-- Карточка события -->
        <div class="event-header">
            <h1>[{{ $event->id }}] {{ $event->name }}</h1>
        </div>

        <!-- Секция Participants с возможностью сворачивания -->
        <div class="section">
            <div class="section-header" onclick="toggleSection('participants')">
                <h2>
                    <span class="section-title">Participants</span>
                    <span class="badge">{{ count($event->participants) }}</span>
                    <span class="arrow">▸</span>
                </h2>
            </div>
            <div class="section-content" id="participants">
                <div class="participants-grid">
                    @foreach ($event->participants as $participant)
                        @php
                            $initials = '';
                            $nameParts = explode(' ', $participant->name);
                            if (count($nameParts) >= 2) {
                                $initials = strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1));
                            } else {
                                $initials = strtoupper(substr($participant->name, 0, 2));
                            }
                            $bgColor = '#' . substr(md5($participant->name), 0, 6);
                            $typeClass = $participant->is_team ? 'team' : 'athlete';
                        @endphp

                        <a href="{{ route($participant->is_team ? 'team' : 'athlete', ['id' => $participant->id]) }}" class="participant-card {{ $typeClass }}">
                            <div class="participant-avatar" style="background-color: {{ $bgColor }};">
                                {{ $initials }}
                            </div>
                            <div class="participant-info">
                                <div class="participant-place @if($participant->place == 1) gold @elseif($participant->place == 2) silver @elseif($participant->place == 3) bronze @endif">
                                    #{{ $participant->place }}
                                </div>
                                <div class="participant-name">{{ $participant->name }}</div>
                                <div class="participant-meta">
                                    <span class="participant-id">{{ $participant->id }}</span>
                                    <span class="participant-type">{{ $participant->is_team ? 'Team' : 'Athlete' }}</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Секция Matches с возможностью сворачивания -->
        <div class="section">
            <div class="section-header" onclick="toggleSection('matches')">
                <h2>
                    <span class="section-title">Matches</span>
                    <span class="badge">{{ count($event->matches) }}</span>
                    <span class="arrow">▸</span>
                </h2>
            </div>
            <div class="section-content" id="matches">
                <div class="matches-list">
                    @foreach ($event->matches as $match)
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

        /* Заголовок события */
        .event-header {
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }
        .event-header h1 {
            margin: 0;
            font-size: 28px;
            color: #333;
        }

        /* Стили для сворачиваемых секций */
        .section {
            margin-bottom: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: scroll;
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
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .section-header:hover {
            background: #e9ecef;
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
            padding: 0;
            max-height: 1000px;
            overflow: scroll;
            transition: max-height 0.3s ease, padding 0.3s ease;
        }
        .section-content.collapsed {
            max-height: 0;
            padding: 0 20px;
        }

        /* Стили для участников */
        .participants-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 15px;
            padding: 20px;
        }
        .participant-card {
            display: flex;
            align-items: center;
            padding: 12px;
            border-radius: 8px;
            text-decoration: none;
            color: inherit;
            background: #fff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .participant-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .participant-card.team {
            border-left: 4px solid #4e73df;
        }
        .participant-card.athlete {
            border-left: 4px solid #1cc88a;
        }
        .participant-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            font-weight: bold;
            color: white;
            margin-right: 15px;
            flex-shrink: 0;
        }
        .participant-info {
            flex-grow: 1;
        }
        .participant-place {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 3px;
        }
        .participant-place.gold {
            color: #ffd700;
        }
        .participant-place.silver {
            color: #c0c0c0;
        }
        .participant-place.bronze {
            color: #cd7f32;
        }
        .participant-name {
            font-weight: 600;
            margin-bottom: 3px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .participant-meta {
            display: flex;
            font-size: 12px;
            color: #6c757d;
        }
        .participant-meta span {
            margin-right: 10px;
        }

        /* Стили для матчей */
        .matches-list {
            padding: 20px;
        }
        .match-card {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            margin-bottom: 10px;
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

    <script>
        function toggleSection(sectionId) {
            const section = document.getElementById(sectionId);
            const header = section.previousElementSibling;
            const arrow = header.querySelector('.arrow');

            section.classList.toggle('collapsed');

            if (section.classList.contains('collapsed')) {
                arrow.textContent = '▸';
                arrow.style.transform = 'rotate(0deg)';
            } else {
                arrow.textContent = '▾';
                arrow.style.transform = 'rotate(90deg)';
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

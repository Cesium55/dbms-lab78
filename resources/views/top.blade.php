@extends('layout')

@section('title')
    @if($is_team)
        Top Teams
    @else
        Top Athletes
    @endif
@endsection

@section('content')
    <div class="top-container">
        <div class="top-header">
            <h1>
                <i class="fas fa-trophy"></i>
                @if($is_team)
                    Top Teams
                @else
                    Top Athletes
                @endif
            </h1>

        </div>

        <div class="top-list">
            @foreach($topParticipants as $index => $participant)
                <div class="top-card">
                    <div class="top-position">
                        <div class="position-number">{{ $index + 1 }}</div>
                        @if($index < 3)
                            <div class="medal">
                                @if($index == 0)
                                    <i class="fas fa-trophy gold"></i>
                                @elseif($index == 1)
                                    <i class="fas fa-medal silver"></i>
                                @elseif($index == 2)
                                    <i class="fas fa-medal bronze"></i>
                                @endif
                            </div>
                        @endif
                    </div>

                    <div class="top-avatar">
                        @php
                            $initials = '';
                            $nameParts = explode(' ', $participant->name);
                            if (count($nameParts) >= 2) {
                                $initials = strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1));
                            } else {
                                $initials = strtoupper(substr($participant->name, 0, 2));
                            }
                            $bgColor = '#' . substr(md5($participant->name), 0, 6);
                        @endphp
                        <div class="avatar-circle" style="background-color: {{ $bgColor }};">
                            {{ $initials }}
                        </div>
                    </div>

                    <div class="top-info">
                        <div class="top-name">
                            <a href="{{ $is_team ? route('team', ['id' => $participant->id]) : route('athlete', ['id' => $participant->id]) }}">
                                {{ $participant->name }}
                            </a>
                        </div>
                        <div class="top-stats">
                            <div class="stat-item">
                                <i class="fas fa-trophy"></i>
                                <span>{{ $participant->tournaments_won }} tournaments</span>
                            </div>
                            <div class="stat-item">
                                <i class="fas fa-medal"></i>
                                <span>{{ $participant->tournaments_won }} wins</span>
                            </div>
                        </div>
                    </div>


                </div>
            @endforeach
        </div>
    </div>

    <style>
        .top-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .top-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }

        .top-header h1 {
            font-size: 28px;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #2c3e50;
        }

        .top-header h1 i {
            color: #f1c40f;
        }

        .view-switcher {
            margin-left: 20px;
        }

        .switch-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 15px;
            background-color: #4a6bdf;
            color: white;
            border-radius: 4px;
            text-decoration: none;
            font-size: 14px;
            transition: background-color 0.3s;
        }

        .switch-btn:hover {
            background-color: #3a56c0;
        }

        .top-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .top-card {
            display: grid;
            grid-template-columns: 80px 60px 1fr 150px;
            align-items: center;
            background: white;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .top-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .top-position {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 5px;
        }

        .position-number {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
        }

        .medal i {
            font-size: 20px;
        }

        .gold {
            color: #f1c40f;
        }

        .silver {
            color: #bdc3c7;
        }

        .bronze {
            color: #e67e22;
        }

        .top-avatar {
            display: flex;
            justify-content: center;
        }

        .avatar-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            font-weight: bold;
            color: white;
        }

        .top-info {
            padding: 0 15px;
        }

        .top-name {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .top-name a {
            color: #2c3e50;
            text-decoration: none;
            transition: color 0.2s;
        }

        .top-name a:hover {
            color: #4a6bdf;
        }

        .top-stats {
            display: flex;
            gap: 15px;
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 14px;
            color: #6c757d;
        }

        .stat-item i {
            color: #4a6bdf;
        }

        .top-rating {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 5px;
        }

        .rating-value {
            font-weight: bold;
            color: #2c3e50;
        }

        .progress-container {
            width: 100%;
            height: 6px;
            background-color: #f0f0f0;
            border-radius: 3px;
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            background-color: #4a6bdf;
            border-radius: 3px;
            transition: width 0.5s ease;
        }

        @media (max-width: 768px) {
            .top-card {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .top-position, .top-avatar, .top-info, .top-rating {
                grid-column: 1;
            }

            .top-position {
                flex-direction: row;
                justify-content: flex-start;
                gap: 15px;
            }

            .top-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .view-switcher {
                margin-left: 0;
                width: 100%;
            }

            .switch-btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>

    <!-- Подключаем Font Awesome для иконок -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
@endsection

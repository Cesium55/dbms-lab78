@extends('layout')

@section('title', $match->name ?? 'Match ' . $match->id)

@section('content')
    <div class="match-container">
        <!-- Заголовок матча -->
        <div class="match-header">
            <h1>{{ $match->name ?? 'Match #' . $match->id }}</h1>
            <button id="auto_rename" class="rename-btn">
                <i class="fas fa-magic"></i> Auto Rename
            </button>
        </div>

        <!-- Таблица участников -->
        <div class="participants-table">
            <div class="table-header">
                <div class="cell place">Place</div>
                <div class="cell name">Participant</div>
                <div class="cell type">Type</div>
                <div class="cell score">Score</div>
            </div>

            @foreach ($participants as $participant)
                <div class="table-row {{ $participant->place <= 3 ? 'podium-' . $participant->place : '' }}">
                    <div class="cell place">
                        @if($participant->place)
                            <span class="place-number">{{ $participant->place }}</span>
                            @if($participant->place == 1)
                                <i class="fas fa-trophy gold"></i>
                            @elseif($participant->place == 2)
                                <i class="fas fa-medal silver"></i>
                            @elseif($participant->place == 3)
                                <i class="fas fa-medal bronze"></i>
                            @endif
                        @else
                            -
                        @endif
                    </div>
                    <div class="cell name">
                        <a href="{{ route($participant->is_team ? 'team' : 'athlete', ['id' => $participant->id]) }}" class="participant-link">
                            @if($participant->is_team)
                                <i class="fas fa-users team-icon"></i>
                            @else
                                <i class="fas fa-user athlete-icon"></i>
                            @endif
                            {{ $participant->name }}
                        </a>
                    </div>
                    <div class="cell type">
                        <span class="type-badge {{ $participant->is_team ? 'team' : 'athlete' }}">
                            {{ $participant->is_team ? 'Team' : 'Player' }}
                        </span>
                    </div>
                    <div class="cell score {{ $participant->place == 1 ? 'winner-score' : '' }}">
                        {{ $participant->score ?? 0 }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <style>
        /* Основные стили */
        .match-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Шапка матча */
        .match-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }

        .match-header h1 {
            margin: 0;
            font-size: 28px;
            color: #333;
        }

        /* Кнопка авто-переименования */
        .rename-btn {
            background-color: #4a6bdf;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .rename-btn:hover {
            background-color: #3a56c0;
        }

        .rename-btn i {
            font-size: 14px;
        }

        /* Таблица участников */
        .participants-table {
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .table-header, .table-row {
            display: grid;
            grid-template-columns: 80px 1fr 100px 80px;
            align-items: center;
            padding: 12px 15px;
        }

        .table-header {
            background-color: #2c3e50;
            color: white;
            font-weight: bold;
        }

        .table-row {
            background-color: white;
            border-bottom: 1px solid #eee;
        }

        .table-row:last-child {
            border-bottom: none;
        }

        .table-row:hover {
            background-color: #f9f9f9;
        }

        /* Стили для подиумных мест */
        .podium-1 {
            background-color: #fff9e6;
        }

        .podium-2 {
            background-color: #f8f8f8;
        }

        .podium-3 {
            background-color: #f5efe6;
        }

        /* Ячейки таблицы */
        .cell {
            padding: 8px 5px;
        }

        .place {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: bold;
        }

        .place-number {
            min-width: 20px;
            text-align: center;
        }

        .name {
            font-weight: 500;
        }

        .participant-link {
            display: flex;
            align-items: center;
            gap: 10px;
            color: inherit;
            text-decoration: none;
        }

        .participant-link:hover {
            color: #4a6bdf;
        }

        .team-icon {
            color: #4a6bdf;
        }

        .athlete-icon {
            color: #2ecc71;
        }

        /* Бейджи типов */
        .type-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }

        .type-badge.team {
            background-color: #e3ecff;
            color: #4a6bdf;
        }

        .type-badge.athlete {
            background-color: #e6f7ee;
            color: #2ecc71;
        }

        /* Счет */
        .score {
            font-weight: bold;
            text-align: center;
        }

        .winner-score {
            color: #f39c12;
            font-size: 16px;
        }

        /* Иконки наград */
        .gold {
            color: #f1c40f;
        }

        .silver {
            color: #bdc3c7;
        }

        .bronze {
            color: #e67e22;
        }
    </style>

    <!-- Подключаем Font Awesome для иконок -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById("auto_rename").addEventListener("click", function() {
                // Показываем индикатор загрузки
                const btn = this;
                const originalText = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
                btn.disabled = true;

                // Отправляем запрос
                fetch('{{ route('match_auto_rename', ['match' => $match->id]) }}', {
                    method: 'POST'
                })
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    window.location.reload()
                })
                .finally(() => {
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                });
            });

            function showAlert(message, type) {
                const alert = document.createElement('div');
                alert.className = `alert alert-${type}`;
                alert.textContent = message;
                alert.style.position = 'fixed';
                alert.style.top = '20px';
                alert.style.right = '20px';
                alert.style.padding = '10px 20px';
                alert.style.borderRadius = '4px';
                alert.style.color = 'white';
                alert.style.backgroundColor = type === 'success' ? '#2ecc71' : '#e74c3c';
                alert.style.boxShadow = '0 2px 10px rgba(0,0,0,0.2)';
                alert.style.zIndex = '1000';
                alert.style.animation = 'fadeIn 0.3s';

                document.body.appendChild(alert);

                setTimeout(() => {
                    alert.style.animation = 'fadeOut 0.3s';
                    setTimeout(() => alert.remove(), 300);
                }, 3000);
            }
        });
    </script>
@endsection

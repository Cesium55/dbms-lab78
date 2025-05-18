import requests
import random
from faker import Faker
from datetime import datetime, timedelta
import time

# Конфигурация
BASE_URL = "http://localhost:8000/api/v1"  # Замените на ваш URL
SPORTS = ['Dota 2', 'Football', 'Chess', 'MMA']
CONFIG = {
    'num_athletes_per_sport': 150,  # Количество спортсменов для каждого вида спорта
    'num_teams_per_sport': 10,      # Количество команд для каждого КОМАНДНОГО вида спорта
    'num_events_per_sport': 5,      # Количество мероприятий для каждого вида спорта
    'min_matches_per_event': 6,
    'max_matches_per_event': 16,
    'request_delay': 0            # Задержка между запросами в секундах
}

fake = Faker()

# Конфигурация для каждого вида спорта
SPORT_CONFIG = {
    'Dota 2': {
        'team_size': 5,
        'is_team_sport': True,
        'event_names': ['{} Championship', '{} International', '{} Major'],
        'team_names': ['Team {}', '{} Esports', '{} Squad']
    },
    'Football': {
        'team_size': 11,
        'is_team_sport': True,
        'event_names': ['{} Cup', '{} League', '{} Championship'],
        'team_names': ['{} FC', '{} United', '{} City']
    },
    'Chess': {
        'is_team_sport': False,
        'event_names': ['{} Grand Prix', '{} Masters', '{} Open']
    },
    'MMA': {
        'is_team_sport': False,
        'event_names': ['{} Fight Night', '{} Grand Prix', '{} Championship']
    }
}

def make_request(method, endpoint, data=None):
    url = f"{BASE_URL}{endpoint}"
    try:
        if method == 'POST':
            response = requests.post(url, json=data)
        else:
            response = requests.get(url)

        if response.status_code >= 400:
            print(f"Error {response.status_code} in {method} {url}: {response.text}")
            return None

        return response.json()
    except requests.exceptions.RequestException as e:
        print(f"Request failed for {method} {url}: {e}")
        return None
    finally:
        time.sleep(CONFIG['request_delay'])

def create_sports():
    sports = {}
    for sport_name in SPORTS:
        data = {'name': sport_name}
        response = make_request('POST', '/sports', data)
        if response:
            sports[sport_name] = response['id']
            print(f"Created sport: {sport_name} (ID: {response['id']})")
    return sports

def create_athletes(sports):
    athletes = []
    for sport_name, sport_id in sports.items():
        for _ in range(CONFIG['num_athletes_per_sport']):
            data = {
                'name': fake.name(),
                'main_sport_id': sport_id,
                'date_of_birth': fake.date_of_birth(minimum_age=16, maximum_age=40).isoformat()
            }
            response = make_request('POST', '/athletes', data)
            if response:
                athletes.append({
                    'id': response['id'],
                    'sport_id': sport_id,
                    'sport_name': sport_name
                })
                print(f"Created athlete: {data['name']} (ID: {response['id']}) for {sport_name}")
    return athletes

def create_teams(sports, athletes):
    teams = []
    for sport_name, sport_id in sports.items():
        config = SPORT_CONFIG[sport_name]
        if not config['is_team_sport']:
            continue

        sport_athletes = [a for a in athletes if a['sport_name'] == sport_name]

        for _ in range(CONFIG['num_teams_per_sport']):
            if len(sport_athletes) < config['team_size']:
                print(f"Not enough athletes ({len(sport_athletes)}) to create team for {sport_name}")
                break

            team_name = random.choice(config['team_names']).format(fake.city())
            data = {
                'name': team_name,
                'main_sport_id': sport_id
            }
            response = make_request('POST', '/teams', data)
            if not response:
                continue

            team_id = response['id']
            teams.append({
                'id': team_id,
                'sport_id': sport_id,
                'sport_name': sport_name
            })
            print(f"Created team: {team_name} (ID: {team_id}) for {sport_name}")

            # Добавляем игроков в команду
            selected_athletes = random.sample(sport_athletes, config['team_size'])
            for athlete in selected_athletes:
                data = {'athlete_id': athlete['id'], "team_id":team_id}
                response = make_request('POST', f'/teams/{team_id}/athletes', data)
                if response:
                    print(f"Added athlete {athlete['id']} to team {team_id}")
                else:
                    print(f"Failed to add athlete {athlete['id']} to team {team_id}")

    return teams

def create_events(sports):
    events = []
    for sport_name, sport_id in sports.items():
        config = SPORT_CONFIG[sport_name]

        for _ in range(CONFIG['num_events_per_sport']):
            event_name = random.choice(config['event_names']).format(sport_name)
            start_date = fake.date_time_between(start_date='-1y', end_date='+1y')
            end_date = start_date + timedelta(days=random.randint(1, 7))

            data = {
                'name': event_name,
                'description': f"Official {event_name} tournament",
                'main_sport_id': sport_id,
                'start_datetime': start_date.isoformat(),
                'finish_datetime': end_date.isoformat()
            }

            response = make_request('POST', '/events', data)
            if response:
                events.append({
                    'id': response['id'],
                    'sport_id': sport_id,
                    'sport_name': sport_name
                })
                print(f"Created event: {event_name} (ID: {response['id']}) for {sport_name}")

    return events

def create_matches(events):
    matches = []
    for event in events:
        sport_name = event['sport_name']
        num_matches = random.randint(
            CONFIG['min_matches_per_event'],
            CONFIG['max_matches_per_event']
        )

        for i in range(num_matches):
            match_date = fake.date_time_between(
                start_date='-30d',
                end_date='+30d'
            ).isoformat()

            data = {
                'name': f"Match {i+1}",
                'match_datetime': match_date,
                'main_sport_id': event['sport_id'],
                "event_id": event["id"]
            }

            response = make_request('POST', '/matches', data)
            if not response:
                continue

            match_id = response['id']
            matches.append({
                'id': match_id,

                'sport_id': event['sport_id'],
                'sport_name': sport_name
            })
            print(f"Created match: {data['name']} (ID: {match_id}) for event {event['id']}")

            data = {'match_id': match_id, 'event_id': event['id'],}
            response = make_request('POST', f'/events/{event["id"]}/matches', data)
            if response:
                print(f"Linked match {match_id} to event {event['id']}")
            else:
                print(f"Failed to link match {match_id} to event {event['id']}")

    return matches

def create_participants(events, athletes, teams):
    for event in events:
        sport_name = event['sport_name']
        config = SPORT_CONFIG[sport_name]

        if config['is_team_sport']:
            # Для командных видов спорта используем команды
            participants = [t for t in teams if t['sport_name'] == sport_name]
            is_team = True
        else:
            # Для индивидуальных - спортсменов
            participants = [a for a in athletes if a['sport_name'] == sport_name]
            is_team = False

        if not participants:
            print(f"No participants found for {sport_name} event {event['id']}")
            continue

        # Выбираем от 2 до 16 участников
        num_participants = random.randint(2, min(16, len(participants)))
        selected = random.sample(participants, num_participants)

        for i, participant in enumerate(selected):
            place = i + 1 if i < 3 else None
            data = {
                'participant_id': participant['id'],
                'is_team': is_team,
                'place': place,
                "event_id": event["id"]
            }
            response = make_request('POST', f'/events/{event["id"]}/participants', data)
            if response:
                part_type = "team" if is_team else "athlete"
                print(f"Added {part_type} {participant['id']} to event {event['id']}")
            else:
                print(f"Failed to add participant {participant['id']} to event {event['id']}")

def create_match_participants(matches, athletes, teams):
    for match in matches:
        sport_name = match['sport_name']
        config = SPORT_CONFIG[sport_name]

        # Получаем участников связанного мероприятия
        participants = []
        if config['is_team_sport']:
            # Для командных видов спорта выбираем 2 команды
            candidates = [t for t in teams if t['sport_name'] == sport_name]
            num_participants = min(2, len(candidates))
            is_team = True
        else:
            # Для индивидуальных - от 2 до 8 участников
            candidates = [a for a in athletes if a['sport_name'] == sport_name]
            num_participants = 2
            is_team = False

        if not candidates:
            print(f"No candidates found for {sport_name} match {match['id']}")
            continue

        selected = random.sample(candidates, num_participants)

        participants = [{'participant_id': participant['id'],
                         'is_team': is_team,
                         'score': round(random.uniform(0, 100), 2) if i == 0 else round(random.uniform(0, 90), 2),
                         "match_id": match["id"]} for i, participant in enumerate(selected)]

        participants.sort(key=lambda x: -x["score"])

        for i in range(len(participants)):
            place = i + 1 if i < 3 else None
            score = round(random.uniform(0, 100), 2) if i == 0 else round(random.uniform(0, 90), 2)

            data = {
                'participant_id': participants[i]['participant_id'],
                'is_team': is_team,
                'place': i+1,
                'score': participants[i]['score'],
                "match_id": match["id"]
            }
            response = make_request('POST', f'/matches/{match["id"]}/participants', data)
            if response:
                part_type = "team" if is_team else "athlete"
                print(f"Added {part_type} {participants[i]['participant_id']} to match {match['id']}")
            else:
                print(f"Failed to add participant {participants[i]['participant_id']} to match {match['id']}")

def main():
    print("=== Starting database population ===")

    print("\n=== Creating sports ===")
    sports = create_sports()

    print("\n=== Creating athletes ===")
    athletes = create_athletes(sports)

    print("\n=== Creating teams ===")
    teams = create_teams(sports, athletes)

    print("\n=== Creating events ===")
    events = create_events(sports)

    print("\n=== Creating matches ===")
    matches = create_matches(events)

    print("\n=== Creating event participants ===")
    create_participants(events, athletes, teams)

    print("\n=== Creating match participants ===")
    create_match_participants(matches, athletes, teams)

    print("\n=== Database population completed! ===")

if __name__ == "__main__":
    main()

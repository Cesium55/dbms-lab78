

CREATE TABLE athletes(
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    main_sport_id INTEGER,
    date_of_birth DATE CHECK (date_of_birth IS NULL OR date_of_birth < CURRENT_DATE)
);

CREATE TABLE sports(
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE CHECK (char_length(name) > 1)
);


CREATE TABLE events(
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL CHECK (char_length(name) > 1),
    description TEXT NOT NULL DEFAULT '',
    main_sport_id INTEGER,
    start_datetime TIMESTAMP NOT NULL,
    finish_datetime TIMESTAMP NOT NULL,
    FOREIGN KEY (main_sport_id) REFERENCES sports(id),
    CHECK (finish_datetime >= start_datetime)
);


CREATE TABLE matches(
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) CHECK (char_length(name) > 1),
    match_datetime TIMESTAMP NOT NULL,
    main_sport_id INTEGER NOT NULL,
    FOREIGN KEY (main_sport_id) REFERENCES sports(id)
);

CREATE TABLE match_participant(
    match_id INT NOT NULL,
    participant_id INT NOT NULL,
    is_team BOOLEAN NOT NULL,
    place INT CHECK (place IS NULL OR place >= 1),
    score NUMERIC,
    PRIMARY KEY (match_id, participant_id, is_team)
);


CREATE TABLE event_match(
    event_id INT NOT NULL,
    match_id INT NOT NULL,

    PRIMARY KEY (event_id, match_id),
    FOREIGN KEY (match_id) REFERENCES matches(id),
    FOREIGN KEY (event_id) REFERENCES events(id)

);


CREATE TABLE event_participant(
    event_id INT NOT NULL,
    participant_id INT NOT NULL,
    is_team BOOLEAN NOT NULL,
    place INT CHECK (place IS NULL OR place >= 1),
    PRIMARY KEY (event_id, participant_id, is_team)
);

CREATE TABLE teams(
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL CHECK (char_length(name) > 1),
    main_sport_id INTEGER NOT NULL,
    FOREIGN KEY (main_sport_id) REFERENCES sports(id)
);


CREATE TABLE team_athlete(
    team_id INT NOT NULL,
    athlete_id INT NOT NULL,
    PRIMARY KEY (team_id, athlete_id),
    FOREIGN KEY (team_id) REFERENCES teams(id),
    FOREIGN KEY (athlete_id) REFERENCES athletes(id)
);




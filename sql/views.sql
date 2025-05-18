CREATE OR REPLACE VIEW top_10_athletes AS
WITH athlete_wins AS (
    SELECT
        a.id,
        a.name,
        COUNT(DISTINCT ep.event_id) AS tournaments_won
    FROM
        athletes a
    JOIN
        event_participant ep ON a.id = ep.participant_id AND ep.is_team = FALSE
    WHERE
        ep.place = 1
    GROUP BY
        a.id, a.name
)
SELECT
    id,
    name,
    tournaments_won
FROM
    athlete_wins
ORDER BY
    tournaments_won DESC, name
LIMIT 10;


CREATE OR REPLACE VIEW top_10_teams AS
WITH team_wins AS (
    SELECT
        t.id,
        t.name,
        COUNT(DISTINCT ep.event_id) AS tournaments_won
    FROM
        teams t
    JOIN
        event_participant ep ON t.id = ep.participant_id AND ep.is_team = TRUE
    WHERE
        ep.place = 1
    GROUP BY
        t.id, t.name
)
SELECT
    id,
    name,
    tournaments_won
FROM
    team_wins
ORDER BY
    tournaments_won DESC, name
LIMIT 10;

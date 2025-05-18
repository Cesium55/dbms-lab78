CREATE OR REPLACE FUNCTION get_event_participants(event_id_param INT)
RETURNS TABLE (
    place INT,
    id INT,
    name VARCHAR(255),
    is_team BOOLEAN
) AS $$
BEGIN
    RETURN QUERY
    (
        SELECT
            ep.place, a.id, a.name, ep.is_team
        FROM
            event_participant ep
            JOIN athletes a ON ep.participant_id = a.id
        WHERE
            ep.event_id = event_id_param AND NOT ep.is_team

        UNION

        SELECT
            ep.place, t.id, t.name, ep.is_team
        FROM
            event_participant ep
            JOIN teams t ON ep.participant_id = t.id
        WHERE
            ep.event_id = event_id_param AND ep.is_team
    )
    ORDER BY place;
END;
$$ LANGUAGE plpgsql;



CREATE OR REPLACE FUNCTION get_match_participants(match_id_param INT)
RETURNS TABLE (
    place INT,
    id INT,
    name VARCHAR(255),
    score numeric,
    is_team BOOLEAN
) AS $$
BEGIN
    RETURN QUERY
    (
        SELECT
            mp.place, a.id, a.name, mp.score, mp.is_team
        FROM
            match_participant mp
            JOIN athletes a ON mp.participant_id = a.id
        WHERE
            mp.match_id = match_id_param AND NOT mp.is_team

        UNION

        SELECT
            mp.place, t.id, t.name, mp.score, mp.is_team
        FROM
            match_participant mp
            JOIN teams t ON mp.participant_id = t.id
        WHERE
            mp.match_id = match_id_param AND mp.is_team
    )
    ORDER BY place;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION get_event_matches(event_id_param INT)
RETURNS TABLE (
    id INT,
    name VARCHAR(255),
    match_datetime TIMESTAMP,
    main_sport_id INT
) AS $$
BEGIN
    RETURN QUERY
    SELECT * FROM matches m
    WHERE EXISTS(
        SELECT * FROM event_match em
        WHERE m.id = em.match_id AND event_id_param=em.event_id
    );
END;
$$ LANGUAGE plpgsql;




CREATE OR REPLACE PROCEDURE match_auto_rename(match_id INT)
LANGUAGE plpgsql
AS $$
DECLARE
    new_name TEXT := '';
    participant_name TEXT;
    participant_count INT := 0;
    max_participants INT := 5;
    total_participants INT;
BEGIN
    FOR participant_name IN
        SELECT a.name
        FROM match_participant mp
        JOIN athletes a ON mp.participant_id = a.id
        WHERE mp.match_id = match_auto_rename.match_id AND mp.is_team = FALSE
        LIMIT max_participants
    LOOP
        IF new_name <> '' THEN
            new_name := new_name || ' - ';
        END IF;
        new_name := new_name || participant_name;
        participant_count := participant_count + 1;
    END LOOP;

    FOR participant_name IN
        SELECT t.name
        FROM match_participant mp
        JOIN teams t ON mp.participant_id = t.id
        WHERE mp.match_id = match_auto_rename.match_id AND mp.is_team = TRUE
        LIMIT (max_participants - participant_count)
    LOOP
        IF new_name <> '' THEN
            new_name := new_name || ' - ';
        END IF;
        new_name := new_name || participant_name;
        participant_count := participant_count + 1;
    END LOOP;

    SELECT COUNT(*) INTO total_participants
    FROM match_participant
    WHERE match_participant.match_id = match_auto_rename.match_id;

    IF participant_count < total_participants THEN
        new_name := new_name || ' - и другие...';
    END IF;

    IF new_name <> '' THEN
        UPDATE matches
        SET name = new_name
        WHERE id = match_auto_rename.match_id;
    END IF;
END;
$$;

CREATE OR REPLACE PROCEDURE event_auto_add_participants()
LANGUAGE plpgsql
AS $$
BEGIN
    INSERT INTO event_participant (event_id, participant_id, is_team)
    SELECT DISTINCT em.event_id, mp.participant_id, FALSE
    FROM event_match em
    JOIN match_participant mp ON em.match_id = mp.match_id
    WHERE mp.is_team = FALSE
      AND NOT EXISTS (
          SELECT 1
          FROM event_participant ep
          WHERE ep.event_id = em.event_id
            AND ep.participant_id = mp.participant_id
            AND ep.is_team = FALSE
      );

    INSERT INTO event_participant (event_id, participant_id, is_team)
    SELECT DISTINCT em.event_id, mp.participant_id, TRUE
    FROM event_match em
    JOIN match_participant mp ON em.match_id = mp.match_id
    WHERE mp.is_team = TRUE
      AND NOT EXISTS (
          SELECT 1
          FROM event_participant ep
          WHERE ep.event_id = em.event_id
            AND ep.participant_id = mp.participant_id
            AND ep.is_team = TRUE
      );

    UPDATE event_participant ep
    SET place = NULL
    FROM (
        SELECT
            em.event_id,
            mp.participant_id,
            mp.is_team,
            MIN(mp.place) as min_place
        FROM event_match em
        JOIN match_participant mp ON em.match_id = mp.match_id
        WHERE mp.place IS NOT NULL
        GROUP BY em.event_id, mp.participant_id, mp.is_team
    ) subquery
    WHERE ep.event_id = subquery.event_id
      AND ep.participant_id = subquery.participant_id
      AND ep.is_team = subquery.is_team
      AND ep.place IS NULL;
END;
$$;


CREATE OR REPLACE PROCEDURE delete_void_sport(sport_id INTEGER)
LANGUAGE plpgsql
AS $$
DECLARE
    ref_count INTEGER;
BEGIN
    SELECT COUNT(*) INTO ref_count FROM athletes WHERE main_sport_id = sport_id;
    IF ref_count > 0 THEN
        RAISE EXCEPTION 'Cannot delete sport % - it has % athlete references', sport_id, ref_count;
    END IF;

    SELECT COUNT(*) INTO ref_count FROM teams WHERE main_sport_id = sport_id;
    IF ref_count > 0 THEN
        RAISE EXCEPTION 'Cannot delete sport % - it has % team references', sport_id, ref_count;
    END IF;

    SELECT COUNT(*) INTO ref_count FROM events WHERE main_sport_id = sport_id;
    IF ref_count > 0 THEN
        RAISE EXCEPTION 'Cannot delete sport % - it has % event references', sport_id, ref_count;
    END IF;

    SELECT COUNT(*) INTO ref_count FROM matches WHERE main_sport_id = sport_id;
    IF ref_count > 0 THEN
        RAISE EXCEPTION 'Cannot delete sport % - it has % match references', sport_id, ref_count;
    END IF;

    DELETE FROM sports WHERE id = sport_id;

    RAISE NOTICE 'Sport % successfully deleted', sport_id;
END;
$$;

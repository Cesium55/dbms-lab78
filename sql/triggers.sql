
CREATE OR REPLACE FUNCTION validate_match_participant()
RETURNS TRIGGER AS $$
BEGIN

    IF NOT EXISTS (SELECT 1 FROM matches WHERE id = NEW.match_id) THEN
        RAISE EXCEPTION 'Match with id % does not exist', NEW.match_id;
    END IF;

    IF NEW.is_team THEN

        IF NOT EXISTS (SELECT 1 FROM teams WHERE id = NEW.participant_id) THEN
            RAISE EXCEPTION 'Team with id % does not exist', NEW.participant_id;
        END IF;
    ELSE

        IF NOT EXISTS (SELECT 1 FROM athletes WHERE id = NEW.participant_id) THEN
            RAISE EXCEPTION 'Athlete with id % does not exist', NEW.participant_id;
        END IF;
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;


CREATE OR REPLACE TRIGGER match_participant_validation
BEFORE INSERT OR UPDATE ON match_participant
FOR EACH ROW EXECUTE FUNCTION validate_match_participant();


CREATE OR REPLACE FUNCTION validate_event_participant()
RETURNS TRIGGER AS $$
BEGIN

    IF NOT EXISTS (SELECT 1 FROM events WHERE id = NEW.event_id) THEN
        RAISE EXCEPTION 'Event with id % does not exist', NEW.event_id;
    END IF;

    IF NEW.is_team THEN

        IF NOT EXISTS (SELECT 1 FROM teams WHERE id = NEW.participant_id) THEN
            RAISE EXCEPTION 'Team with id % does not exist', NEW.participant_id;
        END IF;
    ELSE

        IF NOT EXISTS (SELECT 1 FROM athletes WHERE id = NEW.participant_id) THEN
            RAISE EXCEPTION 'Athlete with id % does not exist', NEW.participant_id;
        END IF;
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;


CREATE OR REPLACE TRIGGER event_participant_validation
BEFORE INSERT OR UPDATE ON event_participant
FOR EACH ROW EXECUTE FUNCTION validate_event_participant();


CREATE OR REPLACE FUNCTION prevent_sport_deletion()
RETURNS TRIGGER AS $$
DECLARE
    ref_count INTEGER;
BEGIN

    SELECT COUNT(*) INTO ref_count FROM athletes WHERE main_sport_id = OLD.id;
    IF ref_count > 0 THEN
        RAISE EXCEPTION 'Cannot delete sport % - it has % athlete(s) associated', OLD.id, ref_count;
    END IF;


    SELECT COUNT(*) INTO ref_count FROM teams WHERE main_sport_id = OLD.id;
    IF ref_count > 0 THEN
        RAISE EXCEPTION 'Cannot delete sport % - it has % team(s) associated', OLD.id, ref_count;
    END IF;


    SELECT COUNT(*) INTO ref_count FROM events WHERE main_sport_id = OLD.id;
    IF ref_count > 0 THEN
        RAISE EXCEPTION 'Cannot delete sport % - it has % event(s) associated', OLD.id, ref_count;
    END IF;


    SELECT COUNT(*) INTO ref_count FROM matches WHERE main_sport_id = OLD.id;
    IF ref_count > 0 THEN
        RAISE EXCEPTION 'Cannot delete sport % - it has % match(es) associated', OLD.id, ref_count;
    END IF;


    RETURN OLD;
END;
$$ LANGUAGE plpgsql;


CREATE OR REPLACE TRIGGER check_sport_references_before_delete
BEFORE DELETE ON sports
FOR EACH ROW EXECUTE FUNCTION prevent_sport_deletion();

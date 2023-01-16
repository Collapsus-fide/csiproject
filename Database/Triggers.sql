CREATE OR REPLACE FUNCTION check_date_proposition()
    RETURNS TRIGGER AS $$
DECLARE
    dateDepot DATE;
    dateDeVente DATE;
BEGIN
    SELECT dateDepot,dateDeVente INTO  dateDepot, dateDeVente
    FROM offrevoiture
    WHERE Immatriculation = NEW.idOffreVoiture;

    IF NEW.dateProposition <= dateDepot OR
       NEW.dateProposition > dateDeVente THEN
        RAISE EXCEPTION 'La date de proposition doit être supérieure à la date de dépôt de OffreVoiture correspondante et inférieure ou égale à la date de vente de OffreVoiture correspondante';
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER dateProposition_check
    BEFORE INSERT OR UPDATE ON PropositionAchat
    FOR EACH ROW
EXECUTE PROCEDURE check_date_proposition();
create type eoffrevoiture as enum ('disponible', 'expirer', 'vendu');


create type epropositionachat as enum ('soumis', 'accepter', 'refuser');


create type etypetransmission as enum ('Automatic', 'Manual', 'Semi-Auto', 'Other.1');


create type etypecarburand as enum ('Diesel', 'Electric', 'Hybrid', 'Petrol', 'Other');


create type ecategorievehicule as enum ('1', '2', '3', '4');


create table compte
(
    idcompte       integer generated always as identity
        primary key,
    nomutilisateur varchar(255),
    motdepasse     varchar(255),
    email          varchar(255),
    adresse        varchar(255),
    tel            varchar(255)
);



create table garage
(
    prenomgerant varchar(255),
    nomgerant    varchar(255),
    nomgarage    varchar(255),
    numsiret     varchar(255),
    idcompte     integer not null
        primary key
        references compte
);



create table client
(
    prenom   varchar(255),
    nom      varchar(255),
    idcompte integer not null
        primary key
        references compte
);


create table offrevoiturearchivage
(
    immatriculation         varchar(255)                                 not null,
    datedepot               date,
    datedevente             date,
    prixvente               numeric
        constraint prixvente_check
            check (prixvente >= (0)::numeric),
    prixpredit              numeric
        constraint prixpredit_check
            check (prixpredit >= (0)::numeric),
    prixfinal               numeric,
    commentaireprix         varchar(255) default NULL::character varying not null,
    imagev                  bytea                                        not null,
    marquevehicule          varchar(255),
    modelvehicule           varchar(255),
    anneevehicule           integer,
    typetransmission        etypetransmission,
    mileagevehicule         integer,
    typecarburant           etypecarburand,
    taxe                    numeric,
    autonomie               integer,
    taillemoteur            integer,
    idgarage                integer
        references garage,
    idoffrevoiturearchivage serial
        primary key,
    categorie               ecategorievehicule                           not null,
    constraint datedevente_check
        check ((datedevente >= datedepot) AND (datedevente <= CURRENT_DATE)),
    constraint prixfinal_check
        check ((prixfinal >= (0)::numeric) AND (prixfinal <= prixvente))
);


create table offrevoiture
(
    immatriculation    varchar(255)                                    not null
        constraint immatriculation_unique
            unique,
    "dateDepot"        date          default CURRENT_DATE,
    "dateDeVente"      date,
    "prixVente"        double precision                                not null
        constraint prixvente_check
            check ("prixVente" > ((0)::numeric)::double precision),
    "prixPredit"       numeric
        constraint prixpredit_check
            check ("prixPredit" > (0)::numeric),
    "prixFinal"        numeric,
    "commentairePrix"  varchar(255)  default 'None'::character varying not null,
    status             eoffrevoiture default 'disponible'::eoffrevoiture,
    "imageV"           varchar       default ''::character varying     not null,
    "dateExpiration"   date          default (CURRENT_DATE + 5),
    "marqueVehicule"   varchar(255),
    "modelVehicule"    varchar(255),
    "anneeVehicule"    integer,
    "typeTransmission" etypetransmission,
    "mileageVehicule"  integer,
    "typeCarburant"    etypecarburand,
    taxe               numeric,
    autonomie          integer,
    "tailleMoteur"     integer,
    idgarage           integer
        references garage,
    categorie          ecategorievehicule,
    idoffrevoiture     serial
        primary key,
    constraint dateexpiration_check
        check ("dateExpiration" > "dateDepot"),
    constraint prixfinal_check
        check (("prixFinal")::double precision <= "prixVente"),
    constraint datedevente_check
        check (("dateDeVente" >= "dateDepot") AND ("dateDeVente" <= "dateExpiration"))
);

create table propositionachat
(
    idpropositionachat       integer generated always as identity
        constraint propositionachat_idpropositionachat_uindex
            primary key,
    montant                  numeric,
    dateproposition          date              default CURRENT_DATE,
    status                   epropositionachat default 'soumis'::epropositionachat,
    idclient                 integer
        references client,
    idoffrevoiture           integer
        constraint fk_proposition_offre
            references offrevoiture,
    idvoffrevoiturearchivage integer
        constraint fk_prop_idoffrearchivage
            references offrevoiturearchivage
);

create function check_date_proposition() returns trigger
    language plpgsql
as
$$
DECLARE
    dateDepot DATE;
    dateDeVente DATE;
BEGIN
    SELECT datedepot,dateDeVente INTO  dateDepot, dateDeVente
    FROM offrevoiture
    WHERE offrevoiture.idoffrevoiture = NEW.idOffreVoiture;

    IF NEW.dateProposition <= dateDepot OR
       NEW.dateProposition > dateDeVente THEN
        RAISE EXCEPTION 'La date de proposition doit être supérieure à la date de dépôt de OffreVoiture correspondante et inférieure ou égale à la date de vente de OffreVoiture correspondante';
    END IF;

    RETURN NEW;
END;
$$;

create trigger dateproposition_check
    before insert or update
    on propositionachat
    for each row
execute procedure check_date_proposition();


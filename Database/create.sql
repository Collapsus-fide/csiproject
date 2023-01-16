create table compte
(
    idcompte       integer not null
        primary key,
    nomutilisateur varchar(255),
    motdepasse     varchar(255),
    email          varchar(255),
    adresse        varchar(255),
    tel            varchar(255)
);

alter table compte
    owner to postgres;

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

alter table garage
    owner to postgres;

create table client
(
    prenom   varchar(255),
    nom      varchar(255),
    idcompte integer not null
        primary key
        references compte
);

alter table client
    owner to postgres;

create table offrevoiturearchivage
(
    immatriculation  varchar(255)                                 not null
        primary key,
    datedepot        date,
    datedevente      date,
    prixvente        numeric
        constraint prixvente_check
            check (prixvente <= (0)::numeric),
    prixpredit       numeric
        constraint prixpredit_check
            check (prixpredit <= (0)::numeric),
    prixfinal        numeric,
    commentaireprix  varchar(255) default NULL::character varying not null,
    imagev           bytea                                        not null,
    marquevéhicule   varchar(255),
    modelvéhicule    varchar(255),
    anneevéhicule    integer,
    typetransmission etypetransmission,
    mileagevéhicule  integer,
    typecarburand    etypecarburand,
    taxe             numeric,
    autonomie        integer,
    taillemoteur     integer,
    idgarage         integer
        references garage,
    constraint datedevente_check
        check ((datedevente >= datedepot) AND (datedevente <= CURRENT_DATE)),
    constraint prixfinal_check
        check ((prixfinal >= (0)::numeric) AND (prixfinal <= prixvente))
);

alter table offrevoiturearchivage
    owner to postgres;

create table offrevoiture
(
    immatriculation  varchar(255) not null
        primary key,
    datedepot        date,
    datedevente      date         not null,
    prixvente        numeric      not null
        constraint prixvente_check
            check (prixvente > (0)::numeric),
    prixpredit       numeric
        constraint prixpredit_check
            check (prixpredit > (0)::numeric),
    prixfinal        numeric      not null,
    commentaireprix  varchar(255) not null,
    status           eoffrevoiture default 'disponible'::eoffrevoiture,
    imagev           bytea        not null,
    dateexpiration   date,
    marquevéhicule   varchar(255),
    modelvéhicule    varchar(255),
    anneevéhicule    integer,
    typetransmission etypetransmission,
    mileagevéhicule  integer,
    typecarburand    etypecarburand,
    taxe             numeric,
    autonomie        integer,
    taillemoteur     integer,
    idgarage         integer
        references garage,
    constraint datedevente_check
        check ((datedevente > datedepot) AND (datedevente <= dateexpiration)),
    constraint prixfinal_check
        check (prixfinal <= prixvente),
    constraint dateexpiration_check
        check (dateexpiration > datedepot)
);

alter table offrevoiture
    owner to postgres;

create table propositionachat
(
    idpropositionachat      integer not null
        primary key,
    montant                 numeric,
    dateproposition         date,
    status                  epropositionachat default 'soumis'::epropositionachat,
    idclient                integer
        references client,
    idoffrevoiture          varchar(255)
        references offrevoiture
        constraint propositionachat_idoffrevoiture_fkey1
            references offrevoiture,
    idoffrevoiturearchivage varchar(255)
        references offrevoiturearchivage
);

alter table propositionachat
    owner to postgres;

create trigger dateproposition_check
    before insert or update
    on propositionachat
    for each row
execute procedure check_date_proposition();


create table 3d_printers
(
    id        int auto_increment,
    cn        text not null comment 'Common name',
    dashboard text not null comment 'Dashboard URL',
    constraint 3d_printers_id_uindex
        unique (id)
);

alter table 3d_printers
    add primary key (id);

create table strings
(
    id       text not null,
    value    text null,
    category text null
);



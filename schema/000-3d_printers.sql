create table labs.`3d_printers`
(
    id        int auto_increment,
    cn        text            not null comment 'Common name',
    dashboard text            not null comment 'Dashboard URL',
    `order`   int default 100 null,
    constraint `3d_printers_id_uindex`
        unique (id)
);

alter table labs.`3d_printers`
    add primary key (id);


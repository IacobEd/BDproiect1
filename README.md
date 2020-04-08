# BDproiect1
create table product
(
    id          int  not null
        primary key,
    productName text not null,
    price       int  not null,
    image       text not null,
    producer    text not null,
    memory      int  not null,
    color       text not null
);

create table product_change
(
    productName    varchar(30) not null,
    price          int         not null,
    image          text        null,
    producer       text        not null,
    memory         int         not null,
    color          text        not null,
    operation      varchar(30) not null,
    operation_time date        not null
);

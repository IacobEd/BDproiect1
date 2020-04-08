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

insert into product (id, productName, price, image, producer, memory, color) VALUES (1, 'Iphone Xr', 3200, 'IphoneXr.jpg', 'Apple',
64, 'Black'), (2, 'Iphone 11', 3700, 'Iphone11.jpg', 'Apple', 64, 'Black'), (3, 'Iphone 11 Pro', 5200, 'Iphone11Pro.jpg', 'Apple',
 64, 'Space Grey'), (4, 'Iphone 11 Pro Max', 5700, 'Iphone11ProMax.jpg', 'Apple', 64, 'Space Grey')

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

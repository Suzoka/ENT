create table if not exists `etudiants` (
    `id` int primary key not null auto_increment,
    `username` varchar(250) not null unique,
    `password` varchar(255) not null,
    `email` varchar(250) not null unique,
    `prenom` varchar(255) not null,
    `nom` varchar(255) not null,
    `date_naissance` date not null,
    `numEtud` int unique,
    `statut` text not null,
    `role` int not null,
);

create table if not exists `groupes` (
    `id_groupe` int primary key not null auto_increment,
    `nom_groupe` varchar(255) not null,
    `ext_id_classe` int not null
);

create table if not exists `classes` (
    `id_classe` int primary key not null auto_increment,
    `nom_classe` varchar(255) not null
);

create table if not exists `promotions` (
    `id_promo` int primary key not null auto_increment,
    `ext_id_groupe` int not null,
    `ext_id_usr` int not null
);

create table if not exists `messages` (
    `id_message` int primary key not null auto_increment,
    `ext_id_sender` int not null,
    `ext_id_receiver` int not null,
    `message` text not null,
    `date` datetime not null    
);
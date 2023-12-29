create table if not exists `etudiants` (
    `id` int primary key not null auto_increment,
    `username` varchar(250) not null unique,
    `password` varchar(255) not null,
    `email` varchar(250) not null unique,
    `prenom` varchar(255) not null,
    `nom` varchar(255) not null,
    `date_naissance` date not null,
    `numEtud` int not null unique,
    `statut` text not null
);

create table if not exists `enseignants` (
    `id` int primary key not null auto_increment,
    `username` varchar(250) not null unique,
    `password` varchar(255) not null,
    `email` varchar(250) not null unique,
    `prenom` varchar(255) not null,
    `nom` varchar(255) not null,
    `date_naissance` date not null,
    `statut` text not null
);
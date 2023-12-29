create table if not exists `etudiant` (
    `id` int(11) primary key not null auto_increment,
    `username` varchar(255) not null,
    `password` varchar(255) not null,
    `email` varchar(255) not null,
    `prenom` varchar(255) not null,
    `nom` varchar(255) not null,
    `date_naissance` date not null,
    `numEtud` int(11) not null,
    `statut` text not null
);

create table if not exists `enseignants` (
    `id` int(11) primary key not null auto_increment,
    `username` varchar(255) not null,
    `password` varchar(255) not null,
    `email` varchar(255) not null,
    `prenom` varchar(255) not null,
    `nom` varchar(255) not null,
    `date_naissance` date not null,
    `statut` text not null
);
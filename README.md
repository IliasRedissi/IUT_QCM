Sondage
=======


###Installation
Intall composer
Run
    
    composer update

###Database
    drop table if exists Answer;
    
    drop table if exists Question;
    
    drop table if exists User;
    
    drop table if exists UserReponse;
    
    /*==============================================================*/
    /* Table : Answer                                               */
    /*==============================================================*/
    create table Answer
    (
       idAnswer             int not null,
       answer               varchar(254),
       idQuestion           int not null,
       primary key (idAnswer)
    );
    
    /*==============================================================*/
    /* Table : Question                                             */
    /*==============================================================*/
    create table Question
    (
       idQuestion           int not null,
       idUser               int not null,
       question             varchar(254),
       primary key (idQuestion)
    );
    
    /*==============================================================*/
    /* Table : User                                                 */
    /*==============================================================*/
    create table User
    (
       idUser               int not null,
       email                varchar(254),
       password             varchar(254),
       primary key (idUser)
    );
    
    /*==============================================================*/
    /* Table : UserReponse                                          */
    /*==============================================================*/
    create table UserReponse
    (
       idAnswer             int not null,
       idUser               int not null,
       primary key (idAnswer, idUser)
    );
    
    alter table Question add constraint FK_CREATE foreign key (idUser)
          references User (idUser) on delete restrict on update restrict;
    
    alter table Answer add constraint FK_GOT foreign key (idQuestion)
          references Question (idQuestion) on delete restrict on update restrict;
    
    alter table UserReponse add constraint FK_ANSWER foreign key (idAnswer)
          references Answer (idAnswer) on delete restrict on update restrict;
    
    alter table UserReponse add constraint FK_ANSWER_USER foreign key (idUser)
          references User (idUser) on delete restrict on update restrict;

###Run Server
    php -S 0.0.0.0:8080 -t public public/index.php
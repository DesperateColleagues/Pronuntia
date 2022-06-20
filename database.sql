CREATE DATABASE IF NOT EXISTS yii2basic;
USE yii2basic;

CREATE TABLE IF NOT EXISTS logopedista (
  nome CHAR(60) NOT NULL,
  cognome CHAR(60) NOT NULL,
  dataNascita DATE NOT NULL,
  email CHAR(255) NOT NULL PRIMARY KEY,
  passwordD CHAR(255) NOT NULL,
  authKey CHAR(50) DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS caregiver (
  nome CHAR(60) NOT NULL,
  cognome CHAR(60) NOT NULL,
  dataNascita DATE NOT NULL,
  email CHAR(255) NOT NULL PRIMARY KEY,
  passwordD CHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS utente (
  username CHAR(60) PRIMARY KEY,
  nome CHAR(60) NOT NULL,
  cognome CHAR(60) NOT NULL,
  dataNascita DATE NOT NULL,
  email CHAR(255) NOT NULL,
  passwordD CHAR(255) NOT NULL,
  logopedista CHAR(255) NOT NULL,
  caregiver CHAR(255) NULL DEFAULT NULL,
  FOREIGN KEY (logopedista) REFERENCES logopedista(email),
  FOREIGN KEY (caregiver) REFERENCES caregiver(email)
);

CREATE TABLE IF NOT EXISTS diagnosi (
  id VARCHAR(23),
  mediaFile BLOB,
  path VARCHAR(255),
  PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS appuntamento (
  dataAppuntamento DATE NOT NULL,
  oraAppuntamento TIME NOT NULL,
  logopedista CHAR(255) NOT NULL,
  utente CHAR(255) NULL,
  caregiver CHAR(255) NULL,
  diagnosi VARCHAR(23),
  PRIMARY KEY (dataAppuntamento,oraAppuntamento,logopedista),
  FOREIGN KEY (logopedista) REFERENCES logopedista(email),
  FOREIGN KEY (caregiver) REFERENCES caregiver(email),
  FOREIGN KEY (utente) REFERENCES utente(username),
  FOREIGN KEY (diagnosi) REFERENCES diagnosi(id)
);

CREATE TABLE IF NOT EXISTS esercizio (
	nome VARCHAR(255) PRIMARY KEY,
    path VARCHAR(255),
    tipologia VARCHAR(255) NOT NULL,
    testo TEXT,
    logopedista CHAR(255) NOT NULL,
	FOREIGN KEY (logopedista) REFERENCES logopedista(email)
);



SELECT * FROM logopedista;
SELECT * FROM caregiver;
SELECT * FROM utente;

/*DROP TABLE utente;
DROP TABLE caregiver;
DROP TABLE logopedista;*/
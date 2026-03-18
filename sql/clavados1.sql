CREATE DATABASE clavados;

USE clavados;

CREATE TABLE Cities (
	CityId int AUTO_INCREMENT NOT NULL ,
	CountryID smallint NOT NULL ,
	RegionID smallint NOT NULL ,
	City varchar (45) NOT NULL ,
	Latitude float NOT NULL ,
	Longitude float NOT NULL ,
	TimeZone varchar (10) NOT NULL ,
	DmaId smallint NULL ,
	County varchar (25) NULL ,
	Code varchar (4) NULL ,
	PRIMARY KEY(CityId),
	FULLTEXT buscador (City,TimeZone,County,Code)
	) ENGINE=InnoDB DEFAULT CHARSET=latin1;
 

CREATE TABLE Regions (
	RegionID smallint AUTO_INCREMENT NOT NULL ,
	CountryID smallint NOT NULL ,
	Region varchar (45) NOT NULL ,
	Code varchar (8) NOT NULL ,
	ADM1Code char (4) NOT NULL ,
	PRIMARY KEY(RegionID),
	FULLTEXT buscador (Region,Code)
	) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE Countries (
	CountryId smallint AUTO_INCREMENT NOT NULL ,
	Country varchar (50) NOT NULL ,
	FIPS104 varchar (2) NOT NULL ,
	ISO2 varchar (2) NOT NULL ,
	ISO3 varchar (3) NOT NULL ,
	ISON varchar (4) NOT NULL ,
	Internet varchar (2) NOT NULL ,
	Capital varchar (25) NULL ,
	MapReference varchar (50) NULL ,
	NationalitySingular varchar (35) NULL ,
	NationalityPlural varchar (35) NULL ,
	Currency varchar (30) NULL ,
	CurrencyCode varchar (3) NULL ,
	Population bigint NULL ,
	Title varchar (50) NULL ,
	Comment varchar (255) NULL ,
	PRIMARY KEY(CountryId),
	FULLTEXT buscador (Country,ISO2,ISO3,Capital,Currency)
	) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE precios (
	cod_concepto 	VARCHAR (20) NOT NULL UNIQUE,
	concepto 		VARCHAR (50) NOT NULL UNIQUE,
	valor           DECIMAL(12,2), 			
	PRIMARY KEY (cod_concepto),
	FULLTEXT buscador (cod_concepto,concepto)
	) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE usuarios (
	cod_usuario 	INT NOT NULL AUTO_INCREMENT,
	nombre 			VARCHAR (50) NOT NULL,
	sexo 			CHAR(1) NOT NULL,
	nacimiento 		DATE,
	email  			VARCHAR(50) NOT NULL UNIQUE,
	pais    		SMALLINT,
	telefono		VARCHAR(15),
	imagen			VARCHAR(80),
	administrador 	BOOLEAN,
	entrenador 		BOOLEAN,
	clavadista 		BOOLEAN,
	juez 			BOOLEAN,
	password		VARCHAR(20) NOT NULL,
	pregunta		VARCHAR(50),
	respuesta		VARCHAR(50),
	fecha_alta		DATE,
	PRIMARY KEY (cod_usuario),
	FOREIGN KEY (pais) REFERENCES Countries (CountryId) ON DELETE RESTRICT ON UPDATE RESTRICT,
	FULLTEXT buscador (nombre,email,telefono)
	) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE equipos (
	cod_equipo 		INT NOT NULL AUTO_INCREMENT,
	equipo 			VARCHAR (50) NOT NULL UNIQUE,
	pais 			SMALLINT,
	telefono		VARCHAR(15),
	representante 	VARCHAR (50),
	email			VARCHAR(50),
	nombrecorto		VARCHAR(5) UNIQUE,
	password		VARCHAR(20),
	PRIMARY KEY (cod_equipo),
	FOREIGN KEY (pais) REFERENCES Countries (CountryId) ON DELETE RESTRICT ON UPDATE RESTRICT,
	FULLTEXT buscador (equipo,representante,telefono,email,nombrecorto)
	) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE modalidades (
	cod_modalidad 	CHAR(1) NOT NULL UNIQUE,
	descripcion 	VARCHAR(15) NOT NULL UNIQUE,
	PRIMARY KEY (cod_modalidad)
	) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE categorias (
	cod_categoria 	CHAR(2) NOT NULL UNIQUE,
	descripcion 	VARCHAR(30) NOT NULL UNIQUE,
	edad_desde 		TINYINT,
	edad_hasta 		TINYINT,
	verifica_edad 	BOOLEAN,
	tipo_categoria 	CHAR(1),
	individual 		BOOLEAN,
	PRIMARY KEY (cod_categoria)
	) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE saltos (
	cod_salto 	VARCHAR(6) NOT NULL UNIQUE,
	descripcion VARCHAR(80) NOT NULL UNIQUE,
	PRIMARY KEY (cod_salto)
	) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE dificult (
	salto 		VARCHAR(6) NOT NULL UNIQUE,
	altura 		DECIMAL(5,2) NOT NULL UNIQUE,
	pos_a		DECIMAL(5,2),
	pos_b		DECIMAL(5,2),
	pos_c		DECIMAL(5,2),
	pos_d		DECIMAL(5,2),
	PRIMARY KEY (salto,altura),
	FOREIGN KEY (salto) REFERENCES saltos (cod_salto) ON DELETE RESTRICT ON UPDATE RESTRICT
	) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE series (
	categoria 	CHAR(2) NOT NULL,
	sexo 		CHAR(1) NOT NULL,
	modalidad  	CHAR(1)  NOT NULL,
	saltos_obl	TINYINT,
	limite_dif 	DECIMAL(5,2),
	saltos_lib	TINYINT,
	gruposmin	TINYINT,
	PRIMARY KEY (categoria,sexo,modalidad),
	FOREIGN KEY (categoria) REFERENCES categorias (cod_categoria) ON DELETE RESTRICT ON UPDATE RESTRICT,
	FOREIGN KEY (modalidad) REFERENCES modalidades (cod_modalidad) ON DELETE RESTRICT ON UPDATE RESTRICT
	) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE competencias (
	cod_competencia 	INT NOT NULL AUTO_INCREMENT,
	competencia 		VARCHAR (200) NOT NULL UNIQUE,
	ciudad				INT NOT NULL,
	fecha_inicia		DATE NOT NULL,
	fecha_termina		DATE NOT NULL,
	limite_inscripcion 	DATETIME,
	estado 				CHAR(1),
	valor_equipo 		DECIMAL (11,2),
	valor_clavadista	DECIMAL (11,2),
	valor_evento 		DECIMAL (11,2),
	max_eve_cobrar		TINYINT,
	premia_mayores		BOOLEAN,
	competencia2 		VARCHAR (200) NOT NULL UNIQUE,
	PRIMARY KEY (cod_competencia),
	FOREIGN KEY (ciudad) REFERENCES Cities (CityId) ON DELETE RESTRICT ON UPDATE RESTRICT,
	FULLTEXT buscador (competencia,competencia2)
	) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE competenciasu (
	competencia 		INT NOT NULL,
	usuario 			INT NOT NULL,
	administrador		BOOLEAN,
	entrenador			BOOLEAN,
	clavadista			BOOLEAN,
	juez				BOOLEAN,
	equipo 				INT,
	categoria 			CHAR(2),
	abierto 			BOOLEAN,
	estado 				CHAR(1),
	PRIMARY KEY (competencia,usuario),
	FOREIGN KEY (usuario) REFERENCES usuarios (cod_usuario) ON DELETE RESTRICT ON UPDATE RESTRICT
	) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE competenciasj (
	competencia 		INT NOT NULL,
	jornada				TINYINT NOT NULL,
	evento 				TINYINT NOT NULL,
	descripcion 		VARCHAR(100) NOT NULL,
	fecha 				DATE,
	hora 				TIME,
	modalidad 			char(1) NOT NULL,
	sorteada 			BOOLEAN,
	cant_jueces			TINYINT,
	ronda 				TINYINT,
	turno				TINYINT,
	inicio_comp 		DATETIME,
	finalizo_comp 		DATETIME,
	categorias 			VARCHAR(20) NOT NULL,
	sexos 				char(2) NOT NULL,
	cerrado				BOOLEAN,
	tiempo_estimado 	TINYINT,
	preliminar			BOOLEAN,
	preliminar_abierto 	BOOLEAN,
	ronda_mayores 		TINYINT,
	PRIMARY KEY (competencia, jornada, evento),
	FOREIGN KEY (competencia) REFERENCES competencias (cod_competencia) ON DELETE RESTRICT ON UPDATE RESTRICT,
	FOREIGN KEY (modalidad) REFERENCES modalidades (cod_modalidad) ON DELETE RESTRICT ON UPDATE RESTRICT
	) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE competenciasm (
	competencia 	INT NOT NULL,
	categoria 		char(2) NOT NULL,
	modalidad 		char(1) NOT NULL,
	marca_damas		DECIMAL(7,2),
	marca_varones	DECIMAL(7,2),
	PRIMARY KEY (competencia, categoria, modalidad),
	FOREIGN KEY (competencia) REFERENCES competencias (cod_competencia) ON DELETE RESTRICT ON UPDATE RESTRICT,
	FOREIGN KEY (categoria) REFERENCES categorias (cod_categoria) ON DELETE RESTRICT ON UPDATE RESTRICT,
	FOREIGN KEY (modalidad) REFERENCES modalidades (cod_modalidad) ON DELETE RESTRICT ON UPDATE RESTRICT
	) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE competenciasq (
	competencia 	INT NOT NULL,
	equipo 			INT NOT NULL,
	representante 	varchar(50),
	email			varchar(50),
	PRIMARY KEY (competencia, equipo),
	FOREIGN KEY (competencia) REFERENCES competencias (cod_competencia) ON DELETE RESTRICT ON UPDATE RESTRICT,
	FOREIGN KEY (equipo) REFERENCES equipos (cod_equipo) ON DELETE RESTRICT ON UPDATE RESTRICT
	) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE competenciast (
	competencia 	INT NOT NULL,
	puesto 			TINYINT  NOT NULL,
	puntos 			TINYINT,
	puntos_sinc 	TINYINT,
	PRIMARY KEY (competencia, puesto),
	FOREIGN KEY (competencia) REFERENCES competencias (cod_competencia) ON DELETE RESTRICT ON UPDATE RESTRICT
	) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE competenciasz (
	competencia 	INT NOT NULL,
	jornada 		TINYINT NOT NULL,
	evento 			TINYINT NOT NULL,
	panel 			TINYINT NOT NULL,
	ubicacion 		TINYINT NOT NULL,
	juez			INT,
	PRIMARY KEY (competencia, jornada, evento, panel, ubicacion),
	FOREIGN KEY (competencia) REFERENCES competencias (cod_competencia) ON DELETE RESTRICT ON UPDATE RESTRICT,
	FOREIGN KEY (juez) REFERENCES usuarios (cod_usuario) ON DELETE RESTRICT ON UPDATE RESTRICT
	) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE planillas (
	cod_planilla 		INT NOT NULL AUTO_INCREMENT,
	clavadista 			INT NOT NULL,
	competencia 		INT NOT NULL,
	modalidad			CHAR(1) NOT NULL,
	categoria 			CHAR(2) NOT NULL,
	sexo 				CHAR(1) NOT NULL,
	equipo 				INT NOT NULL,
	feca_hor_grab 		DATETIME,
	extraof 	 		CHAR(1),
	part_abierta 		CHAR(1),
	extraof_abierto 	CHAR(1),
	limite_dificultad	DECIMAL(6,2),
	grado_dificultad	DECIMAL(6,2),
	competidor2 		INT,
	competencia2 		INT,
	orden_salida		TINYINT,
	total				DECIMAL(6,2),
	lugar 				TINYINT,
	total_abierta		DECIMAL(6,2),
	lugar_abierta 		TINYINT,
	equipo2 			INT,
	ambos_eventos 		BOOLEAN,
	retirado			BOOLEAN,
	PRIMARY KEY (cod_planilla),
	FOREIGN KEY (clavadista) REFERENCES usuarios (cod_usuario) ON DELETE RESTRICT ON UPDATE RESTRICT,
	FOREIGN KEY (competencia) REFERENCES competencias (cod_competencia) ON DELETE RESTRICT ON UPDATE RESTRICT,
	FOREIGN KEY (modalidad) REFERENCES modalidades (cod_modalidad) ON DELETE RESTRICT ON UPDATE RESTRICT,
	FOREIGN KEY (categoria) REFERENCES categorias (cod_categoria) ON DELETE RESTRICT ON UPDATE RESTRICT,
	FOREIGN KEY (equipo) REFERENCES equipos (cod_equipo) ON DELETE RESTRICT ON UPDATE RESTRICT,
	FOREIGN KEY (competidor2) REFERENCES usuarios (cod_usuario) ON DELETE RESTRICT ON UPDATE RESTRICT,
	FOREIGN KEY (competencia2) REFERENCES competencias (cod_competencia) ON DELETE RESTRICT ON UPDATE RESTRICT,
	FOREIGN KEY (equipo2) REFERENCES equipos (cod_equipo) ON DELETE RESTRICT ON UPDATE RESTRICT
	) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE planillad (
	planilla 		INT NOT NULL,
	ronda 			TINYINT NOT NULL,
	salto 			VARCHAR(6) NOT NULL,
	posicion 		CHAR(1) NOT NULL,
	grado_dif		DECIMAL(5,2) NOT NULL,
	abierto 		CHAR(1),
	total_salto		DECIMAL(6,2),
	puntaje_salto	DECIMAL(6,2),
	acumulado		DECIMAL(6,2),
	penalizado		DECIMAL(4,2),
	bonificado		DECIMAL(4,2),
	hora_salto		DATETIME,
	calificado		BOOLEAN,
	PRIMARY KEY (planilla, ronda),
	FOREIGN KEY (planilla) REFERENCES planillas (cod_planilla) ON DELETE RESTRICT ON UPDATE RESTRICT,
	FOREIGN KEY (salto) REFERENCES saltos (cod_salto) ON DELETE RESTRICT ON UPDATE RESTRICT
	) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE planillac (
	planilla 		INT NOT NULL,
	ronda 			TINYINT NOT NULL,
	ubicacion 		TINYINT NOT NULL,
	juez			INT,
	calificacion	DECIMAL(4,2),
	PRIMARY KEY (planilla, ronda,ubicacion),
	FOREIGN KEY (planilla,ronda) REFERENCES planillad (planilla,ronda) ON DELETE RESTRICT ON UPDATE RESTRICT,
	FOREIGN KEY (juez) REFERENCES usuarios (cod_usuario) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

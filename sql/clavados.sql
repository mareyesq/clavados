CREATE DATABASE clavados;

USE clavados;

CREATE TABLE paises (
	cod_pais INT NOT NULL,
	pais VARCHAR (50) NOT NULL UNIQUE,
	alfa2	CHAR(2) UNIQUE,
	alfa3	CHAR(3) UNIQUE,
	fips	char(2),
	capital	VARCHAR(50),
	continente CHAR(2),
	PRIMARY KEY (cod_pais),
	FULLTEXT buscador (pais,alfa2,alfa3,capital,continente)
	) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE provincias (
	cod_provincia INT NOT NULL AUTO_INCREMENT,
	provincia VARCHAR (50) NOT NULL UNIQUE,
	pais INT,
	nombrecorto	VARCHAR(3) UNIQUE,
	PRIMARY KEY (cod_provincia),
	FOREIGN KEY (pais) REFERENCES paises (cod_pais) ON DELETE RESTRICT ON UPDATE RESTRICT,
	FULLTEXT buscador (provincia,nombrecorto)
	) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE ciudades (
	cod_ciudad INT NOT NULL AUTO_INCREMENT,
	ciudad VARCHAR (50) NOT NULL UNIQUE,
	provincia INT,
	pais INT,
	nombrecorto	VARCHAR(3) UNIQUE,
	PRIMARY KEY (cod_ciudad),
	FOREIGN KEY (provincia) REFERENCES provincias (cod_provincia) ON DELETE RESTRICT ON UPDATE RESTRICT,
	FOREIGN KEY (pais) REFERENCES paises (cod_pais) ON DELETE RESTRICT ON UPDATE RESTRICT,
	FULLTEXT buscador (ciudad,nombrecorto)
	) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE administradores (
	cod_administrador INT NOT NULL AUTO_INCREMENT,
	administrador VARCHAR (50) NOT NULL UNIQUE,
	representante VARCHAR (50),
	pais    INT,
	telefono	VARCHAR(15),
	email	VARCHAR(50),
	nombre_corto	VARCHAR(10) UNIQUE,
	password	VARCHAR(20),
	PRIMARY KEY (cod_administrador),
	FULLTEXT buscador (administrador,representante,telefono,email,nombre_corto)
	) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE entrenadores (
	cod_entrenador INT NOT NULL AUTO_INCREMENT,
	entrenador VARCHAR (50) NOT NULL UNIQUE,
	email	VARCHAR(50),
	pais    INT,
	telefono	VARCHAR(15),
	imagen	VARCHAR(50),
	password	VARCHAR(20),
	PRIMARY KEY (cod_entrenador),
	FULLTEXT buscador (entrenador,telefono,email)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE equipos (
	cod_equipo INT NOT NULL AUTO_INCREMENT,
	equipo VARCHAR (50) NOT NULL UNIQUE,
	pais INT,
	telefono	VARCHAR(15),
	representante VARCHAR (50),
	email	VARCHAR(50),
	nombrecorto	VARCHAR(5) UNIQUE,
	password	VARCHAR(20),
	PRIMARY KEY (cod_equipo),
	FOREIGN KEY (pais) REFERENCES paises (cod_pais) ON DELETE RESTRICT ON UPDATE RESTRICT,
	FULLTEXT buscador (equipo,representante,telefono,email,nombrecorto)
	) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE jueces (
	cod_juez INT NOT NULL AUTO_INCREMENT,
	juez VARCHAR (50) NOT NULL UNIQUE,
	origen	VARCHAR(30),
	nivel	VARCHAR(20),
	email	VARCHAR(50),
	pais    INT,
	telefono	VARCHAR(15),
	imagen	VARCHAR(50),
	password	VARCHAR(20),
	PRIMARY KEY (cod_juez),
	FULLTEXT buscador (juez,origen,nivel,email,telefono,password)
	)  ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE modalidades (
	cod_modalidad CHAR(1) NOT NULL UNIQUE,
	descripcion VARCHAR(15) NOT NULL UNIQUE,
	PRIMARY KEY (cod_modalidad)
	) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE categorias (
	cod_categoria CHAR(2) NOT NULL UNIQUE,
	descripcion VARCHAR(30) NOT NULL UNIQUE,
	edad_desde TINYINT,
	edad_hasta TINYINT,
	verifica_edad BOOLEAN,
	tipo_categoria CHAR(1),
	individual BOOLEAN,
	PRIMARY KEY (cod_categoria)
	) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE saltos (
	cod_salto VARCHAR(6) NOT NULL UNIQUE,
	descripcion VARCHAR(80) NOT NULL UNIQUE,
	PRIMARY KEY (cod_salto)
	) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE dificult (
	salto VARCHAR(6) NOT NULL UNIQUE,
	altura DECIMAL(5,2) NOT NULL UNIQUE,
	posiciona	DECIMAL(5,2),
	posicionb	DECIMAL(5,2),
	posicionc	DECIMAL(5,2),
	posiciond	DECIMAL(5,2),
	PRIMARY KEY (salto,altura),
	FOREIGN KEY (salto) REFERENCES saltos (cod_salto) ON DELETE RESTRICT ON UPDATE RESTRICT
	) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE series (
	categoria CHAR(2) NOT NULL,
	sexo CHAR(1) NOT NULL,
	modalidad  CHAR(1)  NOT NULL,
	saltos_con_limite	TINYINT,
	limite_dificultad 	DECIMAL(5,2),
	saltos_sin_limite	TINYINT,
	gruposmin	TINYINT,
	PRIMARY KEY (categoria,sexo,modalidad),
	FOREIGN KEY (categoria) REFERENCES categorias (cod_categoria) ON DELETE RESTRICT ON UPDATE RESTRICT,
	FOREIGN KEY (modalidad) REFERENCES modalidades (cod_modalidad) ON DELETE RESTRICT ON UPDATE RESTRICT
	) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE clavadistas (
	cod_clavadista INT NOT NULL AUTO_INCREMENT,
	nombre VARCHAR (80) NOT NULL,
	sexo CHAR(1) NOT NULL,
	nacimiento DATE,
	email	VARCHAR(50) UNIQUE,
	pais    INT,
	telefono	VARCHAR(15),
	equipo INT,
	entrenador INT,
	password varchar(20),
	imagen	VARCHAR(50),
	PRIMARY KEY (cod_clavadista),
	FOREIGN KEY (pais) REFERENCES paises (cod_pais) ON DELETE RESTRICT ON UPDATE RESTRICT,
	FOREIGN KEY (equipo) REFERENCES equipos (cod_equipo) ON DELETE RESTRICT ON UPDATE RESTRICT,
	FOREIGN KEY (entrenador) REFERENCES entrenadores (cod_entrenador) ON DELETE RESTRICT ON UPDATE RESTRICT,
	FULLTEXT buscador (nombre,email,sexo,telefono)
	) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE competencias (
	cod_competencia INT NOT NULL AUTO_INCREMENT,
	competencia VARCHAR (200) NOT NULL UNIQUE,
	ciudad	INT NOT NULL,
	fecha_inicia	DATE NOT NULL,
	fecha_termina	DATE NOT NULL,
	limite_inscripcion DATETIME,
	estado CHAR(1),
	administrador INT  NOT NULL,
	valor_prueba DECIMAL (11,2),
	valor_inscripcion DECIMAL (11,2),
	max_pruebas_cobrar	TINYINT,
	valor_max_pruebas DECIMAL (11,2),
	premia_mayores	BOOLEAN,
	competencia2 VARCHAR (200) NOT NULL UNIQUE,
	PRIMARY KEY (cod_competencia),
	FOREIGN KEY (ciudad) REFERENCES ciudades (cod_ciudad) ON DELETE RESTRICT ON UPDATE RESTRICT,
	FOREIGN KEY (administrador) REFERENCES administradores (cod_administrador) ON DELETE RESTRICT ON UPDATE RESTRICT,
	FULLTEXT buscador (competencia,competencia2)
	) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE competenciasj (
	competencia INT NOT NULL,
	jornada	TINYINT NOT NULL,
	evento TINYINT NOT NULL,
	descripcion VARCHAR(100) NOT NULL,
	fecha DATE,
	hora TIME,
	modalidad char(1) NOT NULL,
	sorteada BOOLEAN,
	jueces TINYINT,
	inicio BOOLEAN,
	termino BOOLEAN,
	ronda TINYINT,
	turno	TINYINT,
	inicio_competencia DATETIME,
	finalizo_competencia DATETIME,
	categorias VARCHAR(20) NOT NULL,
	sexos char(2) NOT NULL,
	cerrado	BOOLEAN,
	tiempo_estimado TINYINT,
	preliminar	BOOLEAN,
	preliminar_abierto BOOLEAN,
	ronda_mayores TINYINT,
	PRIMARY KEY (competencia, jornada, evento),
	FOREIGN KEY (competencia) REFERENCES competencias (cod_competencia) ON DELETE RESTRICT ON UPDATE RESTRICT,
	FOREIGN KEY (modalidad) REFERENCES modalidades (cod_modalidad) ON DELETE RESTRICT ON UPDATE RESTRICT
	) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE competenciasm (
	competencia INT NOT NULL,
	categoria char(2) NOT NULL,
	modalidad char(1) NOT NULL,
	marca_damas	DECIMAL(7,2),
	marca_varones	DECIMAL(7,2),
	PRIMARY KEY (competencia, categoria, modalidad),
	FOREIGN KEY (competencia) REFERENCES competencias (cod_competencia) ON DELETE RESTRICT ON UPDATE RESTRICT,
	FOREIGN KEY (categoria) REFERENCES categorias (cod_categoria) ON DELETE RESTRICT ON UPDATE RESTRICT,
	FOREIGN KEY (modalidad) REFERENCES modalidades (cod_modalidad) ON DELETE RESTRICT ON UPDATE RESTRICT
	) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE competenciasq (
	competencia INT NOT NULL,
	equipo INT NOT NULL,
	representante varchar(50),
	email	varchar(50),
	PRIMARY KEY (competencia, equipo),
	FOREIGN KEY (competencia) REFERENCES competencias (cod_competencia) ON DELETE RESTRICT ON UPDATE RESTRICT,
	FOREIGN KEY (equipo) REFERENCES equipos (cod_equipo) ON DELETE RESTRICT ON UPDATE RESTRICT
	) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE competenciast (
	competencia INT NOT NULL,
	puesto TINYINT  NOT NULL,
	puntos TINYINT,
	puntos_sinc TINYINT,
	PRIMARY KEY (competencia, puesto),
	FOREIGN KEY (competencia) REFERENCES competencias (cod_competencia) ON DELETE RESTRICT ON UPDATE RESTRICT
	) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE competenciasz (
	competencia INT NOT NULL,
	jornada TINYINT NOT NULL,
	evento TINYINT NOT NULL,
	panel TINYINT NOT NULL,
	ubicacion TINYINT NOT NULL,
	juez	INT,
	PRIMARY KEY (competencia, jornada, evento, panel, ubicacion),
	FOREIGN KEY (competencia) REFERENCES competencias (cod_competencia) ON DELETE RESTRICT ON UPDATE RESTRICT,
	FOREIGN KEY (juez) REFERENCES jueces (cod_juez) ON DELETE RESTRICT ON UPDATE RESTRICT
	) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE planillas (
	cod_planilla INT NOT NULL AUTO_INCREMENT,
	clavadista INT NOT NULL,
	competencia INT NOT NULL,
	modalidad	CHAR(1) NOT NULL,
	categoria CHAR(2) NOT NULL,
	sexo CHAR(1) NOT NULL,
	equipo INT NOT NULL,
	fecha_hora_grabacion DATETIME,
	extraoficial CHAR(1),
	parta_bierta CHAR(1),
	extraoficial_abierto CHAR(1),
	limite_dificultad	DECIMAL(6,2),
	grado_dificultad	DECIMAL(6,2),
	competidor2 INT,
	competencia2 INT,
	orden_salida	TINYINT,
	total_acumulado	DECIMAL(6,2),
	lugar TINYINT,
	total_acumulado_abierta	DECIMAL(6,2),
	lugar_abierta TINYINT,
	equipo2 INT,
	ambos_eventos BOOLEAN,
	retirado	BOOLEAN,
	PRIMARY KEY (cod_planilla),
	FOREIGN KEY (clavadista) REFERENCES clavadistas (cod_clavadista) ON DELETE RESTRICT ON UPDATE RESTRICT,
	FOREIGN KEY (competencia) REFERENCES competencias (cod_competencia) ON DELETE RESTRICT ON UPDATE RESTRICT,
	FOREIGN KEY (modalidad) REFERENCES modalidades (cod_modalidad) ON DELETE RESTRICT ON UPDATE RESTRICT,
	FOREIGN KEY (categoria) REFERENCES categorias (cod_categoria) ON DELETE RESTRICT ON UPDATE RESTRICT,
	FOREIGN KEY (equipo) REFERENCES equipos (cod_equipo) ON DELETE RESTRICT ON UPDATE RESTRICT,
	FOREIGN KEY (competidor2) REFERENCES clavadistas (cod_clavadista) ON DELETE RESTRICT ON UPDATE RESTRICT,
	FOREIGN KEY (competencia2) REFERENCES competencias (cod_competencia) ON DELETE RESTRICT ON UPDATE RESTRICT,
	FOREIGN KEY (equipo2) REFERENCES equipos (cod_equipo) ON DELETE RESTRICT ON UPDATE RESTRICT
	) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE planillad (
	planilla INT NOT NULL,
	ronda TINYINT NOT NULL,
	salto VARCHAR(6) NOT NULL,
	posicion 	CHAR(1) NOT NULL,
	grado_dif	DECIMAL(5,2) NOT NULL,
	abierto char(1),
	total_salto	DECIMAL(6,2),
	puntaje_salto	DECIMAL(6,2),
	acumulado	DECIMAL(6,2),
	penalizado	DECIMAL(4,2),
	bonificado	DECIMAL(4,2),
	hora_salto	DATETIME,
	calificado	BOOLEAN,
	PRIMARY KEY (planilla, ronda),
	FOREIGN KEY (planilla) REFERENCES planillas (cod_planilla) ON DELETE RESTRICT ON UPDATE RESTRICT,
	FOREIGN KEY (salto) REFERENCES saltos (cod_salto) ON DELETE RESTRICT ON UPDATE RESTRICT
	) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE planillac (
	planilla INT NOT NULL,
	ronda TINYINT NOT NULL,
	ubicacion TINYINT NOT NULL,
	juez	INT,
	calificacion	DECIMAL(4,2),
	PRIMARY KEY (planilla, ronda,ubicacion),
	FOREIGN KEY (planilla,ronda) REFERENCES planillad (planilla,ronda) ON DELETE RESTRICT ON UPDATE RESTRICT,

	FOREIGN KEY (juez) REFERENCES jueces (cod_juez) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

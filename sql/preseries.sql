-- sirve para copiar series en otra categoría
CREATE or REPLACE TABLE bor_preserie AS
SELECT *  FROM `preseries` WHERE `competencia` = 130 AND `categoria` LIKE 'VA';

-- Cambia la categoría
UPDATE bor_preserie SET cod_preserie=0,categoria='VC';

-- Inserta la tabla modificada con categoría
INSERT INTO preseries SELECT * FROM `bor_preserie`;



-- Copia todas las preseries de novicios A,B,C y D
CREATE or REPLACE TABLE bor_preserie AS
SELECT *  FROM `preseries` WHERE `competencia` = 130 AND `categoria` IN ('VA','VB','VC','VD');

-- Cambia la altura a 3 mts
UPDATE bor_preserie SET cod_preserie=0,modalidad='3',altura=3;

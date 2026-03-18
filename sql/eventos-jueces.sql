

CREATE OR REPLACE VIEW eventos_juez_view_0 AS
SELECT j.competencia, j.juez, count(*) AS eventos
FROM competenciasz AS j 
WHERE ubicacion != 25
GROUP BY competencia, juez;

CREATE OR REPLACE VIEW eventos_juez_view AS
SELECT j.competencia, j.juez, u.nombre, j.eventos
FROM eventos_juez_view_0 AS j 
LEFT JOIN usuarios AS u ON u.cod_usuario=j.juez 
ORDER BY j.eventos DESC;


CREATE OR REPLACE VIEW eventos_juez_valor_view AS
SELECT *, eventos*10000 AS valor
FROM eventos_juez_view
WHERE competencia = 122;


SELECT nombre, eventos, valor
FROM eventos_juez_valor_view 
WHERE competencia = 122
ORDER BY valor DESC;


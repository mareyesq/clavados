CREATE OR REPLACE VIEW res_clavadistas AS
SELECT p.clavadista, p.competencia, p.cod_planilla, p.modalidad, p.categoria, p.sexo, p.entrenador, p.equipo, p.limite_dificultad, p.grado_dificultad, p.total, p.lugar, p.total_abierta, p.lugar_abierta
FROM planillas as p
WHERE p.clavadista IS NOT NULL
UNION
SELECT p.clavadista2 AS clavadista, p.competencia, p.cod_planilla, p.modalidad, p.categoria, p.sexo, p.entrenador, p.equipo, p.limite_dificultad, p.grado_dificultad, p.total, p.lugar, p.total_abierta, p.lugar_abierta
FROM planillas as p
WHERE p.clavadista2 IS NOT NULL
UNION
SELECT p.clavadista3 AS clavadista, p.competencia, p.cod_planilla, p.modalidad, p.categoria, p.sexo, p.entrenador, p.equipo, p.limite_dificultad, p.grado_dificultad, p.total, p.lugar, p.total_abierta, p.lugar_abierta
FROM planillas as p
WHERE p.clavadista3 IS NOT NULL
UNION
SELECT p.clavadista4 AS clavadista, p.competencia, p.cod_planilla, p.modalidad, p.categoria, p.sexo, p.entrenador, p.equipo, p.limite_dificultad, p.grado_dificultad, p.total, p.lugar, p.total_abierta, p.lugar_abierta
FROM planillas as p
WHERE p.clavadista4 IS NOT NULL
ORDER BY clavadista, competencia DESC;

CREATE OR REPLACE VIEW res_clav_comp AS
SELECT r.clavadista, u.nombre AS nom_clavadista, r.competencia, cp.competencia AS nom_competencia, GROUP_CONCAT(m.abreviado, '-', c.categoria, '-', total, '-', lugar SEPARATOR ';') AS resultados
FROM `res_clavadistas` as r 
LEFT JOIN modalidades as m ON m.cod_modalidad=r.modalidad 
LEFT JOIN categorias AS c ON c.cod_categoria=r.categoria
LEFT JOIN usuarios AS u ON u.cod_usuario=r.clavadista
LEFT JOIN competencias AS cp ON cp.cod_competencia=r.competencia
GROUP BY r.clavadista, r.competencia;


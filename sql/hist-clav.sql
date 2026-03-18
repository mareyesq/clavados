DROP TABLE IF EXISTS hist_clav;
DROP VIEW IF EXISTS hist_clav;
CREATE VIEW hist_clav as 
SELECT p.clavadista, C1.nombre AS nom_clavadista, p.competencia, c.competencia as nom_competencia, p.equipo, q.equipo as nom_equipo, p.modalidad, m.modalidad AS nom_modalidad, p.categoria, k.categoria AS nom_categoria, p.sexo, p.total, p.lugar  
FROM planillas as p 
LEFT JOIN usuarios AS c1 ON c1.cod_usuario=p.clavadista
LEFT JOIN competencias AS c ON c.cod_competencia=p.competencia
LEFT JOIN modalidades AS m ON m.cod_modalidad=p.modalidad
LEFT JOIN categorias AS k ON k.cod_categoria=p.categoria
LEFT JOIN competenciasq AS q ON q.competencia=p.competencia AND q.nombre_corto=p.equipo
WHERE p.usuario_retiro IS null
UNION
SELECT p.clavadista, C1.nombre AS nom_clavadista, p.competencia, c.competencia as nom_competencia, p.equipo, q.equipo as nom_equipo, p.modalidad, m.modalidad AS nom_modalidad, 'AB', 'Abierta' AS nom_categoria, p.sexo, p.total_abierta AS total , p.lugar_abierta AS lugar
FROM planillas as p 
LEFT JOIN usuarios AS c1 ON c1.cod_usuario=p.clavadista
LEFT JOIN competencias AS c ON c.cod_competencia=p.competencia
LEFT JOIN modalidades AS m ON m.cod_modalidad=p.modalidad
LEFT JOIN competenciasq AS q ON q.competencia=p.competencia AND q.nombre_corto=p.equipo
WHERE p.usuario_retiro IS null AND p.categoria IN ('GB','GA') AND p.part_abierta='*'
UNION
SELECT p.clavadista2 as clavadista, C2.nombre AS nom_clavadista, p.competencia, c.competencia as nom_competencia, p.equipo, q.equipo as nom_equipo, p.modalidad, m.modalidad AS nom_modalidad, p.categoria, k.categoria AS nom_categoria, p.sexo2 as sexo, p.total, p.lugar  
FROM planillas as p 
LEFT JOIN usuarios AS c2 ON c2.cod_usuario=p.clavadista2
LEFT JOIN competencias AS c ON c.cod_competencia=p.competencia
LEFT JOIN modalidades AS m ON m.cod_modalidad=p.modalidad
LEFT JOIN categorias AS k ON k.cod_categoria=p.categoria
LEFT JOIN competenciasq AS q ON q.competencia=p.competencia AND q.nombre_corto=p.equipo
WHERE p.usuario_retiro IS null AND p.clavadista2 IS NOT NULL
UNION
SELECT p.clavadista3 as clavadista, C3.nombre AS nom_clavadista, p.competencia, c.competencia as nom_competencia, p.equipo, q.equipo as nom_equipo, p.modalidad, m.modalidad AS nom_modalidad, p.categoria, k.categoria AS nom_categoria, p.sexo3 as sexo, p.total, p.lugar  
FROM planillas as p 
LEFT JOIN usuarios AS c3 ON c3.cod_usuario=p.clavadista3
LEFT JOIN competencias AS c ON c.cod_competencia=p.competencia
LEFT JOIN modalidades AS m ON m.cod_modalidad=p.modalidad
LEFT JOIN categorias AS k ON k.cod_categoria=p.categoria
LEFT JOIN competenciasq AS q ON q.competencia=p.competencia AND q.nombre_corto=p.equipo
WHERE p.usuario_retiro IS null AND p.clavadista3 IS NOT NULL
UNION
SELECT p.clavadista2 as clavadista, C4.nombre AS nom_clavadista, p.competencia, c.competencia as nom_competencia, p.equipo, q.equipo as nom_equipo, p.modalidad, m.modalidad AS nom_modalidad, p.categoria, k.categoria AS nom_categoria, p.sexo4 as sexo, p.total, p.lugar  
FROM planillas as p 
LEFT JOIN usuarios AS c4 ON c4.cod_usuario=p.clavadista4
LEFT JOIN competencias AS c ON c.cod_competencia=p.competencia
LEFT JOIN modalidades AS m ON m.cod_modalidad=p.modalidad
LEFT JOIN categorias AS k ON k.cod_categoria=p.categoria
LEFT JOIN competenciasq AS q ON q.competencia=p.competencia AND q.nombre_corto=p.equipo
WHERE p.usuario_retiro IS null AND p.clavadista4 IS NOT NULL;
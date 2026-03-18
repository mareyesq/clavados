CREATE OR REPLACE VIEW rondas AS
SELECT p.competencia, p.evento, p.orden_salida, p.clavadista, IF(p.clavadista2 IS NULL, c.nombre, CONCAT_WS(' ',c.nombre,c2.nombre)) AS nombre, q.equipo,  GROUP_CONCAT(d.salto, ' ', d.posicion, ' ', ROUND(d.altura,1), ' ', ROUND(d.grado_dif,1) SEPARATOR ';') AS salto
FROM planillas AS p 
LEFT JOIN planillad AS d ON d.planilla=p.cod_planilla
LEFT JOIN usuarios AS c ON c.cod_usuario=p.clavadista
LEFT JOIN usuarios AS c2 ON c2.cod_usuario=p.clavadista2
LEFT JOIN competenciasq AS q ON q.competencia=p.competencia AND q.nombre_corto=p.equipo
GROUP BY p.competencia, p.evento, p.orden_salida
ORDER BY p.competencia, p.evento, p.orden_salida;

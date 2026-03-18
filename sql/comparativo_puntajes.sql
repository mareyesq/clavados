DROP VIEW IF EXISTS puntajes_jueces_0;
CREATE VIEW puntajes_jueces_0 AS
SELECT p.competencia, p.evento, p.categoria, p.sexo, p.clavadista, p.total, d.ronda, d.salto, d.posicion, d.altura, d.grado_dif, d.penalizado, d.total_salto, d.puntaje_salto, d.juez1, d.juez2, d.juez3, d.juez4, d.juez5, d.juez6, d.juez7, d.cal1, d.cal2, d.cal3, d.cal4, d.cal5, d.cal6, d.cal7, 
	IF(d.cal1 IS NOT NULL, IF(d.penalizado IS NOT NULL,((d.cal1-d.penalizado)*3*d.grado_dif), (d.cal1*3*d.grado_dif)), NULL)  AS puntaje_1,
	IF(d.cal2 IS NOT NULL, IF(d.penalizado IS NOT NULL,((d.cal2-d.penalizado)*3*d.grado_dif), (d.cal2*3*d.grado_dif)), NULL)  AS puntaje_2,
	IF(d.cal3 IS NOT NULL, IF(d.penalizado IS NOT NULL,((d.cal3-d.penalizado)*3*d.grado_dif), (d.cal3*3*d.grado_dif)), NULL)  AS puntaje_3,
	IF(d.cal4 IS NOT NULL, IF(d.penalizado IS NOT NULL,((d.cal4-d.penalizado)*3*d.grado_dif), (d.cal4*3*d.grado_dif)), NULL)  AS puntaje_4,
	IF(d.cal5 IS NOT NULL, IF(d.penalizado IS NOT NULL,((d.cal5-d.penalizado)*3*d.grado_dif), (d.cal5*3*d.grado_dif)), NULL)  AS puntaje_5,
	IF(d.cal6 IS NOT NULL, IF(d.penalizado IS NOT NULL,((d.cal6-d.penalizado)*3*d.grado_dif), (d.cal6*3*d.grado_dif)), NULL)  AS puntaje_6,
	IF(d.cal7 IS NOT NULL, IF(d.penalizado IS NOT NULL,((d.cal7-d.penalizado)*3*d.grado_dif), (d.cal7*3*d.grado_dif)), NULL)  AS puntaje_7
	FROM planillas as p 
	LEFT JOIN planillad as d on d.planilla=p.cod_planilla
   	LEFT JOIN modalidades as m on m.cod_modalidad=p.modalidad
	WHERE m.individual=1 AND momento_termino IS NOT NULL;


DROP VIEW IF EXISTS puntajes_jueces;
CREATE VIEW puntajes_jueces AS
SELECT competencia, evento, categoria, sexo, clavadista, total, juez1, juez2, juez3, juez4, juez5, juez6, juez7, SUM(puntaje_1) aS p1, SUM(puntaje_2) aS p2, SUM(puntaje_3) aS p3, SUM(puntaje_4) aS p4, SUM(puntaje_5) aS p5, SUM(puntaje_6) aS p6, SUM(puntaje_7) aS p7
FROM puntajes_jueces_0 
GROUP BY competencia, evento, categoria, sexo, clavadista
ORDER BY categoria, sexo, total DESC;




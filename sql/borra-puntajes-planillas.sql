SELECT *  FROM `planillas` WHERE `competencia` = 104 AND `evento` = 2 AND total is NOT NULL

select d.* FROM planillad as d 
LEFT JOIN planillas AS p ON p.cod_planilla=d.planilla
WHERE p.competencia=104
	AND p.evento=2 
    AND d.suma IS NOT NULL

UPDATE planillad as d 
LEFT JOIN planillas AS p ON p.cod_planilla=d.planilla
SET suma=NULL,
	d.total_salto=NULL,
    d.puntaje_salto=NULL,
    d.acumulado=NULL,
    d.hora_salto=NULL,
    d.calificado=NULL,
    d.juez1=NULL,
    d.juez2=NULL,
    d.juez3=NULL,
    d.cal1=NULL,
    d.cal2=NULL,
    d.cal3=NULL,
    d.calificando=NULL
WHERE p.competencia=104
	AND p.evento=2 
    AND d.suma IS NOT NULL

UPDATE planillas
SET total=NULL,
	lugar=NULL
 WHERE competencia = 104 AND evento = 2 AND total is NOT NULL

SELECT p.modalidad,  p.categoria, d.salto, d.posicion, d.grado_dif
FROM `planillas` as p 
LEFT JOIN planillad As d on d.planilla=p.cod_planilla
WHERE p.competencia=101 and modalidad=1 and posicion='A' and  d.salto=100

UPDATE `planillas` as p 
LEFT JOIN planillad As d on d.planilla=p.cod_planilla
SET d.grado_dif=1.0
WHERE p.competencia=101 and modalidad=1 and posicion='A' and  d.salto=100


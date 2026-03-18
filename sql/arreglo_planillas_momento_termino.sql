update planillas 
	LEFT JOIN competencias
	set momento_termino=NULL
LEFT JOIN competenciaev as ce on ce.competencia=p.competencia and ce.numero_evento=p.evento
WHERE p.competencia=103
	AND ce.finalizo_comp is null

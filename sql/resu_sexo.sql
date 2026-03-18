drop view IF EXISTS resu_sexo;
create view resu_sexo AS 
SELECT p.competencia as competencia, p.sexo as sexo, q.equipo as nom_equipo, SUM(t.puntos) AS total_puntos
	FROM planillas 	as p
	LEFT JOIN categorias 	as k 	on k.cod_categoria=p.categoria
	LEFT JOIN competenciast	as t 	on t.competencia=p.competencia and t.puesto=p.lugar
	LEFT JOIN competenciasq as q 	on (q.competencia=p.competencia and q.nombre_corto=p.equipo)
	WHERE k.individual=1 
		and (p.categoria<>'AB')
		AND p.momento_termino is NOT NULL 
		AND p.extraof IS NULL AND p.usuario_retiro is NULL
	GROUP BY competencia, sexo, nom_equipo 
UNION SELECT p.competencia as competencia, p.sexo as sexo, q.equipo as nom_equipo, SUM(t.puntos_sinc) AS total_puntos
	FROM planillas 	as p
	LEFT JOIN categorias 	as k 	on k.cod_categoria=p.categoria
	LEFT JOIN competenciast	as t 	on t.competencia=p.competencia and t.puesto=p.lugar
	LEFT JOIN competenciasq as q 	on (q.competencia=p.competencia and q.nombre_corto=p.equipo)
	WHERE k.individual=0 
		and p.categoria<>'AB' 
		AND p.momento_termino is NOT NULL 
		AND p.extraof IS NULL 
		AND p.usuario_retiro is NULL
	GROUP BY competencia, sexo, nom_equipo
 UNION SELECT p.competencia as competencia, p.sexo as sexo,  q.equipo as nom_equipo, SUM(t.puntos) AS total_puntos
	FROM planillas 	as p
	LEFT JOIN categorias 	as k 	on k.cod_categoria=p.categoria
	LEFT JOIN competenciast	as t 	on t.competencia=p.competencia and t.puesto=p.lugar_abierta
	LEFT JOIN competenciasq as q 	on (q.competencia=p.competencia and q.nombre_corto=p.equipo) 
	WHERE (p.part_abierta='*' or p.categoria ='AB') 
		and k.individual=1 
		and p.momento_termino is NOT NULL 
		AND p.extraof_abierto IS NULL 
		AND p.usuario_retiro is NULL
	GROUP BY competencia, sexo, nom_equipo
 	ORDER BY competencia, sexo, total_puntos DESC;

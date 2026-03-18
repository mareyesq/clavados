UPDATE `competenciaev` as e
LEFT JOIN planillas as p ON p.competencia=e.competencia and p.evento=e.numero_evento
SET p.momento_termino=e.finalizo_comp
WHERE e.competencia = 99 
	and finalizo_comp is NOT NULL
    and p.momento_termino is null
    
ALTER TABLE `competenciasqe` ADD `entrenador` INT NULL AFTER `nombre_corto`, ADD INDEX (`entrenador`);

ALTER TABLE `competencias`  ADD `banderas` TINYINT(1) NULL COMMENT '1=Usa banderas'  AFTER `control_cambios`;
   
ALTER TABLE `competenciaev`  ADD `panel_activo` TINYINT(1) NOT NULL DEFAULT '1' COMMENT 'Panel de jueces activo 1 ó 2'  AFTER `evento`;
ALTER TABLE `calificaciones` ADD `sombra` TINYINT(1) NULL COMMENT '1=sombra' AFTER `ubcacion`
   


UPDATE planillas SET evento=2 WHERE competencia=104 and modalidad=1 and categoria='GB'   AND sexo='M';

UPDATE planillas SET evento=1 WHERE competencia=104 and modalidad=3 and categoria='GD'   AND sexo='F';

-- marzo 23 2021
SELECT p.competencia, p.cod_planilla, p.modalidad, p.categoria, p.sexo, p.equipo, p.clavadista, p.clavadista2, p.clavadista3, p.clavadista4, p.entrenador, p.equipo, p.limite_dificultad, p.grado_dificultad, p.total, p.lugar, p.total_abierta, p.lugar_abierta, p. c.fecha_inicia, c.fecha_termina, c.competencia
FROM planillas as p
LEFT JOIN competencias as c on c.cod_competencia=p.competencia
LEFT JOIN modalidades as m on m.cod_modalidad=p.modalidad
LEFT JOIN categorias as ct on ct.cod_categoria=p.categoria
LEFT JOIN competenciasq as eq on eq.cod_competencia=p.competencia AND eq.nombre_corto=p.equipo
LEFT JOIN usuarios as c1 on c1.cod_usuario=p.clavadista 


ORDER BY p.competencia, d.puntaje_salto DESC, d.salto, d.posicion, d.altura

-- Octubre 17 2021
ALTER TABLE `competenciaev` ADD `calentamiento` SMALLINT NULL AFTER `panel_activo`, ADD `partcipantes_estimado` SMALLINT NULL AFTER `calentamiento`;


-- Agosto 14 2024
SELECT p.cod_planilla, p.equipo, p.orden_salida, p.total, p.part_abierta, p.lugar, p.extraof, p.categoria, p.sexo, p.evento, CONCAT (p.categoria, p.sexo) as orden_categorias, cat.categoria as nom_cat, c.nombre as nom_cla, c2.nombre as nom_cla2, m.modalidad as nom_mod, e.modalidad, e.sexos, e.categorias, e.tipo, e.fechahora
FROM planillas as p  
LEFT JOIN usuarios as c on c.cod_usuario=p.clavadista
LEFT JOIN usuarios as c2 on c2.cod_usuario=p.clavadista2
LEFT JOIN categorias as cat on cat.cod_categoria=p.categoria
LEFT JOIN modalidades as m on m.cod_modalidad=p.modalidad
LEFT JOIN competenciaev as e on e.competencia=p.competencia and e.numero_evento=p.evento
WHERE p.competencia=128
	AND p.categoria<>'AB'
	AND p.usuario_retiro IS NULL
	AND e.finalizo_comp IS NOT NULL

UNION
SELECT p.cod_planilla, p.equipo, p.orden_salida, p.total_abierta AS total, p.part_abierta, p.lugar_abierta AS lugar, p.extraof_abierto AS extraof, 'AB' AS categoria, p.sexo, p.evento,  CONCAT ('AB', p.sexo) as orden_categorias, cat.categoria as nom_cat, c.nombre as nom_cla, c2.nombre as nom_cla2, m.modalidad as nom_mod, e.modalidad, e.sexos, e.categorias, e.tipo, e.fechahora 
FROM planillas as p  
LEFT JOIN usuarios as c on c.cod_usuario=p.clavadista
LEFT JOIN usuarios as c2 on c2.cod_usuario=p.clavadista2
LEFT JOIN categorias as cat on cat.cod_categoria='AB'
LEFT JOIN modalidades as m on m.cod_modalidad=p.modalidad
LEFT JOIN competenciaev as e on e.competencia=p.competencia and e.numero_evento=p.evento
WHERE p.competencia=128
	AND p.categoria='AB'
	AND p.usuario_retiro IS NULL
	AND e.finalizo_comp IS NOT NULL
ORDER BY fechahora, evento, categoria, sexo, extraof, lugar
    
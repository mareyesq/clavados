-- Vista para validar los grados de dificultad (cambiar la competencia) marq agosto 22/2025

CREATE OR REPLACE VIEW vista_dificultad AS
SELECT u.nombre, m.modalidad, p.categoria, p.sexo, d.planilla, d.salto, d.posicion, d.altura, d.grado_dif, f.cod_salto, f.altura AS altura_dificultad, f.posicion_a, f.posicion_b, f.posicion_c, f.posicion_d
FROM planillad AS d
LEFT JOIN planillas AS p ON p.cod_planilla=d.planilla
LEFT JOIN usuarios AS u ON u.cod_usuario=p.clavadista
LEFT JOIN modalidades AS m ON m.cod_modalidad=p.modalidad
LEFT JOIN dificult AS f ON f.cod_salto=d.salto AND f.altura = d.altura 
WHERE p.competencia=135;

-- Consulta para validar los grados de dificultad
SELECT * FROM vista_dificultad
WHERE (posicion='A' AND grado_dif != posicion_a)
OR (posicion='B' AND grado_dif != posicion_b)
OR (posicion='C' AND grado_dif != posicion_c)
OR (posicion='D' AND grado_dif != posicion_d);
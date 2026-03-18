DELETE
FROM planillad 
WHERE planilla IN (SELECT cod_planilla FROM planillas WHERE competencia IN (29,71,96,54));

DELETE 
FROM planillas 
WHERE competencia IN (29,71,96,54);

DELETE 
FROM competenciasz
WHERE competencia IN (29,71,96,54);

DELETE 
FROM competenciast 
WHERE competencia IN (29,71,96,54);

DELETE 
FROM competenciaev 
WHERE competencia IN (29,71,96,54);

DELETE 
FROM competenciapr
WHERE competencia IN (29,71,96,54);

DELETE 
FROM competenciasa
WHERE competencia IN (29,71,96,54);

DELETE 
FROM competenciasjz
WHERE competencia IN (29,71,96,54);

DELETE 
FROM competenciasm
WHERE competencia IN (29,71,96,54);

DELETE 
FROM competenciasq 
WHERE competencia IN (29,71,96,54);

DELETE 
FROM competenciasqe
WHERE competencia IN (29,71,96,54);

DELETE 
FROM competencias
WHERE cod_competencia IN (29,71,96,54);

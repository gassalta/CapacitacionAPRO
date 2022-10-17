-- Consulta de las materias más cursadas.
SELECT a.*, COUNT(e.Id) total
FROM areas a 
inner join espacioscurriculares e on a.id = e.Area 
GROUP by e.Area
ORDER BY total DESC

-- BUSCAR ALUMNO POR DNI
SELECT e.id,e.nroLegajo,CONCAT(e.apellido,', ',e.nombre) nombres,e.dni FROM estudiantes e WHERE e.dni = '47365250';

-- BUSCAR DOCENTE POR DNI
SELECT d.Id,d.NroLegajoJunta,CONCAT(d.Apellido,', ',d.Nombre) nombres,d.DNI FROM docentes d WHERE d.DNI = '28775140';

-- Mejores 3 promedios de alumnos del último curso
-- cursos
-- calificacionfinalxespcurr
-- estudiantes
SELECT c.estudiante, AVG(c.calificacion) nota, ROUND(c.calificacion,2) redondeo,CONCAT(e.apellido,', ',e.nombre) alumno,e.curso
FROM calificacionfinalxespcurr c 
INNER JOIN estudiantes e ON e.id = c.estudiante 
INNER JOIN cursos c2 ON c2.Id = e.curso
WHERE c2.Id = 4 GROUP BY c.estudiante ORDER BY nota DESC LIMIT 3;

-- Mejor promedio de alumno de la escuela
SELECT c.estudiante, AVG(c.calificacion) nota, ROUND(c.calificacion,2) redondeo,CONCAT(e.apellido,', ',e.nombre) alumno, e.curso
FROM calificacionfinalxespcurr c 
INNER JOIN estudiantes e ON e.id = c.estudiante 
INNER JOIN cursos c2 ON c2.Id = e.curso
GROUP BY c.estudiante ORDER BY nota DESC LIMIT 3;

-- PROMEDIOS
-- Curso con materias de notas más alto.
-- Curso con materias de notas más bajo.
SELECT e.curso,AVG(c.calificacion) nota
FROM calificacionfinalxespcurr c 
INNER JOIN estudiantes e ON e.id = c.estudiante
GROUP BY e.curso
ORDER BY nota DESC
LIMIT 1

-- Consultar promedio de materia más baja.
SELECT c.espacioCurricular, AVG(c.calificacion) promedio, e.NombreEspacCurric, a.Denominacion 
FROM calificacionfinalxespcurr c
INNER JOIN espacioscurriculares e ON e.Id = c.espacioCurricular
inner join areas a ON a.Id = e.Area 
GROUP BY c.espacioCurricular 
ORDER BY promedio ASC LIMIT 1
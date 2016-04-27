SELECT tr_osnov.*,
	mattraffic.*,
	material.*,
	m2.*
FROM mattraffic
LEFT JOIN material ON mattraffic.id_material = material.material_id
LEFT JOIN (
	SELECT id_material AS id_material_m2,
		id_mol AS id_mol_m2,
		mattraffic_date AS mattraffic_date_m2,
		mattraffic_tip AS mattraffic_tip_m2
	FROM mattraffic
	) m2 ON id_material = m2.id_material_m2
	AND id_mol = m2.id_mol_m2
	AND mattraffic_date < m2.mattraffic_date_m2
	AND m2.mattraffic_tip_m2 IN (1,2)
LEFT JOIN tr_osnov ON material_tip = 1
	AND tr_osnov.id_mattraffic IN (
		SELECT mattraffic_id
		FROM mattraffic mt
		WHERE mt.id_mol = mattraffic.id_mol
			AND mt.id_material = mattraffic.id_material
		)
WHERE mattraffic_number > 0
	AND m2.mattraffic_date_m2 IS NULL
	AND mattraffic_tip IN (1,2)
	AND tr_osnov.id_mattraffic IS NULL

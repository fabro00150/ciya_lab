import datetime

def create_laboratorio(data):
    """
    Generates an SQL INSERT statement and parameters for creating a new laboratorio.

    Args:
        data (dict): A dictionary with keys matching column names. Must include:
                     'nombre_lab', 'fk_docente_responsable_lab',
                     'fk_administrativo_responsable_lab', 'usuario_creacion_lab'.
                     Other optional fields like 'descripcion_lab', 'ubicacion_lab',
                     'telefono_ext_lab', 'correo_lab', 'estado_lab' can be included.

    Returns:
        tuple: (str, dict)
               - The SQL INSERT statement string.
               - A dictionary of parameters to be used with the SQL statement.
    """
    required_fields = ['nombre_lab', 'fk_docente_responsable_lab', 'fk_administrativo_responsable_lab', 'usuario_creacion_lab']
    for field in required_fields:
        if field not in data:
            raise ValueError(f"Missing required field '{field}' in data for create_laboratorio")

    columns = list(data.keys())
    values_placeholders = [f"%({col})s" for col in columns]

    # Add automatic fields
    columns.append('fecha_creacion_lab')
    values_placeholders.append('NOW()')
    columns.append('fecha_actualizacion_lab')
    values_placeholders.append('NOW()')
    columns.append('usuario_actualizacion_lab')
    # Use the value of 'usuario_creacion_lab' for 'usuario_actualizacion_lab' during creation
    values_placeholders.append(f"%(usuario_creacion_lab)s")

    sql = f"INSERT INTO laboratorios.laboratorio ({', '.join(columns)}) VALUES ({', '.join(values_placeholders)}) RETURNING id_lab;"

    # Parameters will be the original data dict because 'usuario_creacion_lab' is already in it,
    # and NOW() is handled by PostgreSQL.
    return sql, data

def get_laboratorio_by_id(laboratorio_id):
    """
    Generates an SQL SELECT statement and parameters for retrieving a laboratorio by its ID.

    Args:
        laboratorio_id (int): The ID of the laboratorio to retrieve.

    Returns:
        tuple: (str, dict)
               - The SQL SELECT statement string.
               - A dictionary of parameters to be used with the SQL statement.
    """
    sql = "SELECT * FROM laboratorios.laboratorio WHERE id_lab = %(laboratorio_id)s;"
    params = {'laboratorio_id': laboratorio_id}
    return sql, params

def get_all_laboratorios(filters=None, sort_by=None, limit=None, offset=None):
    """
    Generates an SQL SELECT statement and parameters for retrieving multiple laboratorios,
    with optional filtering, sorting, limit, and offset.

    Args:
        filters (dict, optional): A dictionary for filtering. Keys are column names,
                                  values are the values to match exactly. Defaults to None.
        sort_by (str, optional): Column name to sort by. Add ' DESC' for descending.
                                 Defaults to None.
        limit (int, optional): Maximum number of records to return. Defaults to None.
        offset (int, optional): Number of records to skip. Defaults to None.

    Returns:
        tuple: (str, dict)
               - The SQL SELECT statement string.
               - A dictionary of parameters to be used with the SQL statement.
    """
    sql = "SELECT * FROM laboratorios.laboratorio"
    params = {}
    where_clauses = []

    if filters:
        for col, value in filters.items():
            # Basic protection against SQL injection for column names in filters
            if not (col.isalnum() or col == '_'):
                 raise ValueError(f"Invalid character in filter column name: {col}")
            where_clauses.append(f"{col} = %({col})s")
            params[col] = value

    if where_clauses:
        sql += " WHERE " + " AND ".join(where_clauses)

    if sort_by:
        # Basic protection against SQL injection for sort_by
        safe_sort_by = "".join(c for c in sort_by if c.isalnum() or c.isspace() or c == '_')
        if not safe_sort_by or safe_sort_by.lower().count("desc") > 1 or safe_sort_by.lower().count("asc") > 1 : # prevent multiple asc/desc
            raise ValueError(f"Invalid characters or structure in sort_by argument: {sort_by}")
        if safe_sort_by != sort_by:
             raise ValueError(f"Potentially unsafe characters in sort_by argument: {sort_by}")
        sql += f" ORDER BY {safe_sort_by}"


    if limit is not None:
        if not isinstance(limit, int) or limit < 0:
            raise ValueError("Limit must be a non-negative integer")
        sql += " LIMIT %(limit)s"
        params['limit'] = limit

    if offset is not None:
        if not isinstance(offset, int) or offset < 0:
            raise ValueError("Offset must be a non-negative integer")
        sql += " OFFSET %(offset)s"
        params['offset'] = offset

    sql += ";"
    return sql, params

def update_laboratorio(laboratorio_id, update_data):
    """
    Generates an SQL UPDATE statement and parameters for updating an existing laboratorio.

    Args:
        laboratorio_id (int): The ID of the laboratorio to update.
        update_data (dict): A dictionary of fields to update.
                            Must include 'usuario_actualizacion_lab'.
                            Can include fields like 'nombre_lab', 'descripcion_lab', etc.

    Returns:
        tuple: (str, dict)
               - The SQL UPDATE statement string.
               - A dictionary of parameters to be used with the SQL statement.
    """
    if not update_data:
        raise ValueError("update_data cannot be empty for update_laboratorio")
    if 'usuario_actualizacion_lab' not in update_data:
        raise ValueError("Missing 'usuario_actualizacion_lab' in update_data for update_laboratorio")

    set_clauses = []
    for col, value in update_data.items():
        # Basic protection for column names
        if not (col.isalnum() or col == '_'):
            raise ValueError(f"Invalid character in update_data column name: {col}")
        set_clauses.append(f"{col} = %({col})s")

    set_clauses.append("fecha_actualizacion_lab = NOW()")

    sql = f"UPDATE laboratorios.laboratorio SET {', '.join(set_clauses)} WHERE id_lab = %(id_lab)s;"

    params = update_data.copy()
    params['id_lab'] = laboratorio_id

    return sql, params

def delete_laboratorio(laboratorio_id):
    """
    Generates an SQL DELETE statement and parameters for deleting a laboratorio by its ID.

    Args:
        laboratorio_id (int): The ID of the laboratorio to delete.

    Returns:
        tuple: (str, dict)
               - The SQL DELETE statement string.
               - A dictionary of parameters to be used with the SQL statement.
    """
    sql = "DELETE FROM laboratorios.laboratorio WHERE id_lab = %(laboratorio_id)s;"
    params = {'laboratorio_id': laboratorio_id}
    return sql, params

if __name__ == '__main__':
    # Example Usage (for testing purposes)
    print("--- create_laboratorio ---")
    try:
        create_sql, create_params = create_laboratorio({
            'nombre_lab': 'Laboratorio de Quimica General',
            'descripcion_lab': 'Laboratorio para practicas de quimica basica',
            'ubicacion_lab': 'Edificio C, Salon 101',
            'telefono_ext_lab': '555-1201',
            'correo_lab': 'lab.quimica@example.com',
            'fk_docente_responsable_lab': 10, # Example ID
            'fk_administrativo_responsable_lab': 5, # Example ID
            'estado_lab': True,
            'usuario_creacion_lab': 'user_admin_lab'
        })
        print("SQL:", create_sql)
        print("Params:", create_params)
    except ValueError as e:
        print("Error:", e)
    print("\n")

    print("--- create_laboratorio (missing required field) ---")
    try:
        create_laboratorio({'nombre_lab': 'Incompleto Lab', 'usuario_creacion_lab': 'test_user'})
    except ValueError as e:
        print("Error:", e)
    print("\n")

    print("--- get_laboratorio_by_id ---")
    get_sql, get_params = get_laboratorio_by_id(1)
    print("SQL:", get_sql)
    print("Params:", get_params)
    print("\n")

    print("--- get_all_laboratorios (no filters) ---")
    all_sql, all_params = get_all_laboratorios()
    print("SQL:", all_sql)
    print("Params:", all_params)
    print("\n")

    print("--- get_all_laboratorios (with filters and sort) ---")
    try:
        all_filtered_sql, all_filtered_params = get_all_laboratorios(
            filters={'estado_lab': True, 'ubicacion_lab': 'Edificio C, Salon 101'},
            sort_by='nombre_lab ASC',
            limit=10,
            offset=0
        )
        print("SQL:", all_filtered_sql)
        print("Params:", all_filtered_params)
    except ValueError as e:
        print("Error:", e)
    print("\n")

    print("--- get_all_laboratorios (with sort DESC) ---")
    try:
        all_filtered_sql_desc, all_filtered_params_desc = get_all_laboratorios(
            sort_by='nombre_lab DESC',
        )
        print("SQL:", all_filtered_sql_desc)
        print("Params:", all_filtered_params_desc)
    except ValueError as e:
        print("Error:", e)
    print("\n")

    print("--- update_laboratorio ---")
    try:
        update_sql, update_params = update_laboratorio(1, {
            'correo_lab': 'lab.quimica.updated@example.com',
            'telefono_ext_lab': '555-1202',
            'usuario_actualizacion_lab': 'updater_user_lab'
        })
        print("SQL:", update_sql)
        print("Params:", update_params)
    except ValueError as e:
        print("Error:", e)
    print("\n")

    print("--- update_laboratorio (missing usuario_actualizacion_lab) ---")
    try:
        update_laboratorio(1, {'nombre_lab': 'Test Update Lab'})
    except ValueError as e:
        print("Error:", e)
    print("\n")

    print("--- update_laboratorio (invalid column name in data) ---")
    try:
        update_laboratorio(1, {'nombre_lab;DROP TABLE laboratorios.laboratorio': 'Test Update Lab', 'usuario_actualizacion_lab': 'updater_user_lab'})
    except ValueError as e:
        print("Error:", e)
    print("\n")

    print("--- delete_laboratorio ---")
    delete_sql, delete_params = delete_laboratorio(1)
    print("SQL:", delete_sql)
    print("Params:", delete_params)
    print("\n")

    print("--- get_all_laboratorios (invalid sort_by) ---")
    try:
        get_all_laboratorios(sort_by="nombre_lab; DROP TABLE laboratorios.laboratorio")
    except ValueError as e:
        print("Error:", e)
    print("\n")

    print("--- get_all_laboratorios (invalid filter column) ---")
    try:
        get_all_laboratorios(filters={"estado_lab; DROP TABLE laboratorios.laboratorio": True})
    except ValueError as e:
        print("Error:", e)
    print("\n")

    print("--- get_all_laboratorios (invalid limit) ---")
    try:
        get_all_laboratorios(limit=-1)
    except ValueError as e:
        print("Error:", e)
    print("\n")

    print("--- get_all_laboratorios (invalid offset) ---")
    try:
        get_all_laboratorios(offset="abc")
    except ValueError as e:
        print("Error:", e)
    print("\n")

    print("--- create_laboratorio (check usuario_actualizacion_lab population) ---")
    # This specific example shows how 'usuario_actualizacion_lab' is set from 'usuario_creacion_lab'
    # The SQL output should reflect this: ... VALUES (..., %(usuario_creacion_lab)s, NOW(), NOW(), %(usuario_creacion_lab)s)
    try:
        create_sql_check, create_params_check = create_laboratorio({
            'nombre_lab': 'Lab Auto Check',
            'fk_docente_responsable_lab': 1,
            'fk_administrativo_responsable_lab': 1,
            'usuario_creacion_lab': 'creator123'
        })
        print("SQL for auto-check:", create_sql_check) # Check the SQL for usuario_actualizacion_lab
        print("Params for auto-check:", create_params_check)
        # Expected SQL (simplified): INSERT INTO laboratorios.laboratorio (... usuario_creacion_lab, ..., fecha_creacion_lab, fecha_actualizacion_lab, usuario_actualizacion_lab)
        # VALUES (..., %(usuario_creacion_lab)s, ..., NOW(), NOW(), %(usuario_creacion_lab)s) RETURNING id_lab;
    except ValueError as e:
        print("Error:", e)
    print("\n")

```

import datetime

def create_docente(data):
    """
    Generates an SQL INSERT statement and parameters for creating a new docente.

    Args:
        data (dict): A dictionary with keys matching column names for the docente table.
                     It must include 'usuario_creacion_doc'.

    Returns:
        tuple: (str, dict)
               - The SQL INSERT statement string.
               - A dictionary of parameters to be used with the SQL statement.
    """
    if 'usuario_creacion_doc' not in data:
        raise ValueError("Missing 'usuario_creacion_doc' in data for create_docente")

    columns = list(data.keys())
    values_placeholders = [f"%({col})s" for col in columns]

    # Add automatic fields
    columns.extend(['fecha_creacion_doc', 'fecha_actualizacion_doc', 'usuario_actualizacion_doc'])
    values_placeholders.extend(['NOW()', 'NOW()', f"%({data['usuario_creacion_doc']})s"]) # Direct value for usuario_actualizacion_doc initially

    sql = f"INSERT INTO docente ({', '.join(columns)}) VALUES ({', '.join(values_placeholders)}) RETURNING id_doc;"

    # Prepare params, ensuring usuario_actualizacion_doc is also present if it's a placeholder (though we used direct value above)
    params = data.copy()
    # params['fecha_creacion_doc'] = 'NOW()' # Handled by SQL
    # params['fecha_actualizacion_doc'] = 'NOW()' # Handled by SQL
    # params['usuario_actualizacion_doc'] = data['usuario_creacion_doc'] # Handled by direct value in SQL

    # Need to adjust params if a placeholder was used for usuario_actualizacion_doc
    # However, the current implementation directly inserts the value of usuario_creacion_doc
    # Let's refine the SQL to use a placeholder for usuario_actualizacion_doc as well for consistency

    columns = list(data.keys())
    values_placeholders = [f"%({col})s" for col in columns]

    # Add automatic fields
    columns.append('fecha_creacion_doc')
    values_placeholders.append('NOW()')
    columns.append('fecha_actualizacion_doc')
    values_placeholders.append('NOW()')
    columns.append('usuario_actualizacion_doc')
    values_placeholders.append(f"%(usuario_creacion_doc)s") # Use placeholder

    sql = f"INSERT INTO docente ({', '.join(columns)}) VALUES ({', '.join(values_placeholders)}) RETURNING id_doc;"

    # Parameters will just be the original data, as 'usuario_creacion_doc' is already in it.
    # 'NOW()' is handled by PostgreSQL.
    return sql, data

def get_docente_by_id(docente_id):
    """
    Generates an SQL SELECT statement and parameters for retrieving a docente by its ID.

    Args:
        docente_id (int): The ID of the docente to retrieve.

    Returns:
        tuple: (str, dict)
               - The SQL SELECT statement string.
               - A dictionary of parameters to be used with the SQL statement.
    """
    sql = "SELECT * FROM docente WHERE id_doc = %(docente_id)s;"
    params = {'docente_id': docente_id}
    return sql, params

def get_all_docentes(filters=None, sort_by=None, limit=None, offset=None):
    """
    Generates an SQL SELECT statement and parameters for retrieving multiple docentes,
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
    sql = "SELECT * FROM docente"
    params = {}
    where_clauses = []

    if filters:
        for col, value in filters.items():
            where_clauses.append(f"{col} = %({col})s")
            params[col] = value

    if where_clauses:
        sql += " WHERE " + " AND ".join(where_clauses)

    if sort_by:
        # Basic protection against SQL injection for sort_by, though ideally use a whitelist
        if not all(c.isalnum() or c.isspace() or c == '_' for c in sort_by):
            raise ValueError("Invalid characters in sort_by argument")
        sql += f" ORDER BY {sort_by}"

    if limit is not None:
        sql += " LIMIT %(limit)s"
        params['limit'] = limit

    if offset is not None:
        sql += " OFFSET %(offset)s"
        params['offset'] = offset

    sql += ";"
    return sql, params

def update_docente(docente_id, update_data):
    """
    Generates an SQL UPDATE statement and parameters for updating an existing docente.

    Args:
        docente_id (int): The ID of the docente to update.
        update_data (dict): A dictionary of fields to update.
                            Must include 'usuario_actualizacion_doc'.

    Returns:
        tuple: (str, dict)
               - The SQL UPDATE statement string.
               - A dictionary of parameters to be used with the SQL statement.
    """
    if not update_data:
        raise ValueError("update_data cannot be empty for update_docente")
    if 'usuario_actualizacion_doc' not in update_data:
        raise ValueError("Missing 'usuario_actualizacion_doc' in update_data for update_docente")

    set_clauses = [f"{col} = %({col})s" for col in update_data.keys()]
    set_clauses.append("fecha_actualizacion_doc = NOW()")

    sql = f"UPDATE docente SET {', '.join(set_clauses)} WHERE id_doc = %(id_doc)s;"

    params = update_data.copy()
    params['id_doc'] = docente_id

    return sql, params

def delete_docente(docente_id):
    """
    Generates an SQL DELETE statement and parameters for deleting a docente by its ID.

    Args:
        docente_id (int): The ID of the docente to delete.

    Returns:
        tuple: (str, dict)
               - The SQL DELETE statement string.
               - A dictionary of parameters to be used with the SQL statement.
    """
    sql = "DELETE FROM docente WHERE id_doc = %(docente_id)s;"
    params = {'docente_id': docente_id}
    return sql, params

if __name__ == '__main__':
    # Example Usage (for testing purposes)
    print("--- create_docente ---")
    create_sql, create_params = create_docente({
        'nombre_doc': 'Juan',
        'apellido_doc': 'Perez',
        'correo_doc': 'juan.perez@example.com',
        'telefono_doc': '1234567890',
        'tipo_identificacion_doc': 'CC',
        'numero_identificacion_doc': '12345678',
        'estado_doc': True,
        'usuario_creacion_doc': 'admin_user'
    })
    print("SQL:", create_sql)
    print("Params:", create_params)
    print("\n")

    print("--- get_docente_by_id ---")
    get_sql, get_params = get_docente_by_id(1)
    print("SQL:", get_sql)
    print("Params:", get_params)
    print("\n")

    print("--- get_all_docentes (no filters) ---")
    all_sql, all_params = get_all_docentes()
    print("SQL:", all_sql)
    print("Params:", all_params)
    print("\n")

    print("--- get_all_docentes (with filters and sort) ---")
    all_filtered_sql, all_filtered_params = get_all_docentes(
        filters={'estado_doc': True, 'tipo_identificacion_doc': 'CC'},
        sort_by='apellido_doc ASC',
        limit=10,
        offset=0
    )
    print("SQL:", all_filtered_sql)
    print("Params:", all_filtered_params)
    print("\n")

    print("--- get_all_docentes (with sort DESC) ---")
    all_filtered_sql_desc, all_filtered_params_desc = get_all_docentes(
        sort_by='apellido_doc DESC',
    )
    print("SQL:", all_filtered_sql_desc)
    print("Params:", all_filtered_params_desc)
    print("\n")

    print("--- update_docente ---")
    update_sql, update_params = update_docente(1, {
        'correo_doc': 'juan.p.updated@example.com',
        'telefono_doc': '0987654321',
        'usuario_actualizacion_doc': 'updater_user'
    })
    print("SQL:", update_sql)
    print("Params:", update_params)
    print("\n")

    print("--- delete_docente ---")
    delete_sql, delete_params = delete_docente(1)
    print("SQL:", delete_sql)
    print("Params:", delete_params)
    print("\n")

    # Test create_docente again to ensure the refined logic is correct
    print("--- create_docente (refined check) ---")
    # Note: 'usuario_actualizacion_doc' should NOT be in the input data for create_docente
    # It's derived from 'usuario_creacion_doc'
    create_sql_refined, create_params_refined = create_docente({
        'nombre_doc': 'Ana',
        'apellido_doc': 'Gomez',
        'correo_doc': 'ana.gomez@example.com',
        'usuario_creacion_doc': 'test_creator'
        # other fields for docente table...
    })
    print("SQL:", create_sql_refined)
    print("Params:", create_params_refined)
    # Expected SQL: INSERT INTO docente (nombre_doc, apellido_doc, correo_doc, usuario_creacion_doc, fecha_creacion_doc, fecha_actualizacion_doc, usuario_actualizacion_doc) VALUES (%(nombre_doc)s, %(apellido_doc)s, %(correo_doc)s, %(usuario_creacion_doc)s, NOW(), NOW(), %(usuario_creacion_doc)s) RETURNING id_doc;
    # Expected Params: {'nombre_doc': 'Ana', 'apellido_doc': 'Gomez', 'correo_doc': 'ana.gomez@example.com', 'usuario_creacion_doc': 'test_creator'}
    print("\n")

    try:
        print("--- create_docente (missing usuario_creacion_doc) ---")
        create_docente({'nombre_doc': 'Test'})
    except ValueError as e:
        print("Error:", e)
    print("\n")

    try:
        print("--- update_docente (missing usuario_actualizacion_doc) ---")
        update_docente(1, {'nombre_doc': 'Test'})
    except ValueError as e:
        print("Error:", e)
    print("\n")

    try:
        print("--- get_all_docentes (invalid sort_by) ---")
        get_all_docentes(sort_by="name; DROP TABLE users")
    except ValueError as e:
        print("Error:", e)

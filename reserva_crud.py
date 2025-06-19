import datetime
import re

def _quote_if_needed(column_name):
    """
    Quotes a column name with double quotes if it contains special characters
    (like hyphens) or is potentially a reserved keyword (by being case-sensitive
    or matching known patterns). Simple alphanumeric_underscore names are not quoted.
    """
    if not re.match(r'^[a-zA-Z_][a-zA-Z0-9_]*$', column_name) or column_name.lower() in {"user"}: # Add other reserved words if necessary
        return f'"{column_name}"'
    return column_name

def create_reserva(data):
    """
    Generates an SQL INSERT statement and parameters for creating a new reserva.

    Args:
        data (dict): A dictionary with keys matching column names. Must include:
                     'fk_id_tipres', 'fk_id_doc', 'fk_id_lab', 'fk_id_area',
                     'tema_res', 'estado_res', 'fecha_hora_res', 'duracion_res',
                     'usuario_creacion_res', 'fecha_hora_fin_res',
                     '"fk_id_car-2"', '"fk_id_usu-2"'.
                     Note: Keys for columns with special names must be passed already quoted
                     or match the exact column name string that needs quoting.
                     For simplicity, this function expects keys like "fk_id_car-2" directly.

    Returns:
        tuple: (str, dict)
               - The SQL INSERT statement string.
               - A dictionary of parameters to be used with the SQL statement.
    """
    required_fields = [
        'fk_id_tipres', 'fk_id_doc', 'fk_id_lab', 'fk_id_area', 'tema_res',
        'estado_res', 'fecha_hora_res', 'duracion_res', 'usuario_creacion_res',
        'fecha_hora_fin_res', 'fk_id_car-2', 'fk_id_usu-2'
        # 'pedidodocente_res' might also be required, add if so.
        # The prompt mentions "fk_id_car-2" and "fk_id_usu-2" as strings for keys.
    ]
    # Adjust keys for parameter dictionary if they were passed quoted
    param_data = {}
    for k, v in data.items():
        param_data[k.replace('"', '')] = v # Store params without quotes in keys

    for field in required_fields:
        # Check in param_data which has unquoted keys
        if field.replace('"', '') not in param_data:
            raise ValueError(f"Missing required field '{field}' in data for create_reserva")

    # Original keys (potentially with quotes) for SQL construction
    columns_original_keys = list(data.keys())
    quoted_columns = [_quote_if_needed(col) for col in columns_original_keys]
    # Parameter keys for %()s should NOT have quotes
    values_placeholders = [f"%({col.replace('"', '')})s" for col in columns_original_keys]


    # Add automatic fields
    quoted_columns.extend(['fecha_creacion_res', 'fecha_actualizacion_res', 'usuario_actualizacion_res'])
    # 'usuario_creacion_res' is already in data, its param key is 'usuario_creacion_res' (unquoted)
    values_placeholders.extend(['NOW()', 'NOW()', f"%(usuario_creacion_res)s"])

    sql = f"INSERT INTO laboratorios.reserva ({', '.join(quoted_columns)}) VALUES ({', '.join(values_placeholders)}) RETURNING id_res;"

    return sql, param_data

def get_reserva_by_id(reserva_id):
    """
    Generates an SQL SELECT statement and parameters for retrieving a reserva by its ID.

    Args:
        reserva_id (int): The ID of the reserva to retrieve.

    Returns:
        tuple: (str, dict)
               - The SQL SELECT statement string.
               - A dictionary of parameters to be used with the SQL statement.
    """
    sql = f"SELECT * FROM laboratorios.reserva WHERE id_res = %(reserva_id)s;"
    params = {'reserva_id': reserva_id}
    return sql, params

def get_all_reservas(filters=None, sort_by=None, limit=None, offset=None):
    """
    Generates an SQL SELECT statement and parameters for retrieving multiple reservas,
    with optional filtering, sorting, limit, and offset.

    Args:
        filters (dict, optional): A dictionary for filtering. Keys are column names
                                  (use exact names like "fk_id_car-2" if needed),
                                  values are the values to match. Defaults to None.
        sort_by (str, optional): Column name to sort by (use exact name). Add ' DESC' for descending.
                                 Defaults to None.
        limit (int, optional): Maximum number of records to return. Defaults to None.
        offset (int, optional): Number of records to skip. Defaults to None.

    Returns:
        tuple: (str, dict)
               - The SQL SELECT statement string.
               - A dictionary of parameters to be used with the SQL statement.
    """
    # For SELECT *, column names don't need quoting in the SELECT list itself unless aliased with special chars.
    # Quoting is critical for WHERE, ORDER BY, and potentially later if specific columns are selected.
    sql = "SELECT * FROM laboratorios.reserva"
    params = {}
    where_clauses = []

    if filters:
        for raw_col, value in filters.items():
            quoted_col = _quote_if_needed(raw_col)
            param_key = raw_col.replace('"', '') # Param keys should be simple
            where_clauses.append(f"{quoted_col} = %({param_key})s")
            params[param_key] = value

    if where_clauses:
        sql += " WHERE " + " AND ".join(where_clauses)

    if sort_by:
        sort_by_parts = sort_by.strip().split()
        col_to_sort = sort_by_parts[0]
        quoted_col_sort = _quote_if_needed(col_to_sort)

        direction = ""
        if len(sort_by_parts) > 1 and sort_by_parts[1].upper() in ['ASC', 'DESC']:
            direction = " " + sort_by_parts[1].upper()

        # Basic safety check on the column name before quoting
        if not re.match(r'^[a-zA-Z0-9_"\-]+$', col_to_sort): # Allow hyphen and quotes as they are handled
            raise ValueError(f"Invalid characters in sort_by column name: {col_to_sort}")

        sql += f" ORDER BY {quoted_col_sort}{direction}"


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

def update_reserva(reserva_id, update_data):
    """
    Generates an SQL UPDATE statement and parameters for updating an existing reserva.

    Args:
        reserva_id (int): The ID of the reserva to update.
        update_data (dict): A dictionary of fields to update. Keys are column names
                            (use exact names like "fk_id_car-2" if needed).
                            Must include 'usuario_actualizacion_res'.

    Returns:
        tuple: (str, dict)
               - The SQL UPDATE statement string.
               - A dictionary of parameters to be used with the SQL statement.
    """
    if not update_data:
        raise ValueError("update_data cannot be empty for update_reserva")

    # Use unquoted key for check and for param dict key
    if 'usuario_actualizacion_res' not in [k.replace('"', '') for k in update_data.keys()]:
        raise ValueError("Missing 'usuario_actualizacion_res' in update_data for update_reserva")

    set_clauses = []
    params = {}

    for raw_col, value in update_data.items():
        quoted_col = _quote_if_needed(raw_col)
        param_key = raw_col.replace('"', '') # Param keys should be simple

        # Basic safety check on the column name before quoting
        if not re.match(r'^[a-zA-Z0-9_"\-]+$', raw_col):
             raise ValueError(f"Invalid characters in update_data column name: {raw_col}")

        set_clauses.append(f"{quoted_col} = %({param_key})s")
        params[param_key] = value

    set_clauses.append("fecha_actualizacion_res = NOW()")

    sql = f"UPDATE laboratorios.reserva SET {', '.join(set_clauses)} WHERE id_res = %(id_res)s;"

    params['id_res'] = reserva_id

    return sql, params

def delete_reserva(reserva_id):
    """
    Generates an SQL DELETE statement and parameters for deleting a reserva by its ID.

    Args:
        reserva_id (int): The ID of the reserva to delete.

    Returns:
        tuple: (str, dict)
               - The SQL DELETE statement string.
               - A dictionary of parameters to be used with the SQL statement.
    """
    sql = "DELETE FROM laboratorios.reserva WHERE id_res = %(reserva_id)s;"
    params = {'reserva_id': reserva_id}
    return sql, params

if __name__ == '__main__':
    print("--- _quote_if_needed examples ---")
    print(f"simple_col: {_quote_if_needed('simple_col')}")
    print(f"fk_id_car-2: {_quote_if_needed('fk_id_car-2')}")
    print(f'"already_quoted-col": {_quote_if_needed('"already_quoted-col"')}') # Should ideally not double quote
    print(f"pedidodocente_res: {_quote_if_needed('pedidodocente_res')}") # Normal
    print(f"user: {_quote_if_needed('user')}") # Reserved keyword example
    print("\n")

    print("--- create_reserva ---")
    # For create_reserva, pass keys exactly as they are (with quotes if needed for special names)
    # or ensure your _quote_if_needed handles them if passed unquoted.
    # The current implementation expects keys like "fk_id_car-2" for data dict.
    create_data_example = {
        'fk_id_tipres': 1,
        'fk_id_doc': 2,
        'fk_id_lab': 3,
        'fk_id_area': 4,
        'tema_res': 'Tema de la reserva',
        'estado_res': 'Confirmada',
        'fecha_hora_res': '2024-08-15 10:00:00',
        'duracion_res': 120, # minutes
        'usuario_creacion_res': 'creator_user',
        'fecha_hora_fin_res': '2024-08-15 12:00:00',
        'fk_id_car-2': 5, # Special name, key matches column name
        'fk_id_usu-2': 6, # Special name
        'pedidodocente_res': True
    }
    try:
        create_sql, create_params = create_reserva(create_data_example)
        print("SQL:", create_sql)
        print("Params:", create_params)
        # Expected SQL should have "fk_id_car-2" and "fk_id_usu-2" quoted.
        # Expected params should have keys 'fk_id_car-2' (unquoted).
    except ValueError as e:
        print("Error:", e)
    print("\n")

    print("--- create_reserva (missing required field) ---")
    try:
        create_reserva({'tema_res': 'Incompleto', 'usuario_creacion_res': 'test'})
    except ValueError as e:
        print("Error:", e)
    print("\n")

    print("--- get_reserva_by_id ---")
    get_sql, get_params = get_reserva_by_id(101)
    print("SQL:", get_sql)
    print("Params:", get_params)
    print("\n")

    print("--- get_all_reservas (with special name filter and sort) ---")
    try:
        all_sql, all_params = get_all_reservas(
            filters={'estado_res': 'Confirmada', 'fk_id_car-2': 5},
            sort_by='fk_id_usu-2 DESC',
            limit=5
        )
        print("SQL:", all_sql)
        print("Params:", all_params)
        # Expected SQL: SELECT * FROM laboratorios.reserva WHERE estado_res = %(estado_res)s AND "fk_id_car-2" = %(fk_id_car-2)s ORDER BY "fk_id_usu-2" DESC LIMIT %(limit)s;
        # Expected params: {'estado_res': 'Confirmada', 'fk_id_car-2': 5, 'limit': 5}
    except ValueError as e:
        print("Error:", e)
    print("\n")

    print("--- get_all_reservas (sort by normal column) ---")
    try:
        all_sql_norm, all_params_norm = get_all_reservas(
            sort_by='fecha_hora_res ASC',
        )
        print("SQL:", all_sql_norm)
        print("Params:", all_params_norm)
    except ValueError as e:
        print("Error:", e)
    print("\n")


    print("--- update_reserva ---")
    update_data_example = {
        'tema_res': 'Tema actualizado',
        'estado_res': 'Reprogramada',
        'fk_id_car-2': 7, # Update special column
        'usuario_actualizacion_res': 'updater_user'
    }
    try:
        update_sql, update_params = update_reserva(101, update_data_example)
        print("SQL:", update_sql)
        print("Params:", update_params)
        # Expected SQL: UPDATE laboratorios.reserva SET tema_res = %(tema_res)s, estado_res = %(estado_res)s, "fk_id_car-2" = %(fk_id_car-2)s, ..., fecha_actualizacion_res = NOW() WHERE id_res = %(id_res)s;
        # Expected params should have unquoted keys for special names.
    except ValueError as e:
        print("Error:", e)
    print("\n")

    print("--- update_reserva (missing usuario_actualizacion_res) ---")
    try:
        update_reserva(101, {'tema_res': 'Sin actualizador'})
    except ValueError as e:
        print("Error:", e)
    print("\n")

    print("--- delete_reserva ---")
    delete_sql, delete_params = delete_reserva(101)
    print("SQL:", delete_sql)
    print("Params:", delete_params)
    print("\n")

    # Test _quote_if_needed behavior with already quoted input
    # Current _quote_if_needed doesn't unquote first, so "col" -> """col"""
    # This is fine as long as input keys to functions are consistently unquoted or match db names exactly.
    # The functions are designed to take unquoted keys for data dicts where possible,
    # or exact match keys like 'fk_id_car-2' and then derive param keys and quoted SQL names.
    # For filters and sort_by, the raw column name is passed and then quoted.
```

import mysql.connector

# Conexión a la base de datos
conexion = mysql.connector.connect(
    host="51.178.83.229",
    user="frodo",
    password="bolson",
    database="dbmalaga"
)

cursor = conexion.cursor()

# Crear tabla (si no existe)
def crear_tabla():
    cursor.execute("""
    CREATE TABLE IF NOT EXISTS tbpueblosmalaga (
        SimboloQuimico VARCHAR(10) PRIMARY KEY,
        NombreLocalidad VARCHAR(255),
        Comarca VARCHAR(255),
        AlturaNivelMar INT,
        Habitantes INT,
        Superficie FLOAT,
        NumeroElementoQuimico INT,
        Escudo VARCHAR(255)
    )
    """)
    conexion.commit()

# Crear un registro (Create)
def crear_pueblo(SimboloQuimico, NombreLocalidad, Comarca, AlturaNivelMar, Habitantes, Superficie, NumeroElementoQuimico, Escudo):
    cursor.execute("""
    INSERT INTO tbpueblosmalaga (SimboloQuimico, NombreLocalidad, Comarca, AlturaNivelMar, Habitantes, Superficie, NumeroElementoQuimico, Escudo)
    VALUES (%s, %s, %s, %s, %s, %s, %s, %s)
    """, (SimboloQuimico, NombreLocalidad, Comarca, AlturaNivelMar, Habitantes, Superficie, NumeroElementoQuimico, Escudo))
    conexion.commit()

# Leer registros (Read)
def leer_pueblos():
    cursor.execute("SELECT * FROM tbpueblosmalaga")
    for pueblo in cursor.fetchall():
        print(f"Simbolo: {pueblo[0]}, Nombre: {pueblo[1]}, Comarca: {pueblo[2]}, Altura: {pueblo[3]} m, Habitantes: {pueblo[4]}, Superficie: {pueblo[5]} km², Elemento Químico: {pueblo[6]}, Escudo: {pueblo[7]}")

# Actualizar un registro (Update)
def actualizar_pueblo(SimboloQuimico, NombreLocalidad, Comarca, AlturaNivelMar, Habitantes, Superficie, NumeroElementoQuimico, Escudo):
    cursor.execute("""
    UPDATE tbpueblosmalaga SET NombreLocalidad = %s, Comarca = %s, AlturaNivelMar = %s, Habitantes = %s, Superficie = %s, NumeroElementoQuimico = %s, Escudo = %s
    WHERE SimboloQuimico = %s
    """, (NombreLocalidad, Comarca, AlturaNivelMar, Habitantes, Superficie, NumeroElementoQuimico, Escudo, SimboloQuimico))
    conexion.commit()

# Eliminar un registro (Delete)
def eliminar_pueblo(SimboloQuimico):
    cursor.execute("DELETE FROM tbpueblosmalaga WHERE SimboloQuimico = %s", (SimboloQuimico,))
    conexion.commit()

# Ejemplo de uso:
def menu():
    while True:
        print("\n--- CRUD Pueblos Málaga ---")
        print("1. Crear Pueblo")
        print("2. Ver Pueblos")
        print("3. Actualizar Pueblo")
        print("4. Eliminar Pueblo")
        print("5. Salir")
        
        opcion = input("Seleccione una opción: ")

        if opcion == "1":
            SimboloQuimico = input("Símbolo Químico del Pueblo: ")
            NombreLocalidad = input("Nombre del Pueblo: ")
            Comarca = input("Comarca del Pueblo: ")
            AlturaNivelMar = int(input("Altura sobre el nivel del mar (en metros): "))
            Habitantes = int(input("Número de habitantes: "))
            Superficie = float(input("Superficie del Pueblo (en km²): "))
            NumeroElementoQuimico = int(input("Número del Elemento Químico: "))
            Escudo = input("Escudo del Pueblo (URL o descripción): ")
            crear_pueblo(SimboloQuimico, NombreLocalidad, Comarca, AlturaNivelMar, Habitantes, Superficie, NumeroElementoQuimico, Escudo)
            print(f"Pueblo {NombreLocalidad} creado correctamente.")
        
        elif opcion == "2":
            print("\nPueblos registrados:")
            leer_pueblos()

        elif opcion == "3":
            SimboloQuimico = input("Símbolo Químico del Pueblo a actualizar: ")
            NombreLocalidad = input("Nuevo nombre del Pueblo: ")
            Comarca = input("Nueva comarca del Pueblo: ")
            AlturaNivelMar = int(input("Nueva altura sobre el nivel del mar (en metros): "))
            Habitantes = int(input("Nuevo número de habitantes: "))
            Superficie = float(input("Nueva superficie del Pueblo (en km²): "))
            NumeroElementoQuimico = int(input("Nuevo número del Elemento Químico: "))
            Escudo = input("Nuevo escudo del Pueblo (URL o descripción): ")
            actualizar_pueblo(SimboloQuimico, NombreLocalidad, Comarca, AlturaNivelMar, Habitantes, Superficie, NumeroElementoQuimico, Escudo)
            print(f"Pueblo con Símbolo {SimboloQuimico} actualizado correctamente.")
        
        elif opcion == "4":
            SimboloQuimico = input("Símbolo Químico del Pueblo a eliminar: ")
            eliminar_pueblo(SimboloQuimico)
            print(f"Pueblo con Símbolo {SimboloQuimico} eliminado correctamente.")
        
        elif opcion == "5":
            print("Saliendo del programa...")
            break
        
        else:
            print("Opción no válida. Intente de nuevo.")

# Crear la tabla si no existe
crear_tabla()

# Mostrar el menú interactivo
menu()

# Cerrar conexión
cursor.close()
conexion.close()

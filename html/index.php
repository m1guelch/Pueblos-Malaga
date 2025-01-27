<!DOCTYPE html>
<html>
<head>
    <title>CRUD Completo - tbpueblosmalaga</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f9;
            color: #333;
        }
        h1 {
            color: #2c3e50;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }
        form {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            text-align: left;
            padding: 10px;
        }
        th {
            background-color: #3498db;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #eaf2f8;
        }
        .btn {
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
            color: white;
            cursor: pointer;
            margin-right: 5px;
        }

        .btn-edit {
            background-color: #3498db; /* Azul */
            border: 1px solid #2980b9;
        }

        .btn-edit:hover {
            background-color: #2980b9;
        }

	.btn-delete {
	    background-color: #e74c3c; /* Rojo */
	    border: 1px solid #c0392b;
	}

	.btn-delete:hover {
	    background-color: #c0392b;
	}
	.btn-add {
	    background-color: #2ecc71; /* Verde */
	    border: 1px solid #27ae60;
	    color: white;
	    padding: 10px 15px;
	    font-size: 14px;
	    border-radius: 4px;
	    cursor: pointer;
	}
	.btn-add:hover {
	    background-color: #27ae60; /* Verde más oscuro en hover */
	}
    </style>
</head>
<body>
    <h1>CRUD Completo - tbpueblosmalaga</h1>

    <?php
    $servername = "bd_mysql";
    $username = "frodo";
    $password = "bolson";
    $dbname = "dbmalaga";

    // Crear conexión
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Comprobar conexión
    if ($conn->connect_error) {
        die("<p style='color: red;'>Conexión fallida: " . $conn->connect_error . "</p>");
    }

    // ** Operación: Recuperar datos para edición **
    $edit_data = null;
    if (isset($_GET['edit'])) {
        $simbolo = $_GET['edit']; // Obtener el símbolo químico de la URL
        $stmt = $conn->prepare("SELECT * FROM tbpueblosmalaga WHERE SimboloQuimico = ?");
        $stmt->bind_param("s", $simbolo);
        $stmt->execute();
        $result = $stmt->get_result();
        $edit_data = $result->fetch_assoc(); // Guardar los datos del registro seleccionado
        $stmt->close();
    }

    // ** Operación: Crear registro **
    if (isset($_POST['create'])) {
        $simbolo = $_POST['simbolo'] ?? '';
        $nombre = $_POST['nombre'] ?? '';
        $comarca = $_POST['comarca'] ?? '';
        $altura = $_POST['altura'] ?? 0;
        $habitantes = $_POST['habitantes'] ?? 0;
        $superficie = $_POST['superficie'] ?? 0;
        $numero = $_POST['numero'] ?? 0;

        $stmt = $conn->prepare("INSERT INTO tbpueblosmalaga (SimboloQuimico, NombreLocalidad, Comarca, AlturaNivelMar, Habitantes, Superficie, NumeroElementoQuimico) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssdiid", $simbolo, $nombre, $comarca, $altura, $habitantes, $superficie, $numero);
        $stmt->execute();
        $stmt->close();

        echo "<p style='color: green;'>Registro añadido con éxito.</p>";
    }

    // ** Operación: Eliminar registro **
    if (isset($_GET['delete'])) {
        $simbolo = $_GET['delete']; // Cambiar $id por $simbolo
        $stmt = $conn->prepare("DELETE FROM tbpueblosmalaga WHERE SimboloQuimico = ?"); // Usar SimboloQuimico
        $stmt->bind_param("s", $simbolo); // Cambiar tipo a "s" para string
        $stmt->execute();
        $stmt->close();

        echo "<p style='color: red;'>Registro eliminado con éxito.</p>";
    }

    // ** Operación: Actualizar registro **
    if (isset($_POST['update'])) {
        $simbolo = $_POST['simbolo']; // Usamos el simbolo como identificador
        $nombre = $_POST['nombre'];
        $comarca = $_POST['comarca'];
        $altura = $_POST['altura'];
        $habitantes = $_POST['habitantes'];
        $superficie = $_POST['superficie'];
        $numero = $_POST['numero'];

    // Actualizamos el registro usando el símbolo como identificador
        $stmt = $conn->prepare("UPDATE tbpueblosmalaga SET NombreLocalidad = ?, Comarca = ?, AlturaNivelMar = ?, Habitantes = ?, Superficie = ?, NumeroElementoQuimico = ? WHERE SimboloQuimico = ?");
        $stmt->bind_param("ssdiids", $nombre, $comarca, $altura, $habitantes, $superficie, $numero, $simbolo);
        $stmt->execute();
        $stmt->close();

        echo "<p style='color: orange;'>Registro actualizado con éxito.</p>";
    }
    ?>

    <form method="post">
        <h2><?php echo $edit_data ? "Editar registro" : "Añadir nuevo registro"; ?></h2>
        <label>Símbolo Químico:</label>
        <input type="text" name="simbolo" value="<?php echo htmlspecialchars($edit_data['SimboloQuimico'] ?? ''); ?>" <?php echo $edit_data ? "readonly" : "required"; ?>><br>
        <label>Nombre Localidad:</label>
        <input type="text" name="nombre" value="<?php echo htmlspecialchars($edit_data['NombreLocalidad'] ?? ''); ?>" required><br>
        <label>Comarca:</label>
        <input type="text" name="comarca" value="<?php echo htmlspecialchars($edit_data['Comarca'] ?? ''); ?>" required><br>
        <label>Altura (msnm):</label>
        <input type="number" name="altura" step="1" value="<?php echo htmlspecialchars($edit_data['AlturaNivelMar'] ?? ''); ?>" required><br>
        <label>Habitantes:</label>
        <input type="number" name="habitantes" step="1" value="<?php echo htmlspecialchars($edit_data['Habitantes'] ?? ''); ?>" required><br>
        <label>Superficie:</label>
        <input type="number" name="superficie" step="0.1" value="<?php echo htmlspecialchars($edit_data['Superficie'] ?? ''); ?>" required><br>
        <label>Número Elemento Químico:</label>
        <input type="number" name="numero" step="1" value="<?php echo htmlspecialchars($edit_data['NumeroElementoQuimico'] ?? ''); ?>" required><br><br>
	<button class="btn btn-add" type="submit" name="<?php echo $edit_data ? 'update' : 'create'; ?>">
	    <?php echo $edit_data ? "Actualizar" : "Añadir"; ?>
	</button>
    </form>

    <?php
    // ** Visualizar registros **
    $result = $conn->query("SELECT * FROM tbpueblosmalaga");
    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<tr><th>ID</th><th>Símbolo Químico</th><th>Nombre Localidad</th><th>Comarca</th><th>Altura</th><th>Habitantes</th><th>Superficie</th><th>Número Elemento</th><th>Acciones</th></tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['id'] ?? 'N/A') . "</td>";
            echo "<td>" . htmlspecialchars($row['SimboloQuimico'] ?? 'N/A') . "</td>";
            echo "<td>" . htmlspecialchars($row['NombreLocalidad'] ?? 'N/A') . "</td>";
            echo "<td>" . htmlspecialchars($row['Comarca'] ?? 'N/A') . "</td>";
            echo "<td>" . htmlspecialchars($row['AlturaNivelMar'] ?? 'N/A') . "</td>";
            echo "<td>" . htmlspecialchars($row['Habitantes'] ?? 'N/A') . "</td>";
            echo "<td>" . htmlspecialchars($row['Superficie'] ?? 'N/A') . "</td>";
            echo "<td>" . htmlspecialchars($row['NumeroElementoQuimico'] ?? 'N/A') . "</td>";
            echo "<td>";
            echo "<a class='btn btn-edit' href='?edit=" . htmlspecialchars($row['SimboloQuimico']) . "'>Editar</a>";
            echo " ";
            echo "<a class='btn btn-delete' href='?delete=" . htmlspecialchars($row['SimboloQuimico']) . "'>Eliminar</a>";
            echo "</td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "<p>No hay registros en la base de datos.</p>";
    }

    $conn->close();
    ?>
</body>
</html>

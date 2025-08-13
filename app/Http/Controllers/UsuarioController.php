<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    // Simulación de datos de usuarios (en vez de base de datos)
    private $usuarios = [
        1 => ['id' => 1, 'nombre' => 'Juan', 'email' => 'juan@email.com'],
        2 => ['id' => 2, 'nombre' => 'Ana', 'email' => 'ana@email.com'],
        3 => ['id' => 3, 'nombre' => 'Luis', 'email' => 'luis@email.com'],
    ];

    public function listar()
    {
        return response()->json(array_values($this->usuarios));
    }

    // Ver un usuario por ID
    public function ver($id)
    {
        if (isset($this->usuarios[$id])) {
            return response()->json($this->usuarios[$id]);
        }
        return response()->json(['error' => 'Usuario no encontrado'], 404);
    }

    // Actualizar usuario
    public function actualizar(Request $request, $id)
    {
        if (!isset($this->usuarios[$id])) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        $nombre = $request->input('nombre', $this->usuarios[$id]['nombre']);
        $email = $request->input('email', $this->usuarios[$id]['email']);

        $this->usuarios[$id]['nombre'] = $nombre;
        $this->usuarios[$id]['email'] = $email;

        return response()->json([
            'message' => 'Usuario actualizado exitosamente',
            'usuario' => $this->usuarios[$id]
        ]);
    }

    // Eliminar usuario
    public function eliminar($id)
    {
        if (!isset($this->usuarios[$id])) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        unset($this->usuarios[$id]);

        return response()->json([
            'message' => 'Usuario eliminado exitosamente',
            'id' => $id
        ]);
    }
}
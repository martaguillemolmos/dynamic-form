<?php
namespace App\Http\Controllers;

use App\Models\FormTemplate;
use Illuminate\Http\Request;

class FormTemplateController extends Controller
{
    // Visualizar todos los formularios
    public function index()
    {
        return FormTemplate::all();
    }

    // Crear un nuevo formulario 
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'fields' => 'required|array',
            'fields.*.label' => 'required|string',
            'fields.*.name' => 'required|string',
            'fields.*.type' => 'required|string',
            'fields.*.options' => 'nullable|array',
        ]);

        $form = FormTemplate::create($data);

        return response()->json($form, 201);
    }

    // Mostrar un formulario por su id
    public function show($id)
    {
        return FormTemplate::findOrFail($id);
    }

    // Actualizar un formulario por su id
    public function update(Request $request, $id)
    {
        $form = FormTemplate::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string',
            'fields' => 'required|array',
            'fields.*.label' => 'required|string',
            'fields.*.name' => 'required|string',
            'fields.*.type' => 'required|string',
            'fields.*.options' => 'nullable|array',
        ]);

        $form->update($data);

        return response()->json($form);
    }

    // Eliminar un formulario por su id
    public function destroy($id)
    {
        $form = FormTemplate::findOrFail($id);
        $form->delete();

        return response()->json(['message' => 'Formulario eliminado correctamente.']);
    }
}
